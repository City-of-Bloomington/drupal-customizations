#!/bin/bash
# Creates a tarball containing a full snapshot of the data in the site
#
# @copyright 2011-2018 City of Bloomington, Indiana
# @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
APPLICATION_NAME=drupal
MYSQL_CREDENTIALS=/etc/cron.daily/backup.d/$APPLICATION_NAME.cnf
BACKUP_DIR={{ drupal_backup_path }}
APPLICATION_HOME={{ drupal_install_path }}
SITE_HOME={{ drupal_site_home }}
LOG_FILE=/var/log/cron/$APPLICATION_NAME

MYSQL_DBNAME=$APPLICATION_NAME

# How many days worth of tarballs to keep around
num_days_to_keep=5

#----------------------------------------------------------
# No Editing Required below this line
#----------------------------------------------------------
now=`date +%s`
today=`date +%F`

# Dump the database
cd $SITE_HOME
mysqldump --defaults-extra-file=$MYSQL_CREDENTIALS $MYSQL_DBNAME > $SITE_HOME/$MYSQL_DBNAME.sql

# Tarball the Data
tar -czf $today.tar.gz $MYSQL_DBNAME.sql
mv $today.tar.gz $BACKUP_DIR

# Purge any backup tarballs that are too old
cd $BACKUP_DIR
for file in `ls`
do
	atime=`stat -c %Y $file`
	if [ $(( $now - $atime >= $num_days_to_keep*24*60*60 )) = 1 ]
	then
		rm $file
	fi
done

touch $LOG_FILE
