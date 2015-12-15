(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['AuthService', 'CommonService', 'toastr', '$rootScope'];

    /* @ngInject */
    function LoginController(AuthService, CommonService, toastr, $rootScope) {
        var vm = this;

        //\/\\/\/\/\/\/\/\/\ BINDABLE MEMBERS /\/\/\\/\/\/\/
        vm.user = {};
        vm.errors = [];

        //\/\/\\/\\/\/\/\/\\/ GLOBAL VARIABLES /\\/\/\/\/\//\/
        $rootScope.isAuthenticated = false;
        $rootScope.username = '';
        $rootScope.role = '';
        $rootScope.logOut = logOut;

        //\/\/\\/\\/\/\/\/\/\\/\/\\/\/ FUNCTIONS /\/\\/\/\/\/\
        vm.isInvalid = CommonService.isInvalidFormElement;
        vm.login = login;
        activate();

        ////////////////

        function activate() {}

        function logOut() {
            AuthService.logOut().then(function(successResponse) {
                if (CommonService.isValidResponse(successResponse)) {
                    $rootScope.isAuthenticated = false;
                    $rootScope.username = '';
                    $rootScope.role = '';
                    CommonService.goToUrl('login');
                }
            }, notifyError);
        }

        function login() {
            AuthService.login(vm.user).then(function(successResponse) {
                if (CommonService.isValidResponse(successResponse)) {
                    //set global varibles
                    var data = successResponse.data;
                    $rootScope.isAuthenticated = data.isAuthenticated;
                    $rootScope.username = data.username;
                    $rootScope.role = data.role;
                    CommonService.goToUrl('admin.index');
                }
            }, notifyError);
        }

        /**
         * Notify errors to the user using toaster component
         * @param data
         */
        function notifyError(data) {
            var errors = "";
            if (data.status === 422) {
                angular.forEach(data.error, function(item) {
                    vm.errors.push(item);
                    errors += item + '<br/>';
                });
            } else {
                errors  ="Incorrect username or password";
                vm.errors.push(errors);
            }
            toastr.error(errors, 'Errors had occurred');
        }

    }
})();
