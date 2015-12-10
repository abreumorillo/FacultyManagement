(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserIndexController', UserIndexController);

    UserIndexController.$inject = ['UserService', 'CommonService', '$state'];

    /* @ngInject */
    function UserIndexController(UserService, CommonService, $state) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.users = [];

        //\/\/\/\\/ Functions
        vm.addUser = addUser;
        vm.getRoleLabel = getRoleLabel;

        activate();

        ////////////////

        function activate() {
            getUsers();
        }

        /**
         * Get users
         * @return {array/object}
         */
        function getUsers() {
            UserService.getUsers().then(function(response) {
                if (CommonService.isValidResponse(response)) {
                    vm.users = [];
                    vm.users = CommonService.getResponse(response);
                    vm.isUserAvailable = true;
                    console.log(vm.users);
                }
            }, function(errorResponse) {
                vm.isUserAvailable = false;
                console.log(errorResponse);
            });
        }

        function addUser() {
            $state.go('useradd');
        }

        function getRoleLabel(role) {
            switch (role) {
                case 'Admin':
                    return 'label label-danger';
                case 'Faculty':
                    return 'label label-info';
                case 'Student':
                    return 'label label-warning';
            }
        }
        vm.test = function  (index) {
            console.log('idx', index);
        };
    }
})();
