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
