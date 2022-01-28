# City of Bloomington Drupal Site

The City of Bloomington is migrating our site to use Drupal as our content management system.

## Requirements
Drupal runs on a standard Linux-Apache-MySQL-PHP (LAMP) stack. Instructions for setting up those requirements are beyond the scope of this project, but we do maintain separate repositories to document this:

https://github.com/City-of-Bloomington/system-playbooks

Specifically, these two roles should yield a working foundation:

https://github.com/City-of-Bloomington/ansible-role-php
https://github.com/City-of-Bloomington/ansible-role-mysql

## Installation
* clone the project locally
* run composer update
* build and deploy
* create the database
* add Apache configuration

### Clone from Github
```bash
git clone https://github.com/City-of-Bloomington/drupal-customizations.git drupal
```

### Composer update
When you are applying updates to Drupal, it is vitally important to clear the drupal cache before running `composer update`.  Drupal's customizations to composer do not check for fresh versions from Github.  **You must manually delete the old modules and clear composer's cache**.

```bash
cd drupal
composer clear-cache
cd web/modules/contrib
rm -Rf *
cd ../../themes/contrib
rm -Rf *
cd ../../../
composer update
```

### Build and Deploy
The build requires sassc to compile the CSS.  Once you have sassc installed, you can run make.  The will compile all the CSS and create a clean build directory.  This will strip out all the Git repo stuff, resulting in a much, much smaller size for the site installation.
```
cd drupal
make
```

I usually use Rsync to deploy the `build` directory.
```
cd drupal
rsync -rlve ssh ./build/ drupal.server.org:/srv/sites/drupal/
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

## Modules to enable/disable
Once you have the default installation finished, you'll want to enable modules
we're using - and disable modules we're not going to use.  Disabling modules
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
