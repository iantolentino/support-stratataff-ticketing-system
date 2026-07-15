# FIX LOG

> Read this file FIRST before debugging anything. It is the entire memory of every bug this
> repo has already solved. Most entries should need nothing more than this table.

---

## Format

```
| ID   | Title                        | Category  | Root Cause (1 line)          | Detail File          | Date       | Status |
|------|------------------------------|-----------|-------------------------------|-----------------------|------------|--------|
| F001 | [Short bug description]     | WEB       | [One-line cause]              | inline / F001-slug.md | YYYY-MM-DD | FIXED  |
```

Categories: `WEB` | `BACKEND` | `DB` | `AUTH` | `BUILD` | `DEPLOY` | `AUTOMATION` | `CLI` | `INFRA` | `OTHER`

Status: `FIXED` | `WORKAROUND` (not a real fix, revisit) | `SUPERSEDED` (see linked replacement)

---

## Log

| ID | Title | Category | Root Cause (1 line) | Detail File | Date | Status |
|----|-------|----------|----------------------|-------------|------|--------|
| F001 | Global navbar (T016) caused blank white page on all 4 front-end pages | WEB | Navbar was JS-inserted as the very first child of `<body>` in normal document flow (`insertBefore(el, body.firstChild)`) with a `float:left; width:100%` rule inherited from SupportCandy's own CSS — interacting badly with the page's own layout (Elementor canvas / theme block layout) and collapsing the rest of the page content. Not exhaustively root-caused; fixed by removing the risk class entirely rather than chasing the exact interaction. | inline | 2026-07-15 | FIXED |
| F002 | Home page "Shift Assignments" stopped reflecting real edits made via "Assisting Ticket #" | WEB | The Home page's shift-assignment display is actually the LIVE `[text_display]` shortcode (`Multi-Input-Text-Display-Plugi/text-display-plugin.php`), reading `tdp_display_text_N`/`tdp_shift_schedule_N` from `wp_options` on every load — the exact same options "Assisting Ticket #" (`[text_input_form]`) writes to via `update_option()`. A navbar redesign pass mistakenly hid that live widget and replaced the visible content with a hardcoded JS array (a one-time copy of the values), silently breaking the live connection — edits kept saving correctly, they just had nowhere left to display. Fixed by deleting the hardcoded array and instead relocating (not cloning) the real live DOM node to sit below the navbar, restyled in place by reading its live text/values, never re-typing them. Rule going forward: never hardcode a copy of plugin-rendered data into JS — always relocate/restyle the actual live element. | inline | 2026-07-15 | FIXED |

---

## Usage Rule

- Skim the table only. Open a detail file ONLY if its title matches the current problem.
- If no match exists, proceed with normal debugging, then add a new row here before stopping.
- Keep "Root Cause" to one line — that line is what future AI sessions scan for a match.
