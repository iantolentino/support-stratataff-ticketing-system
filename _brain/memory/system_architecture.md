# SYSTEM ARCHITECTURE

> Written during SYSTEM_GENERATION. Updated only when architecture decisions change.

---

## Architecture Pattern
Monolith — stock WordPress + plugin ecosystem (no custom app framework layered on top)

## Layer Map
| Layer      | Technology | Responsibility                    |
|------------|------------|-----------------------------------|
| Frontend   | WordPress admin UI (wp-admin) + SupportCandy's own JS/CSS | Ticket form, agent dashboard |
| Backend    | PHP / WordPress plugin API | SupportCandy (`wp-content/plugins/supportcandy`) handles tickets, custom fields, agents |
| Database   | MySQL/MariaDB, DB `stratast_support`, prefix `92CpH3vC_` | WP core tables + SupportCandy's `psmsc_*` tables |
| Cache      | none observed | — |
| Queue      | none (SupportCandy uses `psmsc_scheduled_tasks` for background email) | async email sending |
| Auth       | WordPress core (roles/capabilities) | login, agent vs. customer role separation |

## Data Flow
Browser → `wp-admin/admin.php?page=wpsc-tickets` → SupportCandy PHP controllers
→ `$wpdb` queries against `92CpH3vC_psmsc_*` tables → rendered admin page / AJAX response.

## External Integrations
- `wpsc-email-piping` — inbound email → ticket creation
- `tawkto-live-chat` — live chat widget
- `wp-mail-smtp` — outbound email delivery
- `wp-webhooks` / `wpsc-webhooks` — outbound webhook triggers
- `wp-remote-users-sync` (wprus) — syncs users to/from a remote system

## Known Risks
- Local (XAMPP) and live (cPanel) DBs are edited independently — drift risk if a change is
  applied on one side and forgotten on the other. Always double-check both when doing DB-level
  edits (see `memory/app_context.md` → Hard Constraints).
- Leftover/duplicate tables from other plugins or a past migration exist under prefixes like
  `92CpH3vC_2_`, `92CpH3vC_3_zbs_*`, `92CpH3vC_4_psmsc_*` — easy to query the wrong one by
  mistake. The active SupportCandy tables are plain `92CpH3vC_psmsc_*` (no digit segment).

## Architecture Decisions
See: `decisions/decision_log.md`
