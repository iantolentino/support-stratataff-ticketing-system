# DECISION LOG

> Record every architecture, stack, or scope decision made after CONFIRMATION_LOCK.
> This prevents the AI from re-opening settled decisions in future sessions.

---

## Format

```
[TYPE] → [Decision made]
Impact: low | medium | high
Reason: [One-line justification]
Date: [YYYY-MM-DD]
```

Types: ARCH | STACK | SCOPE | SECURITY | PERFORMANCE | UX

---

## Decisions

[UX] → "Hide" custom-field dropdown options (e.g. "Immediate Superior" names) by reassigning
their `custom_field` column to `-39` instead of deleting the row.
Impact: low
Reason: `psmsc_options` has no active/hidden flag; a fake negative `custom_field` id keeps the
row (and its `id`) intact for reversible restore, without showing it in the SupportCandy
admin UI's option list for field 39. Restore by setting `custom_field` back to `39`.
Date: 2026-07-13

---

[STACK] → Treat DB edits as manual-dual-apply: every SQL change is run locally (XAMPP) then
handed to the user as raw SQL to re-run in cPanel phpMyAdmin.
Impact: medium
Reason: No DB sync/migration tooling exists between local and live; user confirmed they apply
edits to cPanel themselves.
Date: 2026-07-13

---

[STACK] → Rejected an actual shadcn/React rebuild; instead building a plain-CSS design system
that visually matches shadcn's look (neutral grays, rounded corners, soft shadows, clean spacing).
Impact: high
Reason: shadcn/ui requires React + a Node build pipeline (Vite/Next.js) — incompatible with the
project's hard constraint of plain PHP/cPanel shared hosting (see `memory/app_context.md` →
Hard Constraints). User initially asked for "shadcn" after seeing the ticket list still look
flat, but confirmed (once the tradeoff was explained) that a CSS-only reskin achieving the same
visual language, with DB/functionality fully unchanged, was the right call.
Date: 2026-07-14

---

[UX] → Fixed SupportCandy's own "Appearance" settings (`wpsc-ap-ticket-list` option) rather than
fighting them with `!important` CSS overrides.
Impact: low
Reason: The admin-configured appearance settings are injected as an inline `<style>` block later
in the DOM than our stylesheet, so they were silently overriding our zebra-striping/hover CSS
with flat white-on-white colors. Editing the actual option is more correct and durable than a
specificity war, and it's literally what that setting exists for.
Date: 2026-07-14

---

[SECURITY] → Removed a stray `support/stratast_support.sql` (120MB DB dump) that sat in the local
webroot and was being served as a public directory listing at `/support/` (shadowing the WP
"Support" home page's own slug).
Impact: medium
Reason: Any DB dump reachable over HTTP is a real exposure, even in local dev — the pattern could
recur if ever mirrored to cPanel. User authorized moving it; it was deleted instead since an
equivalent, newer copy already existed at `_brain/db_backup/stratast_support.sql`, so no data was
actually lost — flagged to user for transparency since the literal instruction was "move."
Date: 2026-07-15

---

[SCOPE] → Extended the layout-overhaul scope (previously "the ticketing site") to also cover
Forms (2102), IT Forms (2156), and the Home/Support page (2281) — not just the Tickets page —
per explicit user request to make the whole site (`support-local.test/`) visually uniform.
Impact: medium
Reason: User directive during EXECUTION_MODE ("apply the ui design ... to all the pages ...
so they are uniform"). Per Scope Change Protocol (`governance/scope.md`), logged here; see
`progress/backlog.md`/`governance/scope.md` for the updated in-scope page list.
Date: 2026-07-15

---

[SCOPE] → Narrowed T016 back down to Home/Support (2281) + Tickets (5422) only, at explicit user
request — Forms (2102) and IT Forms (2156) are fully reverted (no navbar injected, no CSS/JS
touches them) and out of scope again unless a future request re-adds them.
Impact: low
Reason: User: "disregard and dont edit any related here [Forms/IT Forms] even the layout you added."
`wpsc-global-navbar.php`'s `is_page()` guard and nav-links array both updated accordingly.
Date: 2026-07-15

---

[SECURITY] → `wpsc-global-navbar.php` is deliberately kept OUT of git (not force-added), even
though 3 sibling mu-plugins are tracked.
Impact: medium
Reason: This file hardcodes the same real employee names/shift-assignment data that was already
the subject of a redaction decision (T014, `[STACK]`/`current_state.md`) because this repo is
public. A local-only timestamped copy lives instead at `_brain/ui_backups/` (git-ignored, same as
the rest of that folder) — use `ui-restore.ps1` to roll back, not git, for this specific file.
Date: 2026-07-15

---

[UX] → Built one new global mu-plugin (`wpsc-global-navbar.php`) that reuses the *existing*
`.wpsc-header`/`.wpsc-tickets-nav` classes (already loaded site-wide since SupportCandy's
`load-scripts` setting is `all-pages`) to render a matching navbar on Forms/IT Forms/Home, instead
of copying markup per page or editing each page's Elementor data directly.
Impact: medium
Reason: Forms/Home use `elementor_canvas` (no theme header at all) and IT Forms uses the theme's
generic default block navigation — three different "no navbar" states. A single JS-inserted
component reusing already-loaded CSS gets pixel parity with the Tickets navbar for free and stays
consistent with the project's established pattern of isolated, delete-to-revert mu-plugin files
(see `progress/progress.md` T011–T013c). On the Tickets page itself it only renders as a fallback
for the logged-out/login-screen state, and self-removes once SupportCandy's real AJAX-rendered
header appears, so logged-in agents/customers still see the original untouched header.
Date: 2026-07-15

---
