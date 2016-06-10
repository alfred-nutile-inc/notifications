<?php



Route::get('notifications', 'AlfredNutileInc\Notifications\NotificationsController@index');
Route::get('notifications/{notice_id}', 'AlfredNutileInc\Notifications\NotificationsController@getNotification');
Route::put('notifications/{notice_id}', 'AlfredNutileInc\Notifications\NotificationsController@putNotification');
Route::put('notifications/read/{notice_id}', 'AlfredNutileInc\Notifications\NotificationsController@putNotificationRead');
