## Drupal Calendar Module

Google Calendars are an easy way for people to manage events on a calendar. Rather than reinvent that wheel, this Drupal module allows events from Google Calendar to be integrated with a content node in your Drupal instance. 

[Calendar Aggregator](https://github.com/City-of-Bloomington/calendar-aggregator) is a related project that is useful to combine multiple Google Calendars into one.

## Installation
### Composer
We are using [Composer to install and manage our Drupal sites](https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies).


However, because this module is not hosted on Drupal.org, you must add our Github repository
to your composer.json before adding the require statement.

Also, this module requires the Google API Client library.  This is available through the normal
Drupal repositories.  Make sure to add it, as well, to your composer.json as a "require".

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "City-of-Bloomington/calendar",
                "type": "drupal-module",
                "version": "dev",
                "source": {
                    "type": "git",
                    "url": "https://github.com/City-of-Bloomington/drupal-module-calendar",
                    "reference": "master"
                }
            }
        }
    ],
    "require": {
        "google/apiclient": "~2.0",
        "City-of-Bloomington/calendar": "dev"
    }
}
```

### Google Service Account
In order to make queries to your Google Calendar, you must register for an API key,
create a Service Account, and download the credentials to your Drupal site.  You
register for API keys on the [Google Developer Console](https://console.developers.google.com).

This module is a "Server-to-Server" application;  Drupal will be making requests as
a user you specify in the module's settings. You will want to read up
on how to set up OAuth 2.0 for your Google Service Account.

* https://developers.google.com/identity/protocols/OAuth2ServiceAccount
* https://developers.google.com/google-apps/calendar/quickstart/php
* https://developers.google.com/api-client-library/php/auth/service-accounts

Once you have a credentials.json file with your private key, you must place the file
in your Drupal installation's /web/sites/default, next to settings.php.

The credentials.json file should look like this:
```json
{
  "private_key_id": "XXXXXX",
  "private_key": "-----BEGIN PRIVATE KEY-----\nXXXX\n-----END PRIVATE KEY-----\n",
  "client_email": "someuser@developer.gserviceaccount.com",
  "client_id": "someuser.apps.googleusercontent.com",
  "type": "service_account"
}
```

### Configuration

TODO: general documentation for configuring module in drupal

e.g. configure the field to show up in desired content types

### Usage

After the module has been configured for use in your Drupal instance, content editors will see a field called Calendar:

[drupal screenshot showing calendar edit field]

This requires the Google Calendar ID, which can be tricky to find. Users will often put a link to the Google Calendar, but this won’t work. 

* Find the Calendar ID by looking at the "Calendar Settings" for the given calendar. It should look something like: bloomington.in.gov_1bo74srf3b3e7fn9v29j95s994@group.calendar.google.com

![image alt text](static/image_0.png)

![image alt text](static/image_1.png)

TODO: update screenshots for google calendar to ones showing new UI

## Calendar Best Practices

### Permanent Account

If your organization is using G-Suite and paying for the service, remember to share published calendars with a central, permanent account. If a calendar is only associated with the user who created it and they leave the organization, the account may get deleted. If the account is deleted, then the calendar will be deleted and people may wonder where the calendar went on the page. 

### Format Events Consistently

For Board and Commissions, the *Title* should be the name of the commission only. Set the *Location* by reserving City room in Google Calendar. The *Description* should describe the purpose of the board, explaining any acronyms (like BDU, RDC, HAND, CFRD, etc.) and provide a point of contact for the meeting.  Here is an example below:

**Bloomington Digital Underground (BDU) Advisory Committee **

*Cityhall Hooker Conference Room (RM #245)*

The BDU Advisory Committee advises the City on management of its fiber assets, related City telecommunications and IT infrastructure investments, and strategic IT initiatives.  Contact ITS Director, Rick Dietz, for additional information at dietzr@bloomington.in.gov or 812-349-3485.

### Cancelling Events

To cancel an event it is best to add "(CANCELLED)" in the title of the event instead of deleting the event from the calendar. This new title will be applied to the website within the hour. If you want to make the meeting room available, remove the meeting room from the event. 

### Event Links

An event can now include a link to a page with more details. The destination link should be added to the Summary field in Google Calendar. If more than one link is provided, the first link will be the one that is used. Please do not include a period at the end of a link. 

