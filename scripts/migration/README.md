* Backup
    * cover images
    * content images
    * video
    * image browser
* Upgrade to 8.9.16
    * Upgrade to Drush 10

* Purge Revisions  (2 hours)
* Purge Media
    * Delete all fields from content types (and taxonomy)
    * Delete all media
    * Purge unused files

Purge all the deleted stuff
`vendor/bin/drush eval "field_purge_batch(100000)"`

There will likely be leftover field_deleted_* tables
If drush thinks everything's deleted, and the tables are empty, then it's
okay to delete the tables.
`vendor/bin/drush eval "var_dump(\Drupal::state()->get('field.storage.deleted'))"`


* Uninstall modules
    * Media
    * Video Embed
    * Entity Browser
    * Directory
    * OnBoard
    * Promt

* Remove unused modules from composer.json

* Upgrade modules to support Drupal 9
    * CAS 2
    * Geolocation 3
    * Linkit 6

The paragraphs modules is already the latest version, but the tests run into memory problems.
Don't forget to run drush updatedb


* Upgrade to Drupal 9

Use composer to prepare a Drupal 9 release

Custom modules relied on Drupal 8 plugin contexts.  Drupal 9 changed how the block plugin contexts worked.  We have to completely remove the modules, in order to clear the configurations that attempted those contexts.  Then, we have to redo the block layouts these modules provided.

* Reinstall custom modules
    * Directory
    * OnBoard
    * Promt
* Redo Block Layouts
    * Directory
        * Staff
    * OnBoard
        * Members
        * Reports
        * Board Links
    * Promt
        * Location activities
        * Catgegory activities
* Grant permissions to Media

The new media system does not have any role permissions assigned.  We'll need to run through all the permissions and grant them to appropriate roles.
