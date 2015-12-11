(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('UserIndexController', UserIndexController);

    UserIndexController.$inject = ['UserService', 'CommonService', '$state', '$uibModal', 'toastr'];

    /* @ngInject */
    function UserIndexController(UserService, CommonService, $state, $uibModal, toastr) {
        var vm = this;

        //\/\/\/\\/ Public members /\//\/\\
        vm.users = [];

        //\/\/\/\\/ Functions
        vm.addUser = addUser;
        vm.getRoleLabel = getRoleLabel;
        vm.removeUser = removeUser;

        activate();

        ////////////////
        /**
         * Activate the controller
         * @return {mix}
         */
        function activate() {
            getUsers();
        }

        /**
         * Get users
         * @return {array/object}
         */
        function getUsers() {
            UserService.getUsers().then(function(response) {
                if (CommonService.isValidResponse(response)) {
                    vm.users = [];
                    vm.users = CommonService.getResponse(response);
                    vm.isUserAvailable = true;
                }
            }, function(errorResponse) {
                vm.isUserAvailable = false;
                console.log(errorResponse);
            });
        }
        /**
         * Redirect to the add user page
         */
        function addUser() {
            $state.go('useradd');
        }

        /**
         * The custom label for the role
         * @param  {role} role user role
         * @return {string}      label css classes
         */
        function getRoleLabel(role) {
            switch (role) {
                case 'Admin':
                    return 'label label-danger';
                case 'Faculty':
                    return 'label label-info';
                case 'Student':
                    return 'label label-warning';
            }
        }

        /**
         * Remove an existing user from the database
         * @param  {int} userId
         * @return {mix}
         */
        function removeUser(user) {
            var modalInstance = $uibModal.open({
                animation: true,
                backdrop: 'static',
                templateUrl: 'client/views/modals/delete-user.html',
                controller: function($scope, $uibModalInstance, User) {
                    $scope.user = User;
                    $scope.ok = function() {
                        $uibModalInstance.close();
                    };
                    $scope.cancel = function() {
                        $uibModalInstance.dismiss('cancel');
                    };
                },
                size: 'xs',
                resolve: {
                    User: function() {
                        return user;
                    }
                }
            });

            modalInstance.result.then(function() {
                UserService.deleteUser(user.id).then(function(successResponse) {
                    if (successResponse.status === 204) {
                        toastr.success('The user has been deleted successfully');
                        var idx = vm.users.indexOf(user);
                        if (idx !== -1) {
                            vm.users.splice(idx, 1);
                        }
                    }
                }, function() {
                    toastr.error('An error has occurred while trying to delete the user');
                });
            }, function() {
                console.log('Modal dismissed at: ' + new Date());
            });
        }

    }
})();
