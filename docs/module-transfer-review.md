# Module transfer review, 11 November 2026

Source: the supplied InstaWP UpdraftPlus database backup from 11 November 2026.
Its stored site URL was confirmed as `https://ivermectinaustralia.instawp.site`.
The backup was imported only into isolated local review databases. Database
dumps, recovery snapshots and temporary authentication files are outside Git.

The backup is used to review the field mapping. The production command reads
the current values on the site where it runs; it never restores local or backup
page content over the client's current values.

## Completed checks

- Backup copy: transferred 300 records, comprising 32 current pages/posts and
  268 revisions. Preserved 29 records already using modern Page Modules,
  including the current homepage and 28 revisions.
- Local site: transferred its own 291 records, comprising 32 current pages/posts
  and 259 revisions; preserved 11 existing modern records.
- Original editorial post rows and non-transient options compared unchanged.
- Every copied metadata value read back exactly, with only the expected parent,
  layout names and field-reference keys mapped. Original Modules values retained.
- On the backup copy, 27 leftover target metadata entries under empty parents
  were preserved in recovery archives across 12 records, including three current
  records. They were not silently discarded or rendered as extra content.
- Injected a MyISAM root-activation failure on a fresh backup copy. The command
  detected it and restored editorial post/metadata rows exactly. Retrying the
  transfer updated the same field group without creating duplicate groups.
- After retirement: one active Page Modules group, old Modules group in trash,
  root field stored as Flexible Content, and 57 editable layouts.
- Rendered module content for all 33 current records with legacy data before
  and after transfer/retirement. Visible content matched in every record on both
  the local site and the supplied backup copy. Comparison excluded scripts,
  styles and Gravity Forms' randomized hidden honeypot. Carousel/anchor IDs are
  generated per render and were checked separately from content values.
- Browser: Page Modules opens in ACF's field editor with Save Changes available;
  a page editor has one Page Modules box, zero old Modules boxes, and transferred
  rows with their original rich text present in the editor.
- Browser: Shop, Melbourne, Privacy Policy and Our Reviews returned HTTP 200 at
  390px and 1440px, with one main H1 and no document horizontal overflow. The
  Shop catalogue retained its 16 cards and Melbourne retained its seven cards.
- PHP syntax checks, CSS build and Git whitespace checks passed.

The original 28 modern layouts and their existing field definitions remain in
the theme schema; additional editable fields and 29 transferred layouts use
separate field keys. Original
inactive homepage Modules data is appended as disabled, editable rows, so it
remains available in the editor without duplicating the current homepage.

## Follow-up review, 12 November 2026

The 20:50 InstaWP database backup was imported into an isolated local copy.
The page transfer verified 329 legacy records, including revisions; this includes
29 records with existing modern content whose older rows were appended disabled.
Visible text and image/link/video
references matched for the 33 current legacy records checked. The old field
group was retired only in the test copy, where the merged editable group had
58 layouts, including Product Information Tabs.

The separate product transfer copied missing summaries and descriptions for
17 products, leaving 16 existing FAQ modules untouched. Before/after product
pages had matching visible text and image/link/video references; original
product rows and 2,808 existing metadata rows were unchanged. An additional
synthetic test covered a legacy FAQ and extra tab, preserving their positions.
Explicitly empty modules were not replaced, and an injected failure on MyISAM
was rolled back in the isolated database. No live migration was run.

## Deployment boundary

Local consolidation and backup-copy validation are complete. InstaWP has not
been changed by these checks. A Git pull alone does not transfer its database:
follow `page-modules-editor.md` for explicit preview, apply, verification and
retirement commands. Source schema changes after the reviewed backup cause the
command to stop for a mapping review before writing content.
