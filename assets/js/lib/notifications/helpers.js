(function(){
    'use strict';

    /**
     * You must setup the ENV in your config see the readme for this
     * @param ENV
     * @returns {{}}
     * @private
     */
    function NotificationsHelpers(ENV, NotificationsService, toaster)
    {
        var vm = this;
        vm.NotificationsService = NotificationsService;
        vm.toaster              = toaster;
        vm.items_to_mark_read   = [];
        vm.success              = success;
        vm.successRead          = successRead;
        vm.markReadScope        = markReadScope;
        vm.message_scope        = {};
        vm.error                = error;
        vm.token                = ENV.token;

        var NotificationsHelpers = {};
        NotificationsHelpers.showButton = showButton;
        NotificationsHelpers.isInRole = isInRole;
        NotificationsHelpers.markRead = markRead;

        ////

        function error(response)
        {
            vm.toaster.pop('error', 'Error', response.message);
        }

        function successRead(response)
        {
            vm.toaster.pop('info', 'Message', response.message);
            vm.markReadScope();
        }

        function markReadScope()
        {
            console.log(vm.items_to_mark_read);
                //indexOf was not consistent
                angular.forEach(vm.items_to_mark_read, function(v2,i2){
                        var found = false;
                        angular.forEach(vm.message_scope.response.notifications, function(v,i) {
                            if(!found)
                            {
                                if(v2 == v.id)
                                {
                                    vm.message_scope.response.notifications.splice(i, 1);
                                    found = true;
                                }
                            }
                        });
            });
            vm.message_scope.mark_read = [];
        }

        function success(response)
        {
            vm.toaster.pop('info', 'Message', response.message)
        }

        function markRead(items, scope)
        {
            vm.items_to_mark_read = items;
            vm.message_scope = scope;
            vm.NotificationsService.updateManyRead(items.join(','), vm.token, vm.successRead, vm.error);

        }

        function showButton()
        {
            var show = false;

            if(!angular.isUndefined(ENV.profile) && !angular.isUndefined(ENV.profile.roles))
            {
                angular.forEach(ENV.profile.roles, function(v,i){
                    if(v.id == 'role-admin')
                    {
                        show = true;
                    }
                });
            }
            return show;
        }

        function isInRole(role)
        {
            var show = false;
            if(!angular.isUndefined(ENV.profile) && !angular.isUndefined(ENV.profile.roles))
            {
                angular.forEach(ENV.profile.roles, function(v,i){
                    if(v.id == role)
                    {
                        show = true;
                    }
                });
            }
            return show;
        }

        return NotificationsHelpers;
    }

    angular.module('app')
        .factory('NotificationsHelpers', NotificationsHelpers);
})();
