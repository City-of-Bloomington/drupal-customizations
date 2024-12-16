# Drupal Upgrade

## Drupal 9 preparations
### Install Upgrade Status Module

### Themes
* Change admin theme to Claro
* Uninstall Seven

### Change WYSIWYG editor to CKEditor 5
One the editor is switched to CKEditor 5, uninstall the old CKEditor

#### CKEditor
Allowed HTML tags
<cite> <dl> <dt> <dd> <address> <details> <summary> <h2 id> <h3 id> <h4 id> <h5 id> <h6 id>
<blockquote cite> <ul type> <ol type start> <a hreflang title href data-entity-type data-entity-uuid data-entity-substitution>

<br> <p>
<strong> <em> <code> <li> <table> <tr> <td rowspan colspan> <th rowspan colspan> <thead> <tbody> <tfoot> <caption> <img src alt height width data-entity-uuid data-entity-type data-caption data-align>

#### CKEditor 5
<cite> <dl> <dt> <dd> <address> <details> <summary> <h2 id> <h3 id> <h4 id> <h5 id> <h6 id> <blockquote cite> <ul type> <ol type> <a hreflang title>


## Drupal 10 Upgrade
Replace the Drupal 9 install with a Drupal 10 build.

### Geolocation Field / jQuery
The latest version of Geolocation no longer requires jQuery.  However, to get to the latest version of Geolocation, we have to upgrade to Drupl 10.

#### Install the old jquery manually
Copy the web/modules/contrib/jquery directories, by hand, over to the Drupal 10 site.  This allows drush to see and remove the unneeded jqeury module after we've upgraded to 10.


### Drush UpdateDB
Run vendor/bin/drush updatedb


### Uninstall the old jquery_ui
Use the web interface to uninstall jQuery.  You'll have to do it one module at a time.
* jquery_ui_autocomplete
* jquery_ui_menu
* jquery_ui
