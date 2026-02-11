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
cd ansible
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
```yaml
drupal_archive_path: "../build/drupal.tar.gz"
drupal_install_path: "/srv/sites/drupal"
drupal_backup_path:  "/srv/backups/drupal"
drupal_site_home:    "/srv/data/drupal"

drupal_base_uri: "/"
drupal_base_url: "https://{{ ansible_host }}{{ drupal_base_uri }}"

drupal_db:
  host: "localhost"
  name: "drupal"
  user: "drupal"
  pass: "{{ vault_drupal_db.pass }}"

drupal_backup:
  host: "{{ backup.host }}"
  user: "{{ backup.user }}"
  path: "{{ backup.path }}"
  id_rsa:
    private: "{{ backup.id_rsa.private }}"
    public:  "{{ backup.id_rsa.public  }}"
```

Run the Playbook
----------------
    ansible-playbook deploy.yml -i /path/to/inventory

It is commonly desired to push changes to the apache configuration for deployed Drupal sites, without deploying a new version of Drupal.  This playbook declares a tag "apache_conf" that can be used to execute only the apache configuration file deployments.

    ansible-playbook deploy.yml -i /path/to/inventory --tag=apache_conf
License
-------

Copyright (c) 2016-2026 City of Bloomington, Indiana

This material is avialable under the GNU General Public License (GLP) v2.0:
https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
