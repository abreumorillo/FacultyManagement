(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('AdminController', AdminController);

    AdminController.$inject = ['AdminService', 'CommonService', 'toastr', '$state'];

    /* @ngInject */
    function AdminController(AdminService, CommonService, toastr, $state) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\


        //\/\/\/\\/ Functions


        activate();

        ////////////////

        function activate() {

        }

        function function_name (argument) {
            // body...
        }

    }
})();
