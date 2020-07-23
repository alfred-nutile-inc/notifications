# Notification API

video [here](https://www.youtube.com/watch?v=eb6BvQt0Qsc) 

## Install

### Composer

~~~
composer require alfred-nutile-inc/notifications:dev-master
~~~

### Add the provider

```
AlfredNutileInc\Notifications\NotificationsServiceProvider::class,
```

## Publish Migrations

```
php artisan vendor:publish --provider="AlfredNutileInc\Notifications\NotificationsServiceProvider" --tag='migrations'
```

## Publish Assets

```
php artisan vendor:publish --provider="AlfredNutileInc\Notifications\NotificationsServiceProvider" --tag='public'
```


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


# Sending Notifications with NotificationHelper

In this example, a "Tactic" sends a notification to itself in order to later be displayed on a Tactic "activity" page.

->setRecipients can be called multiple times with different sender types, so you could send the notification to multiple different models all at once.

```php
$helper->setFrom(Tactic::class, $tactic->id)
      ->setRecipients(Tactic::class, [$tactic->id])
      ->setMessage($this->faker->paragraph(3))
      ->setCategoryByName('test_tactic_notification', 'Tactic Notification')
      ->sendNotifications();
```

# Plug it into Angular


Base blade file

~~~
<?php
use AlfredNutileInc\CoreApp\Notifications\NotificationFacade as Notify;

/**
 * @Middleware("auth")
 */
class AngularController extends BaseController {


    /**
     * @Get("approve", as="approve.dash")
     */
	public function index()
	{
        $chat_on = getenv('CHAT_ON');

        $message = Notify::setRead(0)->setLimit(10)->getAll();
        JavaScript::put(
            [
                'pusher_public_key' => getenv('PUSHER_PUBLIC'),
                'sauce_key' => getenv('SAUCE_TOKEN'),
                'sauce_user' => getenv('SAUCE_USER'),
                'token' => csrf_token(),
                'profile' => User::profile(),
                'environment' => getenv('APP_ENV'),
                'messages'   => $message
            ]
        );
		return View::make('layouts/main', compact('chat_on'));
	}

}
~~~

I also have a main angular config file that sets these constants

~~~
#config.js
    function constants() {
        return {
            'pusher_public_key': window.pusher_public_key,
            'sauce_key': window.sauce_key,
            'sauce_user': window.sauce_user,
            'profile': window.profile,
            'js_root': '/assets/js/',
            'debug': window.debug,
            'messages': window.messages
        };
    }
~~~

Add if you want the public notifications javascript to your angular area and include the js files into your app.

Then update your nav so people can get to the main ui and the drop down

~~~~
#Main ui nav

~~~
<li ui-sref-active="active">
  <a ui-sref="notifications.messages"><i class="fa fa-envelope-o"></i> <span class="nav-label">Notifications</span></a>
</li>
~~~
And load it in the main angular controller so on page load we listen for pusher notifications

~~~
#controller.js
    /**
     * @NOTE Noty is better than toaster for this one thing
     */
    function MainCtrl($window, PusherService, Noty, ProjectsService, localStorageService, ENV) {

        var vm = this;
        vm.localStorageService = localStorageService;
        vm.Noty = Noty;
        vm.ENV = ENV;
        vm.messages = [];
        vm.PusherService = PusherService;
        vm.ProjectsService = ProjectsService;
        vm.loadProjects = loadProjects;
        vm.setOriginalMessages = setOriginalMessages;
        vm.maintenanceNotification = maintenanceNotification;
        vm.noticeMessage = noticeMessage;
        vm.ringBell = ringBell;

        vm.profile = {};
        vm.projects = [];
        vm.activate = activate;
        vm.activate();

        ///////

        activate();

        function activate() {
            vm.profile = $window.profile;
            vm.PusherService.setPusher('maintenance', 'behat', vm.maintenanceNotification);
            vm.loadProjects();
            if(vm.localStorageService.isSupported) {
                console.log('supported');
                console.log(vm.localStorageService.keys());
            }
            vm.setOriginalMessages();
            vm.PusherService.setPusher('notifications', ENV.profile.id, vm.noticeMessage);
        }

        function setOriginalMessages()
        {
            angular.forEach(vm.ENV.messages, function(v,i){
                vm.messages.push( { "id": v.id, "message": v.notification_message.message } );
            });
        }

        function noticeMessage(data)
        {

            if(!angular.isUndefined(data))
            {
                vm.messages.push(data[0]);
                vm.toaster.pop("info", "Message", data[0].message, 5000, 'trustedHtml');
                vm.ringBell();
                $rootScope.$apply();
            }
        }

        function ringBell()
        {
            var audio = $('#notificationAudio');
            if (audio.length == 0) {
                $('<audio id="notificationAudio"><source src="/js/lib/notifications/notify.mp3" type="audio/mpeg"></audio>').appendTo('body');
                audio = $('#notificationAudio');
            }
            audio[0].play();
        }

        function loadProjects()
        {
            if(vm.projects.length < 1)
            {
                vm.ProjectsService.index().then(function(results) {
                    vm.projects = results.data;
                });
            }
        }

        function maintenanceNotification(data)
        {
            if(!angular.isUndefined(data))
            {
                vm.Noty(data, "information", true, false, false);
            }
        }

    }


    /**
     *
     * Pass all functions into module
     */
    angular
        .module('app')
        .controller('MainCtrl ', MainCtrl);


~~


