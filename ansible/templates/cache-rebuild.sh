#!/bin/bash
cd {{ drupal_install_path }}/web
../vendor/bin/drush cache-rebuild
