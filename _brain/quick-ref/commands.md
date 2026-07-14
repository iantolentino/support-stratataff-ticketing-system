# COMMANDS

> Every command actually used in this project. Fill in during SYSTEM_GENERATION, update as the
> stack evolves. The AI should use these exact commands rather than guessing framework defaults.

---

| Purpose        | Command | Notes |
|----------------|---------|-------|
| Install deps   | n/a — stock WordPress + plugin zips, no package manager | |
| Run dev server | XAMPP Apache/MySQL (already running), site at local `support.stratastaffglobal.com` vhost | |
| Run tests      | n/a — no automated test suite | manual click-through testing only |
| Lint / format  | n/a | |
| Build          | n/a — no build step, edit PHP/CSS directly | |
| DB access (local) | `mysql -u root stratast_support -e "<SQL>"` | root has no password locally |
| DB access (live) | cPanel → phpMyAdmin → select DB (same name/prefix as local) | user applies SQL manually, no auto-sync |
| Back up a layout file before editing | `powershell -File _brain\tools\ui-backup.ps1 -Path "<relative path>"` | run before every CSS/template edit, local AND live |
| Revert a layout file if it breaks | `powershell -File _brain\tools\ui-restore.ps1 -Path "<relative path>"` | restores latest backup; add `-BackupFile` for a specific one |
| Deploy         | Manual: edit locally → verify in browser → re-apply the same file edits (or re-upload via cPanel File Manager/FTP) on the live site | no CI/CD |
