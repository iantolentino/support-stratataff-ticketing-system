# BACKLOG

> Tasks are ordered by dependency. Do not execute a task until all dependencies are COMPLETE.
> This file is written during SYSTEM_GENERATION and updated as scope changes.

---

## Phase 1 — MVP (layout-only overhaul, see `governance/scope.md`)

| ID   | Task                                                              | Priority | Depends On | Status      |
|------|--------------------------------------------------------------------|----------|------------|-------------|
| T001 | Locate "Immediate Superior" dropdown source                       | HIGH     | none       | COMPLETE    |
| T002 | Add a new employee to Immediate Superior dropdown                  | HIGH     | T001       | COMPLETE    |
| T003 | Hide 3 Immediate Superior options (reversible)                     | MEDIUM   | T001       | COMPLETE    |
| T004 | Build `_brain` memory system into real project state                | HIGH     | none       | COMPLETE    |
| T005 | Build UI backup/revert tooling (ui-backup.ps1 / ui-restore.ps1)     | HIGH     | none       | COMPLETE    |
| T006 | Restyle ticket list / dashboard table (modern styling pass)         | HIGH     | T005       | COMPLETE — verified via automated Playwright screenshot |
| T006b | Set up local dev environment (vhost, local admin account, mock data, screenshot tooling) | HIGH | T005 | COMPLETE |
| T006c | Restyle "Assisting Ticket # / Canned Reply Templates" sidebar panel on `/tickets/` page (Elementor) | MEDIUM | T006b | COMPLETE |
| T007 | Restyle New Ticket form layout                                     | HIGH     | T006       | COMPLETE — verified via front-end screenshot |
| T007b | Restyle top navbar (pill hover/active, shadow, spacing)            | MEDIUM   | T007       | COMPLETE     |
| T008 | Restyle single ticket / thread view                                 | MEDIUM   | T006       | PENDING     |
| T009 | Global theme/CSS consistency pass (colors, fonts, spacing)          | MEDIUM   | T006, T007, T008 | PENDING |
| T010 | Deploy confirmed layout changes to live cPanel site                 | HIGH     | T006 (per-item, repeat per completed page) | PENDING |

## Phase 2 — Scale Prep

| ID   | Task                  | Priority | Depends On | Status  |
|------|-----------------------|----------|------------|---------|

## Phase 3 — Scaling

| ID   | Task                  | Priority | Depends On | Status  |
|------|-----------------------|----------|------------|---------|

---

## Task Status Key
| Status      | Meaning                            |
|-------------|------------------------------------|
| PENDING     | Not started                        |
| IN_PROGRESS | Currently executing                |
| COMPLETE    | Done and usable                    |
| BLOCKED     | Waiting on dependency              |
| REJECTED    | Will not implement — see decisions |

---

## Rejected Tasks
See: `decisions/rejected_options.md`
