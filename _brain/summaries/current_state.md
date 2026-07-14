# CURRENT STATE

> The AI reads this file at the start of every EXECUTION_MODE session.
> Update this file at the end of every session — before stopping.

---

## System State
EXECUTION_MODE

## Current Phase
Maintenance — layout-only improvement pass (no new functionality; see `governance/scope.md`)

## Last Completed Task
T005 — Built UI backup/revert tooling (`_brain/tools/ui-backup.ps1`, `ui-restore.ps1`)
Completed: 2026-07-14

## Next Task
T006 — Improve layout of `wp-admin/admin.php?page=wpsc-tickets&section=new-ticket` (and any other
pages the user names). No new fields/functionality — visual/CSS/markup only.
Depends on: none — waiting on user to point at the specific page/section to start with

## Active Blockers
None

## Session Notes
- Site: SupportCandy WordPress ticketing plugin (folder `supportcandy`, DB-internal table
  prefix `psmsc_`, actual DB tables are plain `92CpH3vC_psmsc_*` — ignore lookalike tables under
  `92CpH3vC_2_/3_/4_` prefixes, those are unrelated leftovers).
- DB: `stratast_support`, local via XAMPP/Laragon `mysql -u root`, live via cPanel phpMyAdmin.
  No auto-sync — every DB or file change must be manually re-applied by the user on cPanel.
- Workflow going forward: test every layout change locally first (use `ui-backup.ps1` before
  touching any theme/plugin CSS or template file), confirm in browser, THEN replicate the same
  change on the live cPanel site. If a live change breaks the layout, use `ui-restore.ps1` (or
  the backed-up file copy) to revert immediately.
- "Immediate Superior" custom field (id 39) dropdown options live in table `psmsc_options`;
  full reference for how to view/add/hide entries is in `quick-ref/commands.md` and
  `quick-ref/snippets.md`.

---

Last updated: 2026-07-14
