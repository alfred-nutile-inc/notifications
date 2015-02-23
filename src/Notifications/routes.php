<?php



Route::get('notifications', 'AlfredNutileInc\CoreApp\Notifications\NotificationsController@index');
Route::get('notifications/{notice_id}', 'AlfredNutileInc\CoreApp\Notifications\NotificationsController@getNotification');
Route::put('notifications/{notice_id}', 'AlfredNutileInc\CoreApp\Notifications\NotificationsController@putNotification');
Route::put('notifications/read/{notice_id}', 'AlfredNutileInc\CoreApp\Notifications\NotificationsController@putNotificationRead');
