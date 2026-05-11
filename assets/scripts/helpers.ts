type RefMap<M extends boolean> = M extends true
  ? Record<string, NodeListOf<Element>>
  : Record<string, Element | null>

export function buildRefs<M extends boolean = false> (
  el: Element,
  multiple?: M,
  customRefs: Record<string, string> = {}
): RefMap<M> {
  const DA = multiple ? 'data-refs' : 'data-ref'
  return new Proxy(
    {} as RefMap<M>,
    {
      get (target: Record<string, any>, prop: string) {
        if (!target[prop]) {
          const selector = customRefs[prop] ?? `[${DA}="${prop}"]`
          target[prop] = multiple
            ? el.querySelectorAll(selector)
            : el.querySelector(selector)
          if (!target[prop]) {
            if (import.meta.env.DEV) {
              console.warn(`ref ${prop} not found.`)
            }
          }
        }
        return target[prop]
      }
    }
  )
}

export function getJSON (
  node: Element,
  selector: string = 'script[type="application/json"]',
  property: string = 'textContent'
): Record<string, unknown> {
  let data: Record<string, unknown> = {}
  try {
    data = JSON.parse((node.querySelector(selector) as any)?.[property])
  } catch (e) { }
  return data
}
