# SCOPE DEFINITION

> Written and locked during CONFIRMATION_LOCK.
> Scope changes must go through the change control process in `governance/rules.md`.

---

## In Scope — MVP
- [x] Full layout/visual overhaul of the WordPress ticketing site (SupportCandy admin + front-end
  ticket pages, all screens) — visual/CSS/markup only, not just the new-ticket page
- [x] Extended 2026-07-15: uniform design across ALL front-end pages of the site, not just Tickets
  — Home/Support (2281), Forms (2102), IT Forms (2156), Tickets (5422). See
  `decisions/decision_log.md` [SCOPE] entry 2026-07-15.
- [x] Test every layout change locally (XAMPP, DB `stratast_support`) first, then deploy to the
  live cPanel-hosted site
- [x] A revert mechanism: before any layout file is changed, back it up so a failed change can be
  restored instantly, both locally and on the live site (`_brain/tools/ui-backup.ps1` /
  `ui-restore.ps1` — wired into `prompts/continue_prompt.md` as a standing rule)

## Explicitly Rejected (locked 2026-07-14)
- New functionality of any kind (new fields, new plugins, new workflows, new automations)
- Changing existing behavior/logic — layout/visual changes must not alter how the ticketing
  system works
- Any change that isn't reversible via the backup-and-revert process below

---

## Scope Change Protocol
If the user requests a scope change during EXECUTION_MODE:

1. Stop the current task
2. Log the change request in `decisions/decision_log.md`
3. Update `progress/backlog.md` with new or removed tasks
4. Update `timelines/actual_timeline.md`
5. Resume from the next appropriate task

No scope change takes effect until it is written to this file and backlog.md.
