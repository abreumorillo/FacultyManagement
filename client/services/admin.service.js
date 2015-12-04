(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('AdminService', AdminService);

    AdminService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function AdminService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = 'server/controllers/admincontroller.php';
        var service = {
            getUsers: _getUsers,
            getRoles: _getRoles
        };
        return service;

        ////////////////

        /**
         * Get the users
         * @return {promise}
         */
        function _getUsers() {
            var deferred = $q.defer();

            $http({
                    method: 'GET',
                    url: serviceUrl,
                    params: {
                        action: 'getUsers'
                    }
                })
                .success(function(data, status) {
                    deferred.resolve({
                        data: data,
                        status: status
                    });
                }).
            error(function(error, status) {
                deferred.reject({
                    error: error,
                    status: status
                });
            });

            return deferred.promise;
        }

        /**
         * Get the roles
         * @return {promise}
         */
        function _getRoles() {
            var deferred = $q.defer();

            $http({
                    method: 'GET',
                    url: serviceUrl,
                    params: {
                        action: 'getRoles'
                    }
                })
                .success(function(data, status) {
                    deferred.resolve({
                        data: data,
                        status: status
                    });
                })
                .error(function(error, status) {
                    deferred.reject({
                        error: error,
                        status: status
                    });
                });

            return deferred.promise;
        }

        function saveUser(user) {
            var deferred = $q.defer();
            //user.action = "addProduct";
            var postData = 'data=' + JSON.stringify(user);
            $http({
                method: 'POST',
                url: serviceUrl,
                data: postData
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function(data, status) {
                deferred.reject({
                    data: data,
                    status: status
                });
            });
            return deferred.promise;
        }
    }
})();
