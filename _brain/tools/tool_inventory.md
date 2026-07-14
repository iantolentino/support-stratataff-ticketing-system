# TOOL INVENTORY

> Every CLI tool, service, or external dependency this project relies on outside the core stack
> (which lives in `skills/skills.md`). Covers linters, formatters, generators, monitoring, and
> anything invoked from a terminal or CI pipeline.

---

| Tool | Purpose | Invocation | Notes |
|------|---------|------------|-------|
| `_brain/tools/ui-backup.ps1` | Back up a theme/plugin CSS or template file before editing layout | `powershell -File _brain\tools\ui-backup.ps1 -Path "wp-content\themes\<theme>\style.css"` | Run before every layout edit, local and live. Backups saved under `_brain\ui_backups\` |
| `_brain/tools/ui-restore.ps1` | Revert a layout file to its last backup (or a named one) if a change fails | `powershell -File _brain\tools\ui-restore.ps1 -Path "wp-content\themes\<theme>\style.css"` | Add `-BackupFile "<timestamp>__<file>"` to restore a specific version instead of the latest |
| `mysql` (Laragon MySQL client) | Read/edit SupportCandy custom-field data directly (e.g. dropdown options) | `mysql -u root stratast_support -e "..."` | Local only — no password on local root user. Live DB is edited via cPanel phpMyAdmin using the same SQL |
| `npx playwright` (Chromium, already installed via `npx playwright install chromium`) | Automated login + screenshot verification of layout changes, since wp-admin/front-end requires an authenticated session | Write a small script (see `_brain/staging` pattern) that logs in via `#user_login`/`#user_pass`/`#wp-submit`, navigates, `page.screenshot({fullPage:true})` | Use the local admin account (`db_backup/backup_policy.md` → Local Dev Access). Ticket rows on `/tickets/?wpsc-section=ticket-list` load via AJAX/DataTables — `waitForLoadState('networkidle')` + a short `waitForTimeout` before screenshotting, a raw `curl` won't show them |
| `php.exe` (XAMPP CLI, `C:\xampp\php\php.exe`) | Run one-off PHP scripts against the local DB (via `mysqli`) — used for editing Elementor `_elementor_data` JSON safely, generating WP password hashes (`wp-includes/class-phpass.php`) | `/c/xampp/php/php.exe script.php` | Preferred over raw SQL string-escaping for anything containing HTML/JSON content |
| `wp-content/mu-plugins/wpsc-theme-toggle.php` | Site-wide light/dark theme toggle for the ticketing UI | Loads automatically (mu-plugin, no activation). Delete the file to fully revert — no DB involved | Injects a toggle button into `.wpsc-header` via `wp_footer`/`admin_footer`; persists choice in browser `localStorage` (`wpsc-theme-preference`), defaults to OS `prefers-color-scheme` |

---

## Rule
Add a row here the first time a new tool is introduced to the project (during EXECUTION_MODE or
SYSTEM_GENERATION). This prevents re-discovering "what generates the API client" or "what lints
this repo" in a future session.
