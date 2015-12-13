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
            count: _count
        };
        return service;

        ////////////////

        function _get(searchTerm, page, itemPerPage) {
            var deferred = $q.defer();
            $http({
                    method: 'GET',
                    url: serviceUrl,
                    params: {
                        action: 'getPaper',
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
            }).success(function (data, status) {
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
    }
})();
