(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('AdminService', AdminService);

    AdminService.$inject = ['$http'];

    /* @ngInject */
    function AdminService($http) {
        var service = {
            func: func
        };
        return service;

        ////////////////

        function func() {
        }
    }
})();