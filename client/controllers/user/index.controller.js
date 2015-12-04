(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserIndexController', UserIndexController);

    UserIndexController.$inject = ['UserService', '$state'];

    /* @ngInject */
    function UserIndexController(UserService, $state) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.users = [];

        //\/\/\/\\/ Functions
        vm.addUser = addUser;


        activate();

        ////////////////

        function activate() {
            console.log('UserIndexController');
        }

        /**
         * Get users
         * @return {array/object}
         */
        function getUsers() {
            AdminService.getUsers().then(function(response) {
                if (CommonService.isValidResponse(response)) {

                    vm.users = [];
                    vm.users = CommonService.getResponse(response);
                    vm.isUserAvailable = true;
                }
            }, function(errorResponse) {
                vm.isUserAvailable = false;
                console.log(errorResponse);
            });
        }

        function addUser () {
            $state.go('useradd');
        }
    }
})();
