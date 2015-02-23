# Notification API

video [here](https://www.youtube.com/watch?v=eb6BvQt0Qsc) 

## Install

Register the provider and facade

~~~
        //Providers
		'AlfredNutileInc\CoreApp\Notifications\NotificationsServiceProvider',
		//facade
        'Notify' 	       => 'AlfredNutileInc\CoreApp\Notifications\NotificationFacade'
~~~

Then use as needed


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

