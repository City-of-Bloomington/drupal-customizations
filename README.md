# City of Bloomington Drupal Site

This the project for the City's new website, built using Drupal.  It is created from an initial setup of Drupal's composer installation:

https://github.com/drupal-composer/drupal-project

## Installation
* clone the project
* run composer update
* create the database
* add Apache configuration

### Clone from Github
```bash
cd /srv/sites
git clone https://github.com/City-of-Bloomington/drupal-customizations.git drupal
```

### Composer update
```bash
cd /srv/sites/drupal
composer update
```

### Create the database
```mysql
create database drupal;
grant all privileges on drupal.* to drupal@localhost identified by 'password';
flush privileges;
```

### Apache configuration
```apache
Alias /drupal  "/srv/sites/drupal/web"
<Directory     "/srv/sites/drupal/web">
    Options FollowSymLinks
    AllowOverride None
    Require all granted

    Include /srv/sites/drupal/web/.htaccess
</Directory>
```

## Migration from Drupal 7
We've already started building a site using Drupal 7.  We're planning on migrating what we've got set, so far, into Drupal 8.

* install an empty instance of Drupal 8
* enable the migration module
* use the web interface to point at Drupal 7

### notes
* CAS                     cannot be installed during migration
* External Authentication cannot be installed during migration

#### Basic Page renamed
The name of the default content type, "Basic Page" has been renamed
to "Basic page".  This means that after the migration, we end up with
two content types: "Basic Page" and "Basic page".  We can just delete
the new Drupal 8 "Basic page", and use what we migrated.

#### Plain text fields
We have quite a few fields that we created in order to store identifiers
for integrations.  These fields were plain text in Drupal 7; however, the
migration brings them across as HTML formatted fields.

Rather than try and write complicated custom migration code, it might be
easiest to just delete the fields that come across and recreate them in
Drupal 8.

* Calendar ID fields
* Department directory DN's

## Modules to enable/disable
Once you have the default installation finished, you'll want to enable modules
we're using - and disable moduels we're not going to use.  Disabling moduels
that are not in use will greatly improve the performance of Drupal.

### Enable
* CAS
* Pathauto

### Disable
* Comment
* Contact
* Contextual Links
* History
* Quick Edit
* RDF
* Tour
