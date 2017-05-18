# City of Bloomington Drupal Site

The City of Bloomington is migrating our site to use Drupal as our content management system. 

## Requirements
Drupal runs on a standard Linux-Apache-MySQL-PHP (LAMP) stack. Instructions for setting up those requirements are beyond the scope of this project, but we do maintain separate repositories to document this:

https://github.com/City-of-Bloomington/system-playbooks

Specifically, these two roles should yield a working foundation:

https://github.com/City-of-Bloomington/ansible-role-php
https://github.com/City-of-Bloomington/ansible-role-mysql

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

## Source

This project is based on an initial setup of Drupal's composer installation:

https://github.com/drupal-composer/drupal-project

## Work in progress:
The following role is an attempt to specify the full configuration using ansible, but it is incomplete at this point (2017.05.15):

https://github.com/City-of-Bloomington/ansible-role-drupal

