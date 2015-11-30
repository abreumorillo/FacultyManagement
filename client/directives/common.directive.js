(function() {
    'use strict';

    angular
        .module('frdApp')
        .directive('ngEnter', directive);

    function directive() {
        return function(scope, element, attrs) {
            element.bind('keydown keypress', function  (event) {
                if(event.which === 13) { // Enter key
                    scope.$apply(function  () {
                        scope.$eval(attrs.ngEnter);
                    });
                    event.preventDefault();
                }
            });
        };
    }

})();