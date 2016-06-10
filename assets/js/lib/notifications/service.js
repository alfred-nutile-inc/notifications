(function(){
    'use strict';
    function NotificationsService(Restangular)
    {
        var vm = this;
        vm.Restangular = Restangular;

        return {
            getIndex    : getIndex,
            getOne      : getOne,
            getCreateObject      : getCreateObject,
            update      : update,
            updateManyRead      : updateManyRead,
            create      : create
        };

        ////
        function getIndex()
        {
            return Restangular.one('api/v1/notifications').get().then(
                success,
                fail
            );
        }

        function getCreateObject()
        {
            return {};
        }

        function create(notificationsPayload, token, successCallback, errorCallback)
        {
            var token = token;
            var notificationsPayload = notificationsPayload;

            var rest = Restangular
                .all('api/v1/notifications');
            rest._token = token;
            rest.post({ "data": notificationsPayload }).then(
                successCallback,
                errorCallback
            );

        }

        function updateManyRead(notificationsPayload, token, successCallback, errorCallback) {
            var rest = vm.Restangular.one('api/v1/notifications/read', notificationsPayload);
            rest._token = token;
            rest.data   = notificationsPayload;
            rest.put().then(
                successCallback,
                errorCallback
            );
        }

        function update(notificationsPayload, token, successCallback, errorCallback) {
            var rest = vm.Restangular.one('api/v1/notifications', notificationsPayload.id);
            rest._token = token;
            rest.data   = notificationsPayload;
            rest.put().then(
                successCallback,
                errorCallback
            );
        }

        function getOne(uuid)
        {
            return Restangular.one('api/v1/notifications', uuid).get().then(
                success,
                fail
            );
        }

        function success(response) {
            return response;
        }

        function fail(response) {
            return response;
        }
    }

    angular.module('app')
        .service('NotificationsService', NotificationsService);
})();
