#!/bin/bash
DRUPAL_MODULES=/srv/sites/drupal/modules
for f in $(ls *.tar.gz); do
    tar xzvf $f
done

for d in $(ls -d */); do
    mv $d $DRUPAL_MODULES
done
