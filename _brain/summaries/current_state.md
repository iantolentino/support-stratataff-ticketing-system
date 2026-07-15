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
T016 — Site-wide navbar/design uniformity across all 4 front-end pages (Home/Support, Tickets,
Forms, IT Forms), plus SEC001 (removed a publicly-exposed local DB dump). See `progress/progress.md`.
Completed: 2026-07-15

## Next Task
T016-verify — Visually confirm the new global navbar (and restyled Home-page shift widget) in a
real logged-in browser session; this session had no browser/screenshot tool available, so the work
was verified structurally (HTML/DOM/PHP lint) but not visually.
Depends on: T016 (done, needs visual sign-off before T015 live deploy folds it in).

T015 — Deploy the confirmed layout changes to the live cPanel site (`support.stratastaffglobal.com`)
Depends on: T016-verify. User said "tomorrow" (as of 2026-07-14) — still the next real action once
T016 is visually confirmed.

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
  involved): `wpsc-theme-toggle.php` (light/dark toggle, floats above navbar), `wpsc-modern-table.php`
  (stat tiles + status dots + avatar circles, MutationObserver-driven, purely additive DOM
  decoration), `wpsc-navbar-redesign.php` (full-bleed shell, white navbar, sidebar panels
  relocated into navbar dropdowns), `wpsc-global-navbar.php` (T016 — renders the same navbar look
  on Home/Forms/IT Forms, which don't contain the SupportCandy shortcode so never had `.wpsc-header`
  at all; also restyles the Home page's legacy raw shift-assignment widget and hides its now-redundant
  duplicate logo/heading)

**Site structure discovered during T016 (relevant for any future page-level work):**
- 4 published pages total: Home/Support (ID 2281, slug `support`, set as `page_on_front`), Tickets
  (5422), Forms (2102), IT Forms (2156)
- Home, Forms, Tickets use the `elementor_canvas` template (no theme header/footer at all — fully
  custom per-page). IT Forms uniquely uses `page-template-default` (renders the theme's generic
  block-based nav/footer) — this was the biggest source of visual inconsistency, now hidden via CSS
  in favor of the shared navbar.
- SupportCandy's own navbar (`.wpsc-header`) is entirely session-gated PHP inside the shortcode
  output (`class-wpsc-shortcode-one.php`) — only renders for a logged-in agent/customer, and only
  on the Tickets page. Anonymous/curl requests to the Tickets page get a login form instead, so any
  future structural inspection of that page via curl (no browser/auth available) will NOT show the
  real navbar — this is expected, not a bug.
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
  going forward; redact before every push
- T015 (live deploy) is just uploading those 6 files to matching paths on cPanel — no DB import,
  no downtime risk, fully reversible by deleting the 3 mu-plugin files or restoring the other 3
  from `_brain/ui_backups/`

---

Last updated: 2026-07-15
