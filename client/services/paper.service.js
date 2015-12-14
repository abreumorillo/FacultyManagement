(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('PaperService', PaperService);

    PaperService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function PaperService($http, $q, appConfig) {

        var serviceUrl = appConfig.baseUrl = 'server/controllers/papercontroller.php';
        var service = {
            getPaper: _get,
            count: _count,
            insertOrUpdate: _insertOrUpdate,
            delete: _delete,
            getById: _getById
        };
        return service;

        ////////////////

        /**
         * Get papers that match a given condition
         * @param  {string} searchTerm
         * @param  {int} page
         * @param  {int} itemPerPage
         * @return {promise}
         */
        function _get(searchTerm, page, itemPerPage) {
            var deferred = $q.defer();
            $http({
                    method: 'GET',
                    url: serviceUrl,
                    params: {
                        action: 'getPapers',
                        searchTerm: searchTerm,
                        page: page,
                        itemPerPage: itemPerPage
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
         * Count the number of record in the database
         * @param  {string} searchTerm
         * @return {promise}
         */
        function _count(searchTerm) {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'count',
                    searchTerm: searchTerm
                }
            }).success(function(data, status) {
                deferred.resolve({
                    data: data,
                    status: status
                });
            }).error(function function_name(error, status) {
                deferred.reject({
                    error: error,
                    status: status
                });
            });
            return deferred.promise;
        }

        /**
         * Update a given paper in the database
         * @param  {object} paper
         * @return {promise}
         */
        function _insertOrUpdate(paper) {
            var deferred = $q.defer();
            if (paper.id) {
                paper.action = 'update';
            } else {
                paper.action = 'insert';
            }
            var data = 'data=' + JSON.stringify(paper);
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
         * Delete a paper from the database
         * @param  {int} paperId Id of the user
         * @return {promise}
         */
        function _delete(paperId) {
            var deferred = $q.defer();
            $http({
                method: 'DELETE',
                url: serviceUrl,
                params: {
                    paperId: paperId
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
         * Get a paper by id from the database
         * @return {promise}
         */
        function _getById(paperId) {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: serviceUrl,
                params: {
                    action: 'getById',
                    paperId: paperId
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
