(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['LoginService'];

    /* @ngInject */
    function LoginController(LoginService) {
        var vm = this;
        vm.title = 'LoginController';

        vm.isInvalid = isInvalid;

        activate();

        ////////////////

        function activate() {
            console.log('LoginController');
        }

        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function isInvalid(formElement) {
            return formElement.$dirty && formElement.$invalid;
        }
    }
})();
