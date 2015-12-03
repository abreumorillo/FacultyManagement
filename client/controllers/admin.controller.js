(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('AdminController', AdminController);

    AdminController.$inject = ['AdminService', 'CommonService', 'toastr', '$state'];

    /* @ngInject */
    function AdminController(AdminService, CommonService, toastr, $state) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.users = [];
        vm.newUser = {};
        vm.roles = [];
        vm.isUserAvailable = false;
        vm.isCreatingUser = false;
        vm.goBack = goBack;

        //\/\/\/\\/ Functions
        vm.createNewUser = createNewUser;
        vm.clearInfo = clearInfo;

        activate();

        ////////////////

        function activate() {
            toastr.info('activated');
            //getUsers();
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

        /**
         * Get roles
         * @return {array/object}
         */
        function getRoles() {

            AdminService.getRoles().then(function(response) {
                if (CommonService.isValidResponse(response)) {
                    vm.roles = CommonService.getResponse(response);
                }

            }, function(errorResponse) {
                console.log(errorResponse);
            });
        }

        /**
         * Create new user function
         * @return {mix}
         */
        function createNewUser() {
            vm.isCreatingUser = true;
            getRoles();
        }

        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            switch (url) {
                case 'admin':
                    vm.isCreatingUser = false;
                    vm.newUser = {};
                    break;
                case 'test':
                    console.log(url);
                    break;
            }
        }

        function clearInfo (form) {
           // vm.user = {};
            form.$setPristine();
            console.log(vm.user);
        }
    }
})();
