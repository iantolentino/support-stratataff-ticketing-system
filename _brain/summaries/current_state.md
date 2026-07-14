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
T014 — Git repo initialized, employee-name PII redacted from docs (repo is public), pushed to
`https://github.com/iantolentino/support-stratataff-ticketing-system` (branch `main`)
Completed: 2026-07-14

## Next Task
T015 — Deploy the confirmed layout changes to the live cPanel site (`support.stratastaffglobal.com`)
Depends on: T014 (done). User said "tomorrow" — this is the next real action, not yet scheduled
to a specific date/time beyond that.

## Active Blockers
None. `wp-admin/*` is broken locally (pre-existing bug, see `db_backup/backup_policy.md`) but
does not block anything — all real work happens via the front-end `/tickets/` pages.

## Session Notes — read this before doing anything else

**What the local rebuild actually is, end to end:**
The whole ticketing UI (front-end `/tickets/` pages, which is what staff actually use — NOT
wp-admin) was redesigned in place, CSS/JS/PHP only, zero DB schema or backend logic changes:
- `wp-content/plugins/supportcandy/framework/style.css` — heavily edited (ticket list, forms,
  navbar v1, sidebar cards, container shadows, global font stack)
- `wp-content/plugins/supportcandy/includes/frontend/class-wpsc-shortcode-one.php` — one-line
  change, added `style="border: none !important;"` to strip the boxed-card border
- `wp-content/plugins/Multi-Input-Text-Display-Plugi/text-display-plugin.php` — wrapped the
  "Assisting Ticket #" shift-assignment form in a collapsible `<details>`, restyled inputs
- 3 new isolated mu-plugin files (load automatically, delete file = instant full revert, no DB
  involved): `wpsc-theme-toggle.php` (light/dark toggle, floats above navbar), `wpsc-modern-table.php`
  (stat tiles + status dots + avatar circles, MutationObserver-driven, purely additive DOM
  decoration), `wpsc-navbar-redesign.php` (full-bleed shell, white navbar, sidebar panels
  relocated into navbar dropdowns)
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

Last updated: 2026-07-14
