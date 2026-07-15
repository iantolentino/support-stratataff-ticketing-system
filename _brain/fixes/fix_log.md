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
| F003 | After the T015 live deploy, an oversized "Ticketing System" heading still showed at the top of the live Tickets page even though it was removed locally (T009a) | DEPLOY | Elementor stores page layout in `_elementor_data` (a `wp_postmeta` JSON blob) — a DATABASE value, not a file. File uploads (the 7 files deployed for T015) never touch the database, so a widget removal made locally in Elementor never traveled to live. Fixed by deleting the widget directly in the live page's own Elementor editor (manual, one-time, DB-side fix — no file involved). Rule going forward: before/after any file-based deploy, check whether the local changes being shipped also included any Elementor edits (widget add/remove/resize/reposition) — those need a *separate* manual re-application on the live page's editor, they will never show up just because files were uploaded. | inline | 2026-07-15 | FIXED |
| F004 | Real employee names hardcoded in `Multi-Input-Text-Display-Plugi/text-display-plugin.php`'s own source code, committed to the public repo since its first commit | SECURITY | `tdp_get_person_name()` had a literal array of 5 real first names — predates this session and the T014 doc-redaction pass, which only covered `_brain/` docs, not plugin source files. Found via a post-deploy grep sweep across all tracked files (not just this session's). Fixed by replacing with generic labels ("Agent 1"–"Agent 5") — display-label-only change, doesn't touch `update_option`/`get_option` calls or any saved option data. **Not yet re-deployed to live** as of 2026-07-15 — the live copy of this file still has the real names until re-uploaded. Rule going forward: a PII sweep should check plugin/theme SOURCE files too, not just `_brain/` docs — grep the whole tracked repo (`git ls-files`), not just files touched in the current session; and NEVER quote the actual PII in a fix-log entry describing the fix — describe it generically instead. | inline | 2026-07-15 | FIXED (local only — live re-upload pending) |

---

## Usage Rule

- Skim the table only. Open a detail file ONLY if its title matches the current problem.
- If no match exists, proceed with normal debugging, then add a new row here before stopping.
- Keep "Root Cause" to one line — that line is what future AI sessions scan for a match.
