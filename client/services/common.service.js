(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('CommonService', CommonService);

    CommonService.$inject = ['$log'];

    /* @ngInject */
    function CommonService($log) {
        var statusCode = {
            'HTTP_OK': 200,
            'HTTP_NO_CONTENT': 204,
            'HTTP_NOT_FOUND': 404,
            'HTTP_VALIDATION_ERROR': 422,
            'HTTP_CREATED': 201
        };
        var service = {
            isValidResponse: _isValidResponse,
            getResponse: _getResponse,
            statusCode: statusCode
        };
        return service;

        ////////////////

        function _isValidResponse(response) {
            return (response.status === statusCode.HTTP_OK && (angular.isObject(response.data) || angular.isArray(response.data)));
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
