# Database migrations

Plain `.sql` files in this folder, applied through **Admin → Application
Update**. Unlike the plugin installer's `plugin.sql`/`update.sql` (which run
automatically as part of installing an uploaded package), migrations here are
never executed silently: the update page shows each pending file's contents
and needs an explicit, separate confirmation before running it. Files
arrive on the server the same way as any other code change (through the
GitHub-based updater or a manual sync) -- this folder is not a separate
upload path.

## Naming

`YYYY-MM-DD_HHMMSS_short_description.sql`, e.g.:

```
2026-09-10_140000_add_suggest_category_table.sql
```

The timestamp prefix keeps files sorted in the order they should run.

## Rules

- One migration = one file. Keep them small and focused.
- Idempotent where reasonable (`CREATE TABLE IF NOT EXISTS`, `ADD COLUMN IF
  NOT EXISTS` where the DB version supports it) since a migration that
  half-applies and gets retried should not fail on the second attempt.
- Never write `DROP DATABASE`, `GRANT`, `LOAD_FILE`, `INTO OUTFILE`/`DUMPFILE`,
  or `LOAD DATA` -- the update page rejects any migration containing these,
  the same rule the plugin installer uses.
- A migration only ever needs to change schema/data for this application's
  own tables. It doesn't need to (and can't, through this mechanism) touch
  files -- that's what the regular update already does.

## Tracking

Applied migrations are recorded in a `schema_migrations` table (created
automatically on first use) by filename, so a migration only ever runs once
per server, and re-running the updater after a partial failure only retries
the migrations that didn't complete.
