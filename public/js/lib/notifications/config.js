(function(){
    'use strict';

    function NotificationsConfig($stateProvider)
    {
        $stateProvider
            .state('notifications', {
                abstract: true,
                url: '/notifications',
                templateUrl: "/js/lib/notifications/templates/index.html"
            })
            .state('notifications.messages', {
                url: '',
                views: {
                    '': {
                        templateUrl: 'js/lib/notifications/templates/messages.html',
                        resolve: {
                            notifications: ['NotificationsService', function(NotificationsService){
                                return NotificationsService.getIndex();
                            }]
                        },
                        controller: 'NotificationsIndexController',
                        controllerAs: 'vm'
                    }
                },
                data: {
                    pageTitle: "Notifications All"
                }

            });

    }

    angular
        .module('app')
        .config(NotificationsConfig)

})();