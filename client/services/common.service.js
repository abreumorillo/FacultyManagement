(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('CommonService', CommonService);

    CommonService.$inject = ['$log'];

    /* @ngInject */
    function CommonService($log) {
        var service = {
            isValidResponse: _isValidResponse,
            getResponse: _getResponse
        };
        return service;

        ////////////////

        function _isValidResponse(response) {
            return (response.status === 200 && (angular.isObject(response.data) || angular.isArray(response.data)));
        }

        function _getResponse(response) {
            var result = [];
            if (angular.isArray(response.data)) {
                result = response.data;
            } else {
                result.push(response.data);
            }
            return result;
        }

    }
})();
