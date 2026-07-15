# CURRENT STATE

> The AI reads this file at the start of every EXECUTION_MODE session.
> Update this file at the end of every session — before stopping.

---

## System State
EXECUTION_MODE

## Current Phase
Maintenance — layout-only improvement pass (no new functionality; see `governance/scope.md`).
Local rebuild is functionally done; next real milestone is deploying it to the live cPanel site.

## Last Completed Task
T016/T016a/T016b — Navbar/design uniformity between Home/Support (2281) and Tickets (5422) ONLY
(scope was briefly extended to Forms/IT Forms, then explicitly narrowed back per user request —
those two pages are fully untouched). Includes fixing F002, a real bug where an earlier pass
hardcoded a JS snapshot of live shift-assignment data instead of relocating the actual live
element. User visually confirmed the final result in a real browser, including confirming a live
edit (one roster entry's shift, changed via "Assisting Ticket #") correctly appeared. Also SEC001 (removed
a publicly-exposed local DB dump, unrelated to the navbar work). See `progress/progress.md`.
Completed: 2026-07-15

## Next Task
T015 — Deploy the confirmed layout changes to the live cPanel site (`support.stratastaffglobal.com`)
Depends on: T016b (done, user-confirmed). This is now the next real action — no remaining
verification blocker.

## Active Blockers
None. `wp-admin/*` is broken locally (pre-existing bug, see `db_backup/backup_policy.md`) but
does not block anything — all real work happens via the front-end `/tickets/` pages.

## Session Notes — read this before doing anything else

**What the local rebuild actually is, end to end:**
The whole ticketing UI (front-end `/tickets/` pages, which is what staff actually use — NOT
wp-admin) was redesigned in place, CSS/JS/PHP only, zero DB schema or backend logic changes:
- `wp-content/plugins/supportcandy/framework/style.css` — heavily edited (ticket list, forms,
  navbar v1, sidebar cards, container shadows, global font stack). Loaded on EVERY front-end page
  site-wide, not just Tickets — confirmed via the `wpsc-gs-page-settings` option's `load-scripts`
  value = `all-pages`. This is why T016's global navbar could reuse `.wpsc-header`/`.wpsc-tickets-nav`
  classes directly instead of writing new CSS from scratch.
- `wp-content/plugins/supportcandy/includes/frontend/class-wpsc-shortcode-one.php` — one-line
  change, added `style="border: none !important;"` to strip the boxed-card border
- `wp-content/plugins/Multi-Input-Text-Display-Plugi/text-display-plugin.php` — wrapped the
  "Assisting Ticket #" shift-assignment form in a collapsible `<details>`, restyled inputs
- 4 new isolated mu-plugin files (load automatically, delete file = instant full revert, no DB
  involved): `wpsc-theme-toggle.php` (light/dark toggle — now lives INSIDE the navbar again, next
  to Logout, colored black; an earlier session had floated it fixed outside the navbar, that's been
  superseded), `wpsc-modern-table.php` (stat tiles + status dots + avatar circles, MutationObserver-
  driven, purely additive DOM decoration), `wpsc-navbar-redesign.php` (full-bleed shell, white
  navbar, sidebar panels relocated into navbar dropdowns; click-outside logic fixed to check
  `e.target.closest('.wpsc-nav-dropdown')` so clicking inside a panel — e.g. "Assisting Ticket #"'s
  Update button — no longer closes it before the click registers), `wpsc-global-navbar.php` (T016 —
  scope is Home + Tickets ONLY, not Forms/IT Forms. Renders the same navbar look on Home, which
  doesn't contain the SupportCandy shortcode's logo widget; the logo is inserted inside `.wpsc-header`
  via JS on BOTH pages identically now (Tickets' old separate logo widget is hidden in favor of
  this). Relocates — never clones — the real live "Shift Assignments" widget (`[text_display]`
  shortcode) from its original spot to directly below the navbar, restyled into a card grid, shown
  only on the ticket-list section. See F002 in `fixes/fix_log.md` for why relocating the live node
  (not hardcoding a copy) is the only correct way to touch that widget.)

**Site structure discovered during T016 (relevant for any future page-level work):**
- 4 published pages total: Home/Support (ID 2281, slug `support`, set as `page_on_front`), Tickets
  (5422), Forms (2102), IT Forms (2156) — only the first two are in scope for navbar uniformity;
  Forms/IT Forms were explicitly ruled out by the user and `wpsc-global-navbar.php` never touches them.
- Home, Forms, Tickets use the `elementor_canvas` template (no theme header/footer at all — fully
  custom per-page). IT Forms uniquely uses `page-template-default`. Not relevant to current scope,
  noted here only because it was investigated.
- SupportCandy's own navbar (`.wpsc-header`) is session-gated PHP inside the shortcode output
  (`class-wpsc-shortcode-one.php`) — only renders for a logged-in agent/customer. Surprising find:
  the Home page ALSO embeds this same shortcode (a hidden `#wpsc-container` further down its
  Elementor content) — for a logged-in user, Home renders the exact same live ticket app as the
  Tickets page, just with extra Elementor content above/around it. Anonymous/curl requests get a
  login form instead (no `.wpsc-header` in the raw HTML) — expected, not a bug, when
  structurally inspecting via curl without a real browser/login session.
- SupportCandy tracks the active in-app "section" (ticket-list, agent-profile, my-profile,
  new-ticket, dashboard) live in `window.supportcandy.current_section` and the `wpsc-section` URL
  param, updated via the History API on every client-side tab switch (no page reload) — useful for
  any future section-conditional UI.
- The Home page's "Shift Assignments" text is NOT static — it's the live `[text_display]` shortcode
  from `Multi-Input-Text-Display-Plugi/text-display-plugin.php`, reading `tdp_display_text_N` /
  `tdp_shift_schedule_N` from `wp_options`. `[text_input_form]` (the "Assisting Ticket #" panel,
  already relocated into the navbar in an earlier session) is the real editor for those same
  options. Never hardcode a copy of this data — always relocate/restyle the live element (see F002).
- Elementor page 5422 ("Tickets") `_elementor_data` JSON was hand-edited several times (logo
  resized/repositioned, heading widget removed, duplicate canned-reply widgets removed, header
  section width/padding) — **Elementor ignores `wp_posts.post_content`**, always edit
  `_elementor_data` directly, see the gotcha writeup in `db_backup/backup_policy.md`
- SupportCandy's own "Appearance" settings (`wpsc-ap-ticket-list` option) were edited to fix
  zebra-striping/hover colors that were silently overriding our CSS

**Local environment (still needed for any further local testing):**
- URL: `http://support-local.test` (NOT `support.stratastaffglobal.com` — that hosts entry was
  removed on purpose; do not re-add it, it previously hijacked the live domain)
- Local admin login: username `local_admin`, password is NOT in git — check with the user or a
  password manager (deliberately redacted from `_brain` docs since the repo is public)
- DB: `stratast_support`, local via XAMPP/Laragon `mysql -u root` (no password), live via cPanel
  phpMyAdmin — no auto-sync, ever

**Git/deploy state:**
- Local repo tracks ONLY the layout files above + `_brain/` (NOT WordPress core, uploads, or the
  122MB `_brain/db_backup/stratast_support.sql` DB dump — that's gitignored, stays local-only)
- Repo is PUBLIC — never commit real employee names, passwords, or other PII into `_brain/` docs
  or plugin files going forward; grep for names before every push if a file touches
  Multi-Input-Text-Display-Plugi/shift-assignment data specifically (see F002)
- T015 (live deploy) is uploading 7 files to matching paths on cPanel: the 3 previously-deployed
  files (`framework/style.css`, `class-wpsc-shortcode-one.php`, `text-display-plugin.php`) + 4
  mu-plugins (`wpsc-theme-toggle.php`, `wpsc-modern-table.php`, `wpsc-navbar-redesign.php`,
  `wpsc-global-navbar.php`). No DB import, no downtime risk, fully reversible by deleting the 4
  mu-plugin files or restoring the other 3 from `_brain/ui_backups/`.
- Before deploying: verify live page IDs for "Support"/Home and "Tickets" match local (`2281` and
  `5422`) — `wpsc-global-navbar.php` hardcodes these. Local DB was seeded from a live export so
  they very likely match, but this wasn't independently re-verified against the live site this
  session. If they don't match, just update the `is_page()` array and the `LINKS`/`$current_id`
  logic in that one file — not a rebuild.

---

Last updated: 2026-07-15
