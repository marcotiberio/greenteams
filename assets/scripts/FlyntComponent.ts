import './rIC.js'

type ComponentModule = { default: FlyntComponentScript }
type ScriptImporter = () => Promise<ComponentModule>

const componentsWithScripts = import.meta.glob('@/Components/**/script.{ts,js}') as Record<string, ScriptImporter>

const interactionEvents = new Set<string>([
  'pointerdown',
  'scroll'
])

const upgradedElements = new WeakMap<HTMLElement, void | ((el: HTMLElement) => void)>()
const FlyntComponents = new WeakMap<HTMLElement, [Promise<void>, () => void]>()
const parents = new WeakMap<HTMLElement, HTMLElement | null>()

type LoadingStrategy = 'load' | 'idle' | 'visible' | 'interaction'

export default class FlyntComponent extends HTMLElement {
  observer?: IntersectionObserver
  mediaQueryList?: MediaQueryList

  constructor () {
    super()
    let setReady: () => void
    const isReady = new Promise<void>((resolve) => {
      setReady = resolve
    })
    FlyntComponents.set(this, [isReady, setReady!])
  }

  async connectedCallback (): Promise<void> {
    if (hasScript(this)) {
      const loadingStrategy = determineLoadingStrategy(this)
      const loadingFunctionWrapper = getLoadingFunctionWrapper(loadingStrategy, this)
      const mediaQuery = getMediaQuery(this)
      const loadingFunction = getLoadingFunction(this)

      if (mediaQuery) {
        await mediaQueryMatches(mediaQuery, this)
      }

      if (hasParent(this)) {
        const [parentLoaded] = FlyntComponents.get(parents.get(this)!)!
        await parentLoaded
      }

      loadingFunctionWrapper(loadingFunction)
    } else {
      setComponentReady(this)
    }
  }

  disconnectedCallback (): void {
    this.observer?.disconnect()
    this.mediaQueryList?.removeEventListener('change', () => {})
    cleanupElement(this)
  }
}

function getComponentPath (node: HTMLElement): string | undefined {
  const componentName = node.getAttribute('name')
  return componentName ? window.FlyntData.componentsWithScript[componentName] : undefined
}

function hasScript (node: HTMLElement): boolean {
  const componentPath = getComponentPath(node)
  return !!componentPath
}

function getScriptImport (node: HTMLElement): ScriptImporter | undefined {
  const componentPath = getComponentPath(node)
  return componentsWithScripts[`/Components/${componentPath}/script.ts`] ??
    componentsWithScripts[`/Components/${componentPath}/script.js`]
}

function hasParent (node: HTMLElement): boolean {
  if (!parents.has(node)) {
    const parent = node.parentElement?.closest<HTMLElement>('flynt-component') ?? null
    parents.set(node, parent)
    return !!parent
  } else {
    return !!parents.get(node)
  }
}

function setComponentReady (node: HTMLElement): void {
  const entry = FlyntComponents.get(node)
  if (entry) {
    entry[1]()
  }
}

function visible (node: FlyntComponent): Promise<boolean> {
  return new Promise(function (resolve) {
    const observer = new IntersectionObserver(function (entries) {
      for (const entry of entries) {
        if (entry.isIntersecting) {
          observer.disconnect()
          resolve(true)
        }
      }
    })
    observer.observe(node)
    node.observer = observer
  })
}

function mediaQueryMatches (query: string, node: FlyntComponent): Promise<boolean> {
  return new Promise(function (resolve) {
    const mediaQueryList = window.matchMedia(query)
    if (mediaQueryList.matches) {
      resolve(true)
    } else {
      mediaQueryList.addEventListener(
        'change',
        () => resolve(true),
        { once: true }
      )
    }
    node.mediaQueryList = mediaQueryList
  })
}

function determineLoadingStrategy (node: HTMLElement): LoadingStrategy {
  const defaultStrategy: LoadingStrategy = 'load'
  const strategies: Record<string, LoadingStrategy> = {
    load: 'load',
    idle: 'idle',
    visible: 'visible',
    interaction: 'interaction'
  }
  return strategies[node.getAttribute('load:on') ?? ''] ?? defaultStrategy
}

function getLoadingFunctionWrapper (
  strategyName: LoadingStrategy,
  node: FlyntComponent
): (fn: () => void) => void {
  const loadingFunctions: Record<LoadingStrategy, (fn: () => void) => void> = {
    load: (x) => x(),
    idle: (x) => requestIdleCallback(x, { timeout: 2000 }),
    visible: async (x) => {
      await visible(node)
      x()
    },
    interaction: (x) => {
      const load = () => {
        interactionEvents.forEach((event) =>
          document.removeEventListener(event, load)
        )
        x()
      }
      interactionEvents.forEach((event) =>
        document.addEventListener(event, load, { once: true })
      )
    }
  }
  const defaultFn = loadingFunctions.load
  return loadingFunctions[strategyName] ?? defaultFn
}

function getMediaQuery (node: HTMLElement): string | null {
  return node.hasAttribute('load:on:media') ? node.getAttribute('load:on:media') : null
}

function getLoadingFunction (node: HTMLElement): () => Promise<void> {
  return async () => {
    const componentScriptImport = getScriptImport(node)
    if (!componentScriptImport) return
    const componentScript = await componentScriptImport()
    if (typeof componentScript.default === 'function' && !upgradedElements.has(node)) {
      const cleanupFn = componentScript.default(node)
      upgradedElements.set(node, cleanupFn)
    }
    setComponentReady(node)
  }
}

function cleanupElement (node: HTMLElement): void {
  if (upgradedElements.has(node)) {
    const cleanupFn = upgradedElements.get(node)
    if (typeof cleanupFn === 'function') {
      cleanupFn(node)
    }
    upgradedElements.delete(node)
  }
}
