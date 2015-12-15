(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('AdminController', AdminController);

    AdminController.$inject = ['AdminService', 'CommonService', 'toastr', '$state', 'AuthService'];

    /* @ngInject */
    function AdminController(AdminService, CommonService, toastr, $state, AuthService) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.isLoaded = false;

        //\/\/\/\\/ Functions


        activate();

        ////////////////

        function activate() {
            if(AuthService.isManager()) {
                CommonService.goToUrl('admin.index');
            }
            if(!AuthService.isAuthenticated()) {
                CommonService.goToUrl('login');
            } else {
                if(!AuthService.isManager()) {
                    CommonService.goToUrl('index');
                }
            }
            vm.isLoaded = true;
        }

        function function_name (argument) {
            // body...
        }

    }
})();
