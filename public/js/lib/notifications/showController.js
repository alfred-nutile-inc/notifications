(function(){
    'use strict';

    function NotificationsShowController(notifications, NotificationsService, toaster, $state)
    {
        var vm = this;

        vm.NotificationsService    = NotificationsService;
        vm.activate                      = activate;
        vm.notifications           = notifications.data.notifications;
        vm.toaster                       = toaster;

        activate();

        /////
        function activate()
        {
            vm.toaster.pop('info', 'Notice', 'Getting notifications. But if you do not have permission' +
            'do not be suprised to see nothing :( ');

        }
    }

    angular.module('app')
        .controller("NotificationsShowController", NotificationsShowController);

})();