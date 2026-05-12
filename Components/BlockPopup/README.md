# Block Popup

Modal popup managed as a Reusable Component, selected per page.

- **Authoring**: editors create one or more **Reusable Components** posts and add a **Popup (Modal)** layout to each. This is the popup's content (image, position, content, newsletter button, colors, popup ID, show delay, backdrop close).
- **Per-page selection**: every post/page has a sidebar field group **Popup → Popup**, a post-object dropdown listing all reusable components. The empty state ("No popup") is the default and disables the popup on that page.
- **Rendering**: rendered globally from `templates/_document.twig` via `renderComponent('BlockPopup')`. On `is_singular()` pages, the component's data filter reads the selected reusable post, finds its `BlockPopup` layout in `reusableComponents`, and merges those fields. If nothing is selected, `displayPopup` is false and the twig outputs nothing.
- **Dismissal**: stored in `localStorage` under `blockPopup:<popupId>-<reusablePostId>` — the suffix means each reusable popup has its own dismissal record. Change the *Popup ID* field inside the reusable to re-show after edits.
- **Close**: close button, ESC key, or backdrop click (toggleable). Body scroll is locked while open.
