Drupal - Ansible
======================

The included ansible playbook and role will install Drupal along with required dependencies.

These files also serve as living documentation of the system requirements and configurations necessary to run the application.

This assume some familiarity with the Ansible configuration management system and that you have an ansible control machine configured. Detailed instructions for getting up and running on Ansible are maintained as part of our system-playbooks repository:

https://github.com/City-of-Bloomington/system-playbooks

This deploys a tarball of Drupal that you have built or downloaded.  For instructions on preparing your own release, refer to the main [README](../README.md) for this repository.

On the ansible control machine, here's the executive summary:

```bash
git clone https://github.com/City-of-Bloomington/drupal-customizations
cd drupal-customizations
composer update
make
cd uReport/ansible
ansible-galaxy install -r roles.yml
ansible-playbook deploy.yml -i /path/to/inventory
```

Dependencies
-------------
Decide how you want to get the other necessary ansible roles:

    ansible-galaxy install -r roles.yml

or for development:

```bash
git clone https://github.com/City-of-Bloomington/ansible-role-linux.git  ./roles/City-of-Bloomington.linux
git clone https://github.com/City-of-Bloomington/ansible-role-apache.git ./roles/City-of-Bloomington.apache
git clone https://github.com/City-of-Bloomington/ansible-role-mysql.git  ./roles/City-of-Bloomington.mysql
git clone https://github.com/City-of-Bloomington/ansible-role-php.git    ./roles/City-of-Bloomington.php
git clone https://github.com/City-of-Bloomington/ansible-role-solr.git   ./roles/City-of-Bloomington.solr
```


Variables
---------
All of the variables used are first declared in group_vars/all.yml.  Override them in your inventory as needed.

### drupal_archive_path
This is the path, on your local host, for the tarball to deploy.  If you compile your own release, the makefile for the repository will generate this at the path declared in group_vars/all.

### drupal_install_path
Where on the host to deploy Drupal.  Apache will be configured to serve drupal out of this directory.

### drupal_backup_path
The deploy playbook will set up a cron script to create nightly database dumps.  The database dumps will be tarballed and stored in the backup path.

### drupal_site_home
For our deployments, we find it convenient to store the default site outside of the Drupal directory.  This allows us to deploy new versions of Drupal by deleting Drupal (install path) and replacing it with a copy of the new Drupal directory.  The deploy playbook will create a symlink to drupal_site_home.

### drupal_base_uri
The deploy playbook supports hosting Drupal at either the root or a subdirectory of the Apache host.  If you configure drupal_base_uri as "/", then the playbook will overwrite the 000-default.conf.  If you deploy it as a subdirectory, it will create a separate drupal.conf in sites-enabled/conf.d.


Run the Playbook
----------------
    ansible-playbook deploy.yml -i /path/to/inventory

It is commonly desired to push changes to the apache configuration for deployed Drupal sites, without deploying a new version of Drupal.  This playbook declares a tag "apache_conf" that can be used to execute only the apache configuration file deployments.

    ansible-playbook deploy.yml -i /path/to/inventory --tag=apache_conf
License
-------

Copyright (c) 2016-2019 City of Bloomington, Indiana

This material is avialable under the GNU General Public License (GLP) v2.0:
https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
