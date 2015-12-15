/**
 * Created by sabreu on 6/9/2015.
 */
(function() {
    "use strict";
    angular.module('frdApp').filter('paperFilter', function() {
        return function(data, userInfo, attribute) {
            var shownItems = [];
            if (userInfo.role === 'Admin') {
                shownItems = data;
                return shownItems;
            }
            angular.forEach(data, function(item) {
                if (item[attribute] === userInfo.username) {
                    shownItems.push(item);
                }
            });
            return shownItems;
        };
    });
})();
