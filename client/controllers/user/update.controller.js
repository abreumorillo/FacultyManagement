(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserUpdateController', UserUpdateController);

    UserUpdateController.$inject = ['UserService', 'CommonService', '$state'];

    /* @ngInject */
    function UserUpdateController(UserService, CommonService, $state) {
        var vm = this;
        vm.title = 'UserUpdateController';

        //\/\/\/\\/ Public members /\//\/\\
        vm.user = {};
        vm.roles = [];
        activate();

        //////////////// Functions
        vm.cancelUpdate = cancelUpdate;
        vm.goBack = goBack;

        function activate() {
            getRoles();
        }

        function getRoles() {
            UserService.getRoles().then(function(successResponse) {
                if (successResponse.status === 200) {
                    vm.roles = successResponse.data;
                }
            }, handleErrorResponse);
        }

        function handleErrorResponse(error) {
            console.log(error);
        }

        function cancelUpdate (form) {
            console.log(form);
            vm.user = {};
            form.$setPristine();
            $state.go('userindex');
        }

                /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            $state.go(url);
        }

    }
})();
