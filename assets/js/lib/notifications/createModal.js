(function(){
    'use strict';

    function CreateNotificationsModalController(notificationsCreateObject, $scope, $modalInstance, NotificationsHelpers, $rootScope, Restangular, $window, toaster, TokenService, NotificationsService)
    {

        var vm = this;
        vm.toaster = toaster;
        vm.Restangular = Restangular;
        vm.NotificationsService = NotificationsService;
        vm.TokenService = TokenService;
        vm.createNotificationsSuccess = createNotificationsSuccess;
        vm.createNotificationsError = createNotificationsError;
        vm.activate = activate;

        //Had trouble with vm and modal
        $scope.createNotifications = createNotifications;
        $scope.notificationsPayload = {};
        $scope.NotificationsHelpers = NotificationsHelpers;
        $scope.disableCreate = disableCreate;
        $scope.notificationsCreateObject = notificationsCreateObject;

        $scope.cancel = function() {
            $modalInstance.dismiss('cancel');
        }

        vm.activate();
        ////
        function activate()
        {
            //Set Users to payload
            //Get User Select list
            //Get Related Projects?
        }

        function disableCreate()
        {

        }

        function createNotifications()
        {
            vm.toaster.pop('info', 'Notice', 'Submitting new notifications hang in there');
            var token = vm.TokenService.get();
            vm.NotificationsService.create($scope.notificationsPayload, token, vm.createNotificationsSuccess, vm.createNotificationsError);
        }

        function createNotificationsSuccess(response)
        {
            vm.toaster.pop('success', 'Notice', 'Notifications created will add it to the list below');
            $modalInstance.close(response.data.notifications);
        }

        function createNotificationsError(error)
        {
            vm.toaster.pop('error', 'Notice', 'Error Saving Notifications :(, is the email unique?');
        }

    }

    angular.module('app')
        .controller('CreateNotificationsModalController', CreateNotificationsModalController);


})();