#!/bin/bash
# Rsyncs drupal file uploads to an external backup server
#
# @copyright 2011-2026 City of Bloomington, Indiana
# @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
host=`hostname`
remote_dir={{ drupal_backup.path }}/$host/drupal/files

cd {{ drupal_site_home }}
ssh -n -i /root/.ssh/{{ drupal_backup.user }} {{ drupal_backup.user }}@{{ drupal_backup.host }} "mkdir -p $remote_dir"
rsync -rle "ssh -i /root/.ssh/{{ drupal_backup.user }}" ./files/ {{ drupal_backup.user }}@{{ drupal_backup.host }}:$remote_dir/
