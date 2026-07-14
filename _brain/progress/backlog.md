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
| T006 | Restyle ticket list / dashboard table (modern styling pass)         | HIGH     | T005       | COMPLETE    |
| T006b | Set up local dev environment (vhost, local admin account, mock data, screenshot tooling) | HIGH | T005 | COMPLETE |
| T006c | Restyle "Assisting Ticket # / Canned Reply Templates" sidebar panel (Elementor)          | MEDIUM | T006b | COMPLETE |
| T007 | Restyle New Ticket form layout                                     | HIGH     | T006       | COMPLETE    |
| T007b | Restyle top navbar v1 (pill hover/active, shadow, spacing) — superseded by T013 | MEDIUM | T007 | COMPLETE |
| T008 | Restyle single ticket / thread view sidebar widgets                 | MEDIUM   | T006       | COMPLETE    |
| T009 | Global "shadcn-style" plain-CSS design pass (fonts, ticket-list appearance-settings fix, container shadow/radius, boxed-look fix) | MEDIUM | T006, T007, T008 | COMPLETE |
| T010 | Kanban-vs-modern-table comparison artifact — user picked **Modern Table** + light/dark toggle | HIGH | T009 | COMPLETE |
| T011 | Site-wide light/dark theme toggle (`mu-plugins/wpsc-theme-toggle.php`) | HIGH | T010 | COMPLETE |
| T012 | Modern Table redesign: stat tiles, status dots, avatar circles (`mu-plugins/wpsc-modern-table.php`) | HIGH | T010 | COMPLETE |
| T013 | Full app-shell + navbar redesign: full-bleed layout, white navbar, sidebar panels relocated into navbar dropdowns (`mu-plugins/wpsc-navbar-redesign.php`) | HIGH | T012 | COMPLETE |
| T013b | Theme-toggle visibility fix + logo/navbar gap tightened                | MEDIUM   | T011, T013 | COMPLETE |
| T013c | Theme-toggle repositioned to float above navbar (outside nav row)      | LOW      | T013b      | COMPLETE |
| T014 | Git repo initialized, employee-name PII redacted from public-repo docs, pushed to `github.com/iantolentino/support-stratataff-ticketing-system` (`main` branch) | HIGH | T013c | COMPLETE |
| T015 | Deploy confirmed layout changes to live cPanel site (`support.stratastaffglobal.com`) | HIGH | T014 | PENDING — user said "tomorrow" |

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
