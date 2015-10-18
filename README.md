# yii-lti

Yii LTI extension.

This class helps Yii to processing LTI requests.

It uses LTI_Tool_Provider.php library
http://projects.oscelot.org/gf/project/php-basic-lti/frs/

## Installation
1. Download and upload main extension file  https://raw.githubusercontent.com/andreykin/yii-lti/master/extensions/yii-lti/YiiLTI.php  to /protected/extensions/yii-lti/;
1. Extract LTI_Tool_Provider files from http://projects.oscelot.org/gf/projects/php-basic-lti/
  to /protected/vendors/lti_tool_provider/;
1. Upload your custom LTI Tool Provider class to /protected/components/ (if needed)
1. Include extension in config/main.php

## Usage
* Based on official demo Rating APP, I create some demo controller and components to show you, how it works.
* You also need some customization in your "user" table: add new varchar(255) fields "lti_user_id" and "auth_service_name";
* First navigate to http://localhost/app/lti/createCustomer url (I assume you have human URL's enabled and some PDO database connection configured in your yii app config file).
* Few tables will be created in your database, and new record will be added in customer table. (db init will be provided later, now you must use some .sql script from original library to create database structure. there may be some error with foreign key, so you feel free just drop it).
* You can use dummy tool http://ltiapps.net/test/tc.php for debug pursoses. There is some bug if dots is in customer key, so use "moodlekey" as key and "secret" as secret for connect. Provide http://localhost/app/lti/connect as your launch URL, fill secret and userkey fields, click "save data" and then "Launch TP in new window" buttons.
* New window opens, with your app. New user will be created and authorized.
* Use Moodle external application course element, added to some moodle course, to authorize in your yii application from moodle installation.

## Useful Links
* http://www.spvsoftwareproducts.com/php/lti_tool_provider/
* http://projects.oscelot.org/gf/project/php-basic-lti/frs/
* http://ltiapps.net/workshop/lti-workshop.html
* http://ltiapps.net/test/tc.php
