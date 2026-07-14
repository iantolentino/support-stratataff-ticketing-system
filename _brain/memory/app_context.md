# APP CONTEXT

---

## Project Name
Strata Staff Global — Support Ticketing System (support.stratastaffglobal.com)

## Project Type
WordPress site — internal help desk / ticketing tool

## Domain
Internal IT/HR support ticketing for Strata Staff Global employees (BPO/staffing company)

## Target Users
Internal staff submitting IT/HR/account requests; agents/admins triaging and resolving tickets
via wp-admin.

## Core Workflow
1. Employee opens `wp-admin/admin.php?page=wpsc-tickets&section=new-ticket`
2. Fills ticket form: Category, Subject, Description, Priority, custom fields (incl.
   "Immediate Superior" dropdown), attachments
3. Agent gets assigned, works ticket via SupportCandy threads
4. Ticket resolved/closed; reporting via wpsc-reports plugin

## Key Features (MVP)
- [x] Ticket submission (SupportCandy core)
- [x] Custom fields incl. "Immediate Superior" single-select dropdown
- [x] Email piping / notifications
- [x] Ticket export/print (wpsc-export-ticket, wpsc-print-ticket)
- [x] Reporting (wpsc-reports)

## Tech Stack
| Layer       | Technology |
|-------------|------------|
| Language    | PHP (WordPress) |
| Framework   | WordPress core + SupportCandy plugin (folder `supportcandy`, DB-internal prefix `psmsc_`) |
| Database    | MySQL/MariaDB, DB name `stratast_support`, table prefix `92CpH3vC_` |
| Cache       | none observed |
| Auth        | WordPress users/roles |
| Hosting     | Local: XAMPP (`c:\xampp\htdocs\support.stratastaffglobal.com`). Live: cPanel — user manually mirrors DB edits from local to cPanel phpMyAdmin. |

## Expected Scale
Small — single internal company, tens to low hundreds of employees/tickets.

## Hard Constraints
- Local (XAMPP) and live (cPanel) are **separate databases** — no automated sync. Every DB
  change made locally must be manually re-applied by the user via cPanel phpMyAdmin. Always
  hand over the raw SQL; never assume it propagates on its own.
- SupportCandy single-select custom-field "options" rows only store `name` — no email or other
  metadata column exists on `psmsc_options`.
- `psmsc_` tables under prefix `92CpH3vC_` (no site-id suffix) are the LIVE ones this SupportCandy
  install actually reads. Tables like `92CpH3vC_2_...`, `92CpH3vC_3_zbs_...`,
  `92CpH3vC_4_psmsc_...` are unrelated/leftover data (from other plugins or a past migration) —
  do not confuse them with the active `psmsc_*` tables.
- **cPanel shared hosting only** — the live site is standard shared hosting (Apache/LiteSpeed +
  PHP + MySQL via cPanel), NOT a VPS/container with shell/Node/build-tool access. Every layout
  change must be deployable by just uploading files via cPanel File Manager/FTP and/or pasting
  SQL into phpMyAdmin. Concretely this means:
  - Plain PHP, HTML, CSS, and vanilla/jQuery JS only — no Node.js, no build step (webpack/vite),
    no React/Vue/shadcn, no package.json dependency the live server would need to install
  - No background services/daemons/workers (rules out things like local memory/indexing
    services that need a running process — local dev tooling built for THIS session, e.g. the
    Playwright screenshot scripts, stays local-only for testing and is never deployed)
  - Any JS used must run entirely client-side from a static file, includable via a plain
    `<script>` tag or enqueued the normal WordPress way
  - Elementor page edits (see `db_backup/backup_policy.md` → Elementor gotcha) are done by
    editing `_elementor_data` JSON directly or via the Elementor editor UI — both are
    cPanel-compatible since they're just DB/file content, no extra runtime needed

## Current Phase
Maintenance / ongoing admin support (not active MVP build)
