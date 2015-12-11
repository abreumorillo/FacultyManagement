(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserUpdateController', UserUpdateController);

    UserUpdateController.$inject = ['UserService', 'CommonService', '$state', '$stateParams', '$q', 'toastr'];

    /* @ngInject */
    function UserUpdateController(UserService, CommonService, $state, $stateParams, $q, toastr) {
        var vm = this;
        vm.title = 'UserUpdateController';

        //\/\/\/\\/ Public members /\//\/\\
        vm.user = {};
        vm.isLoaded = false;
        vm.roles = [];
        activate();

        //////////////// Functions
        vm.cancelUpdate = cancelUpdate;
        vm.goBack = goBack;
        vm.isInvalid = isInvalid;
        vm.update = update;

        function activate() {
            $q.all([UserService.getRoles(), UserService.getById($stateParams.userId)])
                .then(function(data) {
                    handleRoles(data[0]);
                    handleUser(data[1]);
                    vm.isLoaded = true;
                }, handleErrorResponse);
        }

        function handleRoles(response) {
            if (response.status === 200) {
                vm.roles = response.data;
            }
        }

        function handleUser(response) {
            if (response.status === 200) {
                vm.user = response.data;
            }
        }

        function handleErrorResponse(error) {
            toastr.error('An error has occurred', error.status);
        }

        function cancelUpdate(form) {
            vm.user = {};
            form.$setPristine();
            $state.go('userindex');
        }

        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            $state.go(url);
        }
        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function isInvalid(formElement) {
            return formElement.$dirty && formElement.$invalid;
        }

        /**
         * Update the given user
         * @param  {[type]} form [description]
         * @return {[type]}      [description]
         */
        function update (form) {
            UserService.updateUser(vm.user).then(function  (successResponse) {
                if(successResponse.status === 204) {
                    form.$setPristine();
                    toastr.success('User updated successfully');
                    $state.go('userindex');
                }
            }, handleErrorResponse);
        }
    }
})();
