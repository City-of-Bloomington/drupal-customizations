# drupal-module-promt
Drupal module to integrate with City of Bloomington's Parks & Rec management system.  This is a read-only module.  Drupal should only ever read information from PROMT.  This allows us to render Parks and Rec information on Drupal nodes.

## Installation
### Composer
We are using [Composer to install and manage our Drupal sites](https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies).


However, because this module is not hosted on Drupal.org, you must add our Github repository
to your composer.json before adding the require statement.

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "City-of-Bloomington/promt",
                "type": "drupal-module",
                "version": "dev",
                "source": {
                    "type": "git",
                    "url": "https://github.com/City-of-Bloomington/drupal-module-promt",
                    "reference": "master"
                }
            }
        }
    ],
    "require": {
        "City-of-Bloomington/promt": "dev"
    }
}
```
