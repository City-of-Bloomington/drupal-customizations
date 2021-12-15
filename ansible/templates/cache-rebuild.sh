#!/bin/bash
cd {{ drupal_install_path }}
vendor/bin/drush cache-rebuild
