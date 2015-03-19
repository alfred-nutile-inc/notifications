# Notification API

video [here](https://www.youtube.com/watch?v=eb6BvQt0Qsc) 

## Install

@TODO make this a proper l5 package 

Until I do you need to

## Include the route file

~~~
composer require alfred-nutile-inc/notifications:dev-master
~~~


~~~
    //Notifications
    $path = base_path('vendor/alfred-nutile-inc/notifications/src/Notifications/routes.php');
    if(File::exists($path))
        require_once($path);
~~~

## Copy over migration files 

~~~
2014_05_05_212549_create_notifications_table.php
2014_05_05_212609_create_notifications_categories_table.php
~~~

To your database/migrations folder

### Register the provider and facade

~~~
//Providers
'AlfredNutileInc\CoreApp\Notifications\NotificationsServiceProvider',

//facade
'Notify' 	       => 'AlfredNutileInc\CoreApp\Notifications\NotificationFacade'
~~~

Then use as needed and seen in video


~~~
<?php namespace Foo

use AlfredNutileInc\CoreApp\Notifications\NotificationFacade as Notify;

Notify::setRead(-1)->getAll();
Notify::setRead(0)->setLimit(20)->setFromId('mock-project-1')->to('mock-user-1');
~~~

You can also include the routes file into your routes file for api access to the system

The test files shows some example usage

[/tests/CoreApp/Tests/NotificationTest.php](/tests/CoreApp/Tests/NotificationTest.php)
[/tests/CoreApp/Tests/NotificationListenerTest.php](/tests/CoreApp/Tests/NotificationListenerTest.php)

