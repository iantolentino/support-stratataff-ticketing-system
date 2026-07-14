# SNIPPETS

> Canonical, copy-paste-correct patterns for this project. When the AI needs to write something
> that fits an existing pattern (a new route, a new component, a new test), it should match the
> pattern here instead of inventing a new style.

---

## Format

```
### [Pattern name — e.g. "API route handler"]
[One-line description of when to use this]

​```[language]
[code]
​```
```

---

## Patterns

### SupportCandy custom-field dropdown — view options
Use to see current values of any single-select custom field (e.g. "Immediate Superior" = field id 39).

```sql
SELECT * FROM `92CpH3vC_psmsc_options` WHERE custom_field = 39 ORDER BY load_order;
```

### SupportCandy custom-field dropdown — add an option
```sql
INSERT INTO `92CpH3vC_psmsc_options` (name, custom_field, date_created, load_order)
VALUES ('New Person Name', 39, NOW(), 22);
```

### SupportCandy custom-field dropdown — hide an option without deleting it
`psmsc_options` has no active/hidden flag, so reassign `custom_field` to a negative sentinel
(`-<field id>`) instead of deleting. Reversible by setting it back.

```sql
-- hide (39 = Immediate Superior field id; replace id list with the option ids to hide)
UPDATE `92CpH3vC_psmsc_options` SET custom_field = -39 WHERE id IN (45, 51, 53);

-- restore
UPDATE `92CpH3vC_psmsc_options` SET custom_field = 39 WHERE id IN (45, 51, 53);
```

### UI backup before a layout edit
Always run before editing any theme/plugin CSS or template file, local and live.

```powershell
powershell -File _brain\tools\ui-backup.ps1 -Path "wp-content\themes\<theme>\style.css"
```

### UI revert if a layout change breaks something
```powershell
powershell -File _brain\tools\ui-restore.ps1 -Path "wp-content\themes\<theme>\style.css"
```
