# Consolidate Modules into Page Modules

Page Modules is a normal editable database ACF field group. Native Local JSON
exports remain enabled; its old PHP registration override is removed. All 28
existing layouts and field keys are preserved. The 29 old layouts are available
inside this same parent with labels ending in `(Transferred)` and new distinct
field keys. Their field names, types, relationships, images and rich text stay
the same. No manual ACF Sync is needed after applying this transfer.

## Explicit transfer

Use a current database/theme backup and review the preview against that site.
Run from the theme directory using an administrator account for writes:

```sh
wp eval-file tools/consolidate-modules.php preview
wp --user=admin-ivermectin eval-file tools/consolidate-modules.php apply
wp eval-file tools/consolidate-modules.php preview
wp eval-file tools/transfer-product-content.php preview
wp --user=admin-ivermectin eval-file tools/transfer-product-content.php apply
wp eval-file tools/transfer-product-content.php preview
```

The apply command installs or updates the database field definition, copies raw
metadata into `page_modules`, then reads every copied value back for exact
comparison. It preserves revisions without redirecting their writes to parents.
Existing populated modern Page Modules remain active and unchanged; their old
inactive Modules rows are appended as disabled, editable rows. They do not
appear on the page unless an editor enables them.
Empty target parents with leftover subfields are archived in the private
recovery snapshot and `_iva_page_modules_before_transfer` metadata first.

Preview must show zero pending transfers and zero conflicts after apply. For
records with both parents, edits between transfer and retirement are detected
and require review before retirement. Inspect
the frontend, **ACF → Field Groups → Page Modules → Edit**, and several page
editors. To remove the old top-level editor after verifying the copies:

```sh
wp --user=admin-ivermectin eval-file tools/consolidate-modules.php retire
```

Retire repeats verification and moves the old field group to WordPress trash.
It does not delete posts, saved Modules values, products, media or revisions.
The old schema is recoverable through trash and the pre-change backup. Do not
empty that trash until the migration has been reviewed.

## Preservation and recovery

No transfer/import runs on page loads. Changed source definitions, unmapped
current fields/layouts, mixed conflicting target data, duplicate groups, missing
posts and non-post module values stop the command before content writes.
An advisory lock prevents two transfer commands from running together.

InnoDB uses a transaction. For MyISAM, subfields are copied and checked before
the parent activates them. A failure restores values belonging to this transfer;
concurrent target edits are preserved for manual recovery instead of overwritten.
Each mutating command prints a private recovery snapshot path outside the theme.
Store that file with the full backup; never commit database dumps or snapshots.

The original field values stay in the database. If rollback is needed after
subsequent editing, compare the recovery snapshot and backup with those newer
edits before restoring anything. Restoring a full older database would replace
later client changes.

The separate product transfer copies the currently displayed summary and
description, legacy extra tabs, and legacy FAQs into missing Page Modules rows.
Existing rows are never replaced, including rows with intentionally blank text.
Original product content and metadata remain in place for recovery. Legacy FAQ
and tab positions are preserved; transferred summaries retain their original
presentation. The transfer requires the editable database schema to be present,
rechecks the source and destination, verifies each copy, and stops on conflicts.
For non-transactional tables, it attempts a guarded rollback on errors and
retains concurrent edits for manual review from its private recovery snapshot.

Existing migrated layouts use the same rendering adapters and legacy fallback.
Shop catalogue placement and blog/product flows distinguish transferred rows
from modern rows, preserving their previous positions and output.
