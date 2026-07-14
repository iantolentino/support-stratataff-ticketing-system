# ENVIRONMENTS

---

## Environment Summary

| Environment | Purpose                    | Access        |
|-------------|----------------------------|---------------|
| Local       | Layout editing/testing before deploy | Developer only, via `support-local.test` |
| Production  | Live ticketing site (real users) | Public, via `support.stratastaffglobal.com` |

No staging environment exists for this project — local IS the pre-deploy check, then straight
to production (manual file/DB copy via cPanel).

---

## URLs — DO NOT CONFUSE THESE

| Environment | URL | Resolves to | Notes |
|-------------|-----|-------------|-------|
| Local  | `http://support-local.test` | `127.0.0.1` (hosts file entry) | Apache vhost in `C:\xampp\apache\conf\extra\httpd-vhosts.conf` → DocumentRoot `C:/xampp/htdocs/support.stratastaffglobal.com` |
| Live   | `http://support.stratastaffglobal.com` | `194.39.148.179` (real DNS, cPanel-hosted) | Do NOT add a hosts file override for this hostname — doing so once accidentally hijacked it to point at local, breaking live access until removed |

Local intentionally uses a DIFFERENT hostname than live (`support-local.test`, not
`support.stratastaffglobal.com`) specifically so the browser address bar can never be mistaken
for the real production site.

## Local Setup Reference (already done, 2026-07-14)
1. Hosts file (`C:\Windows\System32\drivers\etc\hosts`) — needs admin rights to edit:
   `127.0.0.1 support-local.test`
2. Apache vhost block in `httpd-vhosts.conf`: `ServerName support-local.test`, DocumentRoot
   pointing at this project folder
3. Local-only `wp_options` → `siteurl`/`home` set to `http://support-local.test` (live DB
   untouched, still says the real domain)
4. Local MySQL user `stratast_support`@`localhost` created to match `wp-config.php`'s DB
   credentials (so wp-config didn't need editing for DB access)
5. Local WP admin login: username `local_admin` (password stored outside git — see
   `db_backup/backup_policy.md` → Local Dev Access)
6. `wp-config.php` — added `@ini_set('display_errors','0')` to hide pre-existing plugin PHP
   warnings from rendering on-page during layout QA (does not change any logic; harmless/good
   practice if this file is ever copied to live too)

## Environment Rules
- Never use the LIVE hostname (`support.stratastaffglobal.com`) in the local hosts file — it
  breaks real access to production for this machine
- Never copy `wp-config.php`'s DB credentials section from local to live or vice versa without
  checking — they currently match (same user/pass), but don't assume that stays true
- Layout files (CSS/templates) are the only things intended to move from local → live; always
  back up first via `_brain/tools/ui-backup.ps1` (see `prompts/continue_prompt.md`)
