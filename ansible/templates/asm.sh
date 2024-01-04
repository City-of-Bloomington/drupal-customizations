#!/bin/bash
cd {{ drupal_site_home }}/files
rm -Rf asm
mkdir asm
chown -R www-data:staff asm

cd {{ drupal_install_path }}
vendor/bin/drush cache-rebuild
