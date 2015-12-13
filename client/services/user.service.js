(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('UserService', UserService);

    UserService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function UserService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = 'server/controllers/UserManagementController.php';
        var service = {
            getUsers: _getUsers,
            getRoles: _getRoles,
            saveUser: _saveUser,
            getById: _getById,
            deleteUser: _deleteUser,
            updateUser: _updateUser,
            getFacultiesList: _getFacultiesList
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
         * Get an user given its id
         * @param  {in} userId Id of the user
         * @return {promise}
         */
        function _getById(userId) { //getUserById
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'getUserById',
                    userId: userId
                }
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function(error, status) {
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

        /**
         * Save a user
         * @param  {object} user user information
         * @return {promise}
         */
        function _saveUser(user) {
            var deferred = $q.defer();
            //user.action = "addProduct";
            user.action = 'insert';
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

        /**
         * Update a given user in the database
         * @param  {object} user
         * @return {promise}
         */
        function _updateUser(user) {
            var deferred = $q.defer();
            user.action = 'update';
            var putData = 'data=' + JSON.stringify(user);
            $http({
                method: 'POST',
                url: serviceUrl,
                data: putData
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function(error, status) {
                deferred.reject({
                    error: error,
                    status: status
                });
            });
            return deferred.promise;
        }

        /**
         * Delete a user from the database
         * @param  {int} userId Id of the user
         * @return {promise}
         */
        function _deleteUser(userId) {
            var deferred = $q.defer();
            $http({
                method: 'DELETE',
                url: serviceUrl,
                params: {
                    userId: userId
                }
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function(error, status) {
                deferred.reject({
                    error: error,
                    status: status
                });
            });
            return deferred.promise;
        }

        function _getFacultiesList() {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'getFacultiesList'
                }
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function(error, status) {
                deferred.reject({
                    error: error,
                    status: status
                });
            });
            return deferred.promise;
        }
    }
})();
