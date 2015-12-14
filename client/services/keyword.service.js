(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('KeywordService', KeywordService);

    KeywordService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function KeywordService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = 'server/controllers/KeywordController.php';
        var service = {
            getAll: _getAll,
            getById: _getById,
            insertOrUpdate: _insertOrUpdate,
            deleteKeyword: _delete
        };
        return service;

        ////////////////

        /**
         * Get all keywords from the database
         * @return {promise}
         */
        function _getAll() {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'getAll'
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
         * Get all keywords from the database
         * @return {promise}
         */
        function _getById(keywordId) {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'getById', keywordId: keywordId
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
         * Update a given keyword in the database
         * @param  {object} keyword
         * @return {promise}
         */
        function _insertOrUpdate(keyword) {
            var deferred = $q.defer();
            if (keyword.id) {
                keyword.action = 'update';
            } else {
                keyword.action = 'insert';
            }

            var data = 'data=' + JSON.stringify(keyword);
            $http({
                method: 'POST',
                url: serviceUrl,
                data: data
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
         * @param  {int} keywordId Id of the user
         * @return {promise}
         */
        function _delete(keywordId) {
            var deferred = $q.defer();
            $http({
                method: 'DELETE',
                url: serviceUrl,
                params: {
                    keywordId: keywordId
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
