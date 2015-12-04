(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserUpdateController', UserUpdateController);

    UserUpdateController.$inject = ['UserService'];

    /* @ngInject */
    function UserUpdateController(UserService) {
        var vm = this;
        vm.title = 'UserUpdateController';

        activate();

        ////////////////

        function activate() {
            console.log('UserUpdateController');
        }
    }
})();