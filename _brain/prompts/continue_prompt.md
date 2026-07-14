# CONTINUE PROMPT

Paste this at the start of a session to resume work on an existing project.

---

Read the following files in this exact order. Do not read anything else.

1. `_brain/claude.md` (or `_brain/aibrain.md`)
2. `_brain/progress/progress.md`
3. `_brain/summaries/current_state.md`

You are now in EXECUTION_MODE.

Rules:
- Select ONE incomplete task from progress.md
- Validate that all its dependencies are COMPLETE
- If a dependency is not complete, select that dependency instead
- If the task is a bug fix (`B###`), read `_brain/fixes/fix_log.md` first
- Execute the selected task to full completion
- Update `progress/progress.md` and `summaries/current_state.md` (and `fixes/fix_log.md` if this
  was a bug fix)
- Stop

Do not plan. Do not re-explain the system. Do not propose future tasks.
Execute the next task and stop.

---

## PROJECT-SPECIFIC RULE — LAYOUT-ONLY REVERT SAFETY (always active this project)

This project's mission is a full layout/visual overhaul of the SupportCandy WordPress ticketing
site (`governance/scope.md`) — **no new functionality, no logic changes**, ever. Local DB name:
`stratast_support` (XAMPP/Laragon, `mysql -u root stratast_support`); live DB is the same
name/prefix, edited manually via cPanel phpMyAdmin (no auto-sync).

Before touching ANY theme/plugin CSS, template, or markup file — local or live:

```powershell
powershell -File _brain\tools\ui-backup.ps1 -Path "<relative path to file>"
```

If a change breaks the layout (local or after deploying to cPanel), revert immediately:

```powershell
powershell -File _brain\tools\ui-restore.ps1 -Path "<relative path to file>"
```

(Add `-BackupFile "<timestamp>__<file>"` to restore an older version instead of the latest.)

Never edit a layout file without a backup existing first. Never deploy a local change to the
live cPanel site until it has been visually confirmed working locally.
