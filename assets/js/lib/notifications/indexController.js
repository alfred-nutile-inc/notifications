(function(){
    'use strict';

    function NotificationsIndexController(notifications, $modal, NotificationsHelpers, NotificationsService, toaster)
    {
        var vm = this;

        vm.NotificationsService                = NotificationsService;
        vm.NotificationsHelpers                = NotificationsHelpers;
        vm.$modal                                    = $modal;
        vm.editNotificationsModal              = editNotificationsModal;
        vm.showCreateNotificationsModal        = showCreateNotificationsModal;
        vm.activate                                  = activate;
        vm.response                                  = notifications.data;
        vm.toaster                                   = toaster;


        activate();

        /////
        function activate()
        {
            vm.toaster.pop('info', 'Notice', 'Notifications Loaded for your permission level');
        }

        function showCreateNotificationsModal()
        {
            var modalInstance = vm.$modal.open({
                templateUrl: 'js/lib/notifications/templates/_create.html',
                controller: 'CreateNotificationsModalController',
                size: 'lg',
                resolve: {
                    notificationsCreateObject: function()
                    {
                        return vm.NotificationsService.getCreateObject();
                    }
                }
            });

            modalInstance.result.then(function(notifications){
                //
                vm.response.notifications.push(notifications);
            }, function(){
                //
            });
        }

        function editNotificationsModal(notifications)
        {
            var modalInstance = vm.$modal.open({
                templateUrl: 'js/lib/notifications/templates/_edit.html',
                controller: 'EditNotificationsModalController',
                controllerAs: 'editVm',
                size: 'lg',
                resolve: {
                    notificationsEditObject: function()
                    {
                        var results = vm.NotificationsService.getOne(notifications.id);
                        return results;
                    },
                    parentScope: function()
                    {
                        return vm;
                    }
                }
            });

            modalInstance.result.then(function(notifications){
                //
            }, function(){
                //
            });
        }
    }

    angular.module('app')
        .controller("NotificationsIndexController", NotificationsIndexController);

})();