# DATABASE BACKUP POLICY

---

## Backup Strategy
Manual logical dump (mysqldump / phpMyAdmin "Export") — user pulls a full `.sql` export from the
live cPanel-hosted DB periodically and drops it in the local project folder.

## Schedule
| Environment | Frequency | Retention |
|-------------|-----------|-----------|
| Production (cPanel) | Manual, before/after risky changes | User keeps latest dump only (informal) |
| Local (XAMPP)        | n/a — local DB is live working copy, not backed up itself | — |

## Storage Location
Known dumps:
- `support/stratast_support.sql` — dated 2026-07-12/13, predates the "Immediate Superior"
  dropdown edits (T002/T003). Pre-layout-overhaul restore point only, NOT current state.
- `_brain/db_backup/stratast_support.sql` — dated 2026-07-14 09:52, CURRENT/live dump. Already
  imported into local XAMPP/phpMyAdmin `stratast_support` DB — verified to include T002 (Marc
  Dhaniel Batac added) and T003 (Georgia Galang, Irish Nicolette Sicat, Rosallie Chua hidden via
  `custom_field = -39`). This is the DB to test layout/DB changes against going forward.

## Restore Procedure
1. In local phpMyAdmin: select DB `stratast_support` → Import → choose the `.sql` file → Go
   (or via CLI: `mysql -u root stratast_support < support/stratast_support.sql`)
2. Note: importing an OLDER dump overwrites newer data (e.g. would undo T002/T003 custom-field
   edits) — confirm with user before importing over the current local DB
3. Verify: re-run `SELECT * FROM 92CpH3vC_psmsc_options WHERE custom_field = 39;` to confirm
   expected state post-restore

## Local Dev Access
- Local vhost: `http://support-local.test` (deliberately distinct hostname from live, so the
  address bar is never confused with production — see `decisions/decision_log.md`). Requires
  hosts entry `127.0.0.1 support-local.test` and the vhost block in
  `C:\xampp\apache\conf\extra\httpd-vhosts.conf` (DocumentRoot → this project's htdocs folder).
- Local-only `wp_options` → `siteurl`/`home` overridden to `http://support-local.test` (does NOT
  affect live DB/site — cPanel DB is untouched).
- Local-only WP admin account created for testing: user `local_admin` (password stored outside
  git — ask the user or check local password manager), WP user ID 620, role `administrator`.
  Local DB only — do not attempt this login on the live site.

## Local Mock Data
- 6 mock tickets inserted into local DB only (`92CpH3vC_psmsc_tickets`, IDs 7336–7341), subject
  prefixed `[MOCK]` for easy identification/cleanup. Used to populate the ticket list for layout
  QA. Delete anytime with:
  `DELETE FROM 92CpH3vC_psmsc_tickets WHERE subject LIKE '[MOCK]%';`
- Local-only MySQL user `stratast_support`@`localhost` created (matches `wp-config.php`
  credentials) so `wp-config.php` didn't need editing for DB access — only its `display_errors`
  suppression was added (see `decisions/decision_log.md`).

## Elementor Pages — Important Gotcha
Several front-end pages (e.g. "Tickets", page ID 5422, URL `/tickets/`) are built with Elementor,
NOT classic content. **`wp_posts.post_content` is ignored at render time** — the real layout
lives in `wp_postmeta` meta_key `_elementor_data` (a JSON tree of `elType`/`widgetType` nodes).
Editing `post_content` on an Elementor page is a silent no-op. To change layout on such a page:
1. Find the target text via a small PHP script that `json_decode`s `_elementor_data` and walks
   the tree looking for the string (see pattern in `quick-ref/snippets.md`)
2. Edit only that node's `settings.editor` (text-editor widgets) or relevant setting key
3. Re-encode with `json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)` and save
4. Delete `_elementor_element_cache` and `_elementor_css` postmeta rows for that post ID to force
   Elementor to regenerate its render cache — otherwise stale HTML/CSS may still be served
Always back up the full `_elementor_data` JSON value to `_brain/ui_backups/db_content/` first
(same pattern as any DB content backup) — hand-editing the tree structure incorrectly can corrupt
the page in the Elementor editor.

## Known Pre-Existing Bug: wp-admin is broken locally (not caused by our layout work)
Discovered 2026-07-14: every `wp-admin/*` page (even the plain WP dashboard `index.php`) returns
a near-empty response locally. Root cause: Elementor's usage-tracking module
(`Elementor\Core\Common\Modules\EventTracker\DB->create_table`) tries to `CREATE TABLE
92CpH3vC_e_events` on every admin page load without `IF NOT EXISTS`, errors because the table
already exists, and execution appears to halt shortly after with no fatal logged. Confirmed via
`WP_DEBUG_DISPLAY` temporarily enabled (immediately reverted after diagnosis).
**Workaround / why this doesn't block layout work:** the actual staff workflow uses the
**front-end** `/tickets/?wpsc-section=...` pages (confirmed by user), which work fine and do NOT
go through this broken admin code path. All layout QA (ticket list, new ticket form) should be
done via `/tickets/` front-end URLs, not `wp-admin/admin.php?page=wpsc-tickets`.
This bug was NOT introduced by any of our CSS/PHP edits — it reproduces on a clean `index.php`
dashboard request with zero of our changes involved. Flagged for the user's awareness; fixing it
(likely disabling Elementor's usage tracking, or an `IF NOT EXISTS` fix) is outside "layout only"
scope unless the user asks for it separately.

## Restore Test Cadence
Not yet tested end-to-end — first real restore should be verified manually against the checklist
above.

## Rules
- Never overwrite the local working DB with an older dump without explicit user confirmation
- Treat DB layout/schema as read from `memory/system_architecture.md` — dumps are data snapshots,
  not the source of truth for structure
- This project has no automated local↔live DB sync (see `memory/app_context.md` → Hard
  Constraints) — dumps are the only bridge between the two
