(function() {
    'use strict';

    angular
        .module('frdApp')
        .factory('CommonService', CommonService);

    CommonService.$inject = ['$log', '$state'];

    /* @ngInject */
    function CommonService($log, $state) {
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
            statusCode: statusCode,
            getKeywordLabel: _getKeywordLabel,
            isInvalidFormElement: _isInvalidFormElement,
            goToUrl: _goToUrl
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

                /**
         * Get label for the keywords
         * @param  {string} keyword
         * @return {bootstrap class}
         */
        function _getKeywordLabel(keyword) {
            keyword = keyword.toLowerCase();
            switch (keyword) {
                case 'course assignment':
                case 'department management':
                case 'faculty':
                case 'tools':
                case 'education':
                case 'is fluency':
                    return 'label label-info';
                case 'web 2.0':
                case 'department management':
                case 'web services':
                case 'restful':
                case 'web api':
                    return 'label label-primary';
                case 'php':
                case 'c#':
                case 'java':
                case 'javascript':
                case 'xml':
                case 'html':
                    return 'label label-warning';
                case 'tomcat':
                case 'iis':
                    return 'label label-danger';
                case 'database':
                case 'data mining':
                case 'informatics':
                    return 'label label-success';
                default:
                    return 'label label-default';
            }
        }

        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function _isInvalidFormElement (formElement) {
            return formElement.$dirty && formElement.$invalid;
        }
        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function _goToUrl(url) {
            console.log(url);
            $state.go(url);
        }

    }
})();
