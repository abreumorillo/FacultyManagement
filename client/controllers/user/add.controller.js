(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserAddController', UserAddController);

    UserAddController.$inject = ['UserService', '$state', 'CommonService', 'toastr'];

    /* @ngInject */
    function UserAddController(UserService, $state, CommonService, toastr) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.user = {};
        vm.roles = [];
        vm.errors = [];
        vm.isCreatingUser = false;

        //\/\/\/\\/ Functions
        vm.goBack = goBack;
        vm.isInvalid = isInvalid;
        vm.clearInfo = clearInfo;
        vm.saveUser = saveUser;
        activate();

        ////////////////

        function activate() {
            getRoles();
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
            UserService.saveUser(vm.user).then(function(successResponse) {
                if(successResponse.status === 201){
                    vm.user={};
                    form.$setPristine();
                    $state.go('userindex');
                }
            }, function(errorResponse) {
                if (errorResponse.status === 422) { //server side validation error
                    vm.errors = errorResponse.data;
                    var msg = "";
                    angular.forEach(vm.errors, function(data) {
                        msg += data + '<br>';
                    });
                    toastr.error(msg, 'Validation Error');
                } else {
                    toastr.error('An error has occurred while processing the data');
                }
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

        function getRoles() {
            UserService.getRoles().then(function(successResponse) {
                if (successResponse.status === 200) {
                    vm.roles = successResponse.data;
                }
            }, handleErrorResponse);
        }

        function handleErrorResponse(error) {
            console.log(error);
        }
    }
})();
