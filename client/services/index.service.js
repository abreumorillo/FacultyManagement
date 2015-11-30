(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('IndexService', IndexService);

    IndexService.$inject = ['$http', '$q', 'appConfig'];

    /* @ngInject */
    function IndexService($http, $q, appConfig) {
        var serviceUrl = appConfig.baseUrl = 'server/controllers/papercontroller.php';
        var service = {
            searchPaper: searchPaper
        };
        return service;

        ////////////////

        function searchPaper(searchTerm, page, itemPerPage) {
            var deferred = $q.defer();

            $http({
                    method: 'GET',
                    url: serviceUrl,
                    params: {action: 'getPapers', searchTerm: searchTerm, page: page, itemPerPage: itemPerPage}
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
    }
})();
