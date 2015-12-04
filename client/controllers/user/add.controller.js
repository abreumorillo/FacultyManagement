(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserAddController', UserAddController);

    UserAddController.$inject = ['UserService', '$state'];

    /* @ngInject */
    function UserAddController(UserService, $state) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.newUser = {};
        vm.roles = [];
        vm.isCreatingUser = false;

        //\/\/\/\\/ Functions
        vm.goBack = goBack;
        vm.isInvalid = isInvalid;
        vm.clearInfo = clearInfo;
        vm.saveUser = saveUser;
        activate();

        ////////////////

        function activate() {
            console.log('UserAddController');
        }

        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function isInvalid(formElement) {
            return formElement.$dirty && formElement.$invalid;
        }

        function saveUser(form) {
            console.log(form);
            console.log(vm.user);
            return;
            UserService.saveUser(vm.user).then(function(successResponse) {
                console.log(successResponse);
            }, function(errorResponse) {
                console.log(errorResponse);
            });
        }
        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            $state.go(url);
        }

        function clearInfo(form) {
            vm.user = {};
            form.$setPristine();
        }
    }
})();
