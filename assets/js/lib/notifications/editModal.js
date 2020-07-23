(function(){
    'use strict';

    function EditNotificationsModalController(notificationsEditObject, parentScope, $scope, $modalInstance, NotificationsHelpers, $rootScope, Restangular, $window, toaster, TokenService, NotificationsService)
    {

        var editVm = this;
        $scope.editVm = {};
        editVm.toaster = toaster;
        editVm.Restangular = Restangular;
        editVm.NotificationsService = NotificationsService;
        editVm.TokenService = TokenService;
        editVm.editNotificationsSuccess = editNotificationsSuccess;
        editVm.editNotificationsError = editNotificationsError;
        editVm.updateScopeList = updateScopeList;
        editVm.activate = activate;
        editVm.updateNotifications = updateNotifications;
        editVm.notificationsPayload = {}; //make a payload from the incoming data object
        editVm.userList = []; //make a payload from the incoming data object
        editVm.NotificationsHelpers = NotificationsHelpers;
        editVm.notificationsEditObject = notificationsEditObject;
        editVm.showClose = 'false';

        $scope.cancel = function() {
            $modalInstance.dismiss('cancel');
        };

        editVm.activate();
        ////


        function activate()
        {
            $scope.editVm = editVm;
            $scope.editVm.related_users_selected = [];
            $scope.editVm.roles = notificationsEditObject.roles;
            $scope.editVm.teams = notificationsEditObject.teams;
            $scope.editVm.notificationsPayload = notificationsEditObject.data.notifications;
        }

        function updateNotifications()
        {
            editVm.showClose = 'true';
            editVm.toaster.pop('info', 'Notice', 'Submitting updates to notifications hang in there');
            editVm.NotificationsService.update($scope.editVm.notificationsPayload, token, editVm.editNotificationsSuccess, editVm.editNotificationsError);
            editVm.updateScopeList(editVm.notificationsPayload);
        }

        function updateScopeList(new_notifications)
        {
            var update_notifications_in_parent_list = angular.copy(new_notifications);
            angular.forEach(parentScope.response.notifications, function(v, i)
            {
               if(v.id == update_notifications_in_parent_list.id)
               {
                   parentScope.response.notifications[i] = new_notifications; //Comes back full from the API?
               }
            });
        }

        function editNotificationsSuccess(response)
        {
            editVm.toaster.pop('success', 'Notice', 'Notifications updated!');
        }

        function editNotificationsError(error)
        {
            editVm.toaster.pop('error', 'Notice', 'Error Saving Notifications :(');
        }

    }

    angular.module('app')
        .controller('EditNotificationsModalController', EditNotificationsModalController);


})();