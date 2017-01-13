# Migration
CAS                     cannot be installed during migration
External Authentication cannot be installed during migration

## Basic Page renamed
The name of the default content type, "Basic Page" has been renamed
to "Basic page".  This means that after the migration, we end up with
two content types: "Basic Page" and "Basic page".  We can just delete
the new Drupal 8 "Basic page", and use what we migrated.

## Plain text fields
We have quite a few fields that we created in order to store identifiers
for integrations.  These fields were plain text in Drupal 7; however, the
migration brings them across as HTML formatted fields.

Rather than try and write complicated custom migration code, it might be
easiest to just delete the fields that come across and recreate them in
Drupal 8.

* Calendar ID fields
* Department directory DN's
