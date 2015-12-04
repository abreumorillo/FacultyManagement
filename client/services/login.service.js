(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('LoginService', LoginService);

    LoginService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function LoginService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = 'server/controllers/logincontroller.php';
        var service = {
            login: login
        };
        return service;

        ////////////////

        function login() {
        }
    }
})();