/**
 * Global type declarations for the Greenteams WordPress theme.
 *
 * Declares WordPress/Flynt-specific globals and augments the Window interface.
 */

interface FlyntData {
  componentsWithScript: Record<string, string>
  templateDirectoryUri: string
}

interface Window {
  FlyntData: FlyntData
  Alpine: import('alpinejs').Alpine
  requestIdleCallback: (
    callback: (deadline: IdleDeadline) => void,
    options?: IdleRequestOptions
  ) => number
  cancelIdleCallback: (handle: number) => void
}

/** ACF (Advanced Custom Fields) global */
declare const acf: {
  addAction: (action: string, callback: (...args: any[]) => void) => void
  addFilter: (filter: string, callback: (...args: any[]) => any) => void
  [key: string]: any
}

/** Type for Flynt component script default export */
type FlyntComponentScript = (el: HTMLElement) => void | ((el: HTMLElement) => void)

/** Vite's import.meta.glob return type */
type GlobImport = Record<string, () => Promise<{ default: FlyntComponentScript }>>
