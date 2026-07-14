# PROGRESS

> The AI reads this file at the start of every EXECUTION_MODE session.
> Update after every completed or blocked task.

---

## Active Task
> None — awaiting user's next layout target (single ticket/thread view, or global CSS pass)

---

## In Progress
| ID   | Task                  | Blocker        |
|------|-----------------------|----------------|
|      |                       |                |

---

## Completed
| ID   | Task                                                                 | Date Completed |
|------|-----------------------------------------------------------------------|----------------|
| T001 | Located "Immediate Superior" dropdown (SupportCandy custom field 39, table `psmsc_options`) | 2026-07-13 |
| T002 | Added new option "Marc Dhaniel Batac" to Immediate Superior dropdown (local DB) | 2026-07-13 |
| T003 | Hid 3 options (Georgia Galang, Irish Nicolette Sicat, Rosallie Chua) via `custom_field = -39` trick, reversible | 2026-07-13 |
| T004 | Built `_brain` memory system into real project state (app_context, architecture, scope, decisions, commands) | 2026-07-14 |
| T005 | Built UI backup/revert tooling (`_brain/tools/ui-backup.ps1`, `ui-restore.ps1`) for safe layout testing before live deploy | 2026-07-14 |
| T006 | Restyled ticket list table (`.wpsc-ticket-list-tbl` in `framework/style.css`) — rounded borders, styled header, zebra striping, hover state. Verified visually via automated Playwright screenshot (local admin login) on both wp-admin and front-end `/tickets/?wpsc-section=ticket-list` (shares same CSS class) | 2026-07-14 |
| T006b | Set up local dev environment for actual browser testing: distinct vhost `support-local.test` (separate from live to avoid confusion), local WP admin account (`local_admin`), local MySQL user matching wp-config credentials, mock tickets, PHP warning suppression, Playwright screenshot verification loop | 2026-07-14 |
| T006c | Restyled the "Assisting Ticket # / Canned Reply Templates" sidebar panel on the `/tickets/` page (Elementor-built): consolidated 5 separate raw-text widgets into one collapsible accordion per section, removed duplicate/redundant widgets, restyled the shift-assignment form (`Multi-Input-Text-Display-Plugi` plugin output) with a `<details>` collapse wrapper — same fields/options/logic, presentation only | 2026-07-14 |
| T007 | Restyled New Ticket form (`.wpsc-create-ticket` card wrapper, rounded/bordered inputs+selects+textarea, focus states, modern buttons) — verified via front-end `/tickets/?wpsc-section=new-ticket` screenshot (wp-admin route is broken, see Known Bug below, so front-end is now the standard QA path) | 2026-07-14 |
| T007b | Restyled top navbar (`.wpsc-header`/`.wpsc-tickets-nav`) — rounded pill hover/active state, subtle shadow for depth, better spacing. Kept existing admin-configured brand color (`#094bc1`) untouched | 2026-07-14 |
| T008 | Restyled single ticket/thread view sidebar widgets (`.wpsc-it-widget`) — rounded corners + subtle shadow per card, kept existing brand colors (dark green header `#324b4a`) | 2026-07-14 |
| T009a | Repositioned/shrunk header logo to small top-left placement (Elementor widget `3921805`: width 160px, align left) and removed the oversized "Ticketing System" heading widget (`e9c3d88`) from the `/tickets/` page — per user request | 2026-07-14 |
| T009b | Fixed ticket-list zebra striping/hover at the source: updated SupportCandy's own "Appearance" settings option (`wpsc-ap-ticket-list`) which was forcing flat white-on-white rows with no hover, overriding our CSS. Even rows now `#f9fafb`, hover `#eef2f6` — matches the rest of the shadcn-style palette used elsewhere | 2026-07-14 |
| T009c | Global "shadcn-style" plain-CSS design pass: system-font stack applied site-wide, search box border+radius, main container radius/shadow bumped for consistency with other cards. Explicitly NOT a React/shadcn rebuild — user confirmed DB/functionality unchanged, CSS-only, cPanel-compatible (see `decisions/decision_log.md`) | 2026-07-14 |
| T009d | Fixed the real cause of the "boxed/cramped" look user reported: (1) `.wpsc-shortcode-container` had a hardcoded blue `border: 1px solid #094bc1` from the plugin's own appearance color, replaced with `border: none !important` inline (matching the pattern SupportCandy already uses on 3 other shortcode variants) so only our soft shadow/radius shows; (2) header Elementor section (id `6901aa1`) was still `boxed` layout while the body below was `full_width`, causing misalignment — set header to `full_width` too with proper padding. Confirmed already-correct: main content/sidebar column split is 75/25 (`_inline_size`), not 50/50 as initially suspected | 2026-07-14 |
| T010 | Built a live Kanban-vs-modern-table comparison artifact (using real ticket data) so user could pick a redesign direction before further rebuild. User chose **Modern Table** + requested a light/dark toggle | 2026-07-14 |
| T011 | Implemented site-wide light/dark theme toggle: new isolated file `wp-content/mu-plugins/wpsc-theme-toggle.php` (pure front-end CSS/JS, no DB/functional changes) — injects a sun/moon button into `.wpsc-header`, persists choice in `localStorage`. Changed to **always default to light mode** (ignores OS `prefers-color-scheme`) per user request — only switches when the user clicks the toggle. Dark overrides cover ticket-list table (fixed an initial bug where odd rows stayed white/unreadable), our custom sidebar panels, inputs, modal, and the Elementor sidebar column's inline background. Verified both themes via Playwright screenshots | 2026-07-14 |
| T012 | Implemented the actual "Modern Table" visual redesign (not just base polish) the user picked from the T010 comparison: new isolated file `wp-content/mu-plugins/wpsc-modern-table.php` — a `MutationObserver`-driven script that (1) renders a 4-tile stat strip above the ticket table (Open/Processing/Awaiting-Hold/Closed counts, computed client-side from currently-rendered rows — no new backend/AJAX), (2) adds a colored dot inside each existing status pill, (3) prepends an avatar-circle-with-initials before each customer name. Purely additive DOM decoration — never removes/replaces existing elements, so click handlers/sorting/filtering/pagination are untouched. Verified via screenshot | 2026-07-14 |
| T013 | Full app-shell + navbar redesign per explicit user feedback ("still looks boxed", "blue navbar is bad", "move sidebar panels into navbar"): new isolated file `wp-content/mu-plugins/wpsc-navbar-redesign.php` — (1) removed the "boxed card" look (`.wpsc-shortcode-container` now full-bleed: no border-radius/shadow/margin), sidebar column (`elementor-element-0d2c8e0`) hidden and main content column (`elementor-element-279585d`) expanded to 100% width so the table now shows every column including Date Updated/Created; (2) replaced the flat solid-blue `.wpsc-header` with a clean white navbar + bottom border matching the artifact's "Modern Table" mockup exactly (light active/hover pill states, not white-on-blue) — user explicitly asked to match the mockup's navbar while keeping our existing small top-left logo; (3) relocated the "Assisting Ticket #" and "Canned Reply Templates" panels (moved the actual existing DOM nodes, not duplicated) into two new navbar dropdown buttons, freeing the sidebar space entirely. Fixed a bug where the "Assisting Ticket #" panel didn't relocate (wrong selector `.wpsc-helper-panel`, an orphaned class from an abandoned `post_content` edit that Elementor never renders — real class is `.tdp-panel` from `text-display-plugin.php`), and a bug where both dropdown panels rendered empty (they're native `<details>`, default closed, and hiding their `<summary>` toggle meant no way to open them — now forced `.open = true` on relocation). Verified both dropdowns open with correct content via Playwright | 2026-07-14 |
| T013b | Fixed two bugs surfaced by user right after T013 shipped: (1) theme toggle button was invisible (still styled white-icon-on-white for the old dark/blue navbar, invisible on the new white one) — recolored to match the new navbar's neutral palette, with its own dark-mode variant; button position confirmed already correct (immediately left of Logout, i.e. "beside" it, per user's request). (2) Large vertical gap between the small top-left logo and the navbar — reduced the Elementor logo section's own padding (`elementor_data`, section id `6901aa1`, top-level padding 16→6px) and closed the residual gap via `margin-bottom: -20px` on that section. Took the user's own suggested "easier" fix (tighten spacing) over the alternative (merge logo into navbar row) | 2026-07-14 |
| T013c | User still wanted the theme toggle moved: instead of inline in the navbar row, it now floats OUTSIDE the navbar entirely via `position: fixed; top:10px; right:32px`, sitting above the Logout button rather than beside it. Logo/navbar gap confirmed fine by user, no further change there | 2026-07-14 |

---

## Blocked
| ID   | Task                  | Reason         |
|------|-----------------------|----------------|
|      |                       |                |

---

## Known Bug (pre-existing, not caused by our changes — see `db_backup/backup_policy.md`)
`wp-admin/*` pages are broken locally (Elementor event-tracker table conflict causes near-empty
responses on every admin page, even the plain dashboard). Front-end `/tickets/` pages work fine
and are the real staff workflow — use those for all layout QA going forward. Not in scope to fix
unless user asks separately.

---

Last updated: 2026-07-14
Current phase: Maintenance — layout-only improvement pass (no new functionality)
