(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('KeywordController', KeywordController);

    KeywordController.$inject = ['KeywordService', 'CommonService', 'toastr', '$uibModal', '$timeout', '$state'];

    /* @ngInject */
    function KeywordController(KeywordService, CommonService, toastr, $uibModal, $timeout, $state) {
        var vm = this;

        activate();

        //\\/\/\/\/\ BINDABLE MEMBERS
        vm.keywords = [];
        vm.keyword = {};
        vm.isSaving = false;
        vm.isUpdating = false;
        vm.isDeleting = false;
        vm.isLoaded = false;
        vm.actionDescription = "";

        //Pagination options
        vm.totalItems = 0;
        vm.currentPage = 1;
        vm.itemPerPage = 8;
        vm.isPaging = false;

        ///FUNCTIONS
        vm.updateKeyword = updateKeyword;
        vm.newKeyword = newKeyword;
        vm.saveKeyword = saveKeyword;
        vm.deleteKeyword = deleteKeyword;
        vm.showActionDescription = showActionDescription;
        vm.paginate = paginate;
        vm.pageChanged = pageChanged;


        /**
         * Activate function
         * @return {mix}
         */
        function activate() {
            getAll();
        }

        /**
         * Get all the  keywords
         * @return {array}
         */
        function getAll() {
            KeywordService.getAll().then(function(successResponse) {
                if (successResponse.status === 204) {
                    toastr.warning('There is not keyword in the database');
                    vm.keywords = [];
                } else if (CommonService.isValidResponse(successResponse)) {
                    vm.keywords = [];
                    vm.keywords = CommonService.getResponse(successResponse);
                    vm.totalItems = vm.keywords.length;
                }
                vm.isLoaded = true;
            }, handleErrorResponse);
        }

        /**
         * Handle error response
         * @param  {object} error  object
         * @return {mix}
         */
        function handleErrorResponse(error) {
            console.log(error);
            toastr.error('An error has occurred', "Code: " + error.status);
        }

        /**
         * Create a new keyword
         * @return {mix}
         */
        function newKeyword() {
            vm.keyword = {};
            var saveModal = $uibModal.open({
                animation: true,
                backdrop: 'static',
                templateUrl: 'client/views/modals/create-update-keyword.html',
                controller: function($scope, $uibModalInstance, Keyword) {
                    $scope.keyword = Keyword;
                    $scope.ok = function() {
                        $uibModalInstance.close($scope.keyword);
                    };
                    $scope.cancel = function() {
                        $uibModalInstance.dismiss('cancel');
                    };
                },
                size: 'xs',
                resolve: {
                    Keyword: function() {
                        return vm.keyword;
                    }
                }
            });

            saveModal.result.then(function(keyword) {
                KeywordService.insertOrUpdate(keyword).then(function(successResponse) {
                    if (successResponse.status === CommonService.statusCode.HTTP_CREATED) {
                        vm.keywords.push(keyword);
                        $state.reload();
                        toastr.success('Keyword added successfully');
                    }
                }, handleErrorResponse);
            });
        }

        /**
         * Save a keyword to the database
         * @param  {form elemenet} form
         * @return {mix}
         */
        function saveKeyword(form) {
            console.log('save', form);
        }

        /**
         * Udate a keyword
         * @param  {form elemenet} form
         * @return {mix}
         */
        function updateKeyword(keyword) {
            var updateModal = $uibModal.open({
                animation: true,
                backdrop: 'static',
                templateUrl: 'client/views/modals/create-update-keyword.html',
                controller: function($scope, $uibModalInstance, Keyword) {
                    $scope.keyword = Keyword;
                    $scope.ok = function() {
                        $uibModalInstance.close($scope.keyword);
                    };
                    $scope.cancel = function() {
                        $uibModalInstance.dismiss('cancel');
                    };
                },
                size: 'xs',
                resolve: {
                    Keyword: function() {
                        return keyword;
                    }
                }
            });

            updateModal.result.then(function(keyword) {
                KeywordService.insertOrUpdate(keyword).then(function(successResponse) {
                    console.log(successResponse);
                    if (successResponse.status === CommonService.statusCode.HTTP_NO_CONTENT) {
                        var idx = vm.keywords.indexOf(keyword);
                        if (idx !== -1) {
                            vm.keywords[idx].description = keyword.description;
                            $state.reload();
                        }
                    }
                }, handleErrorResponse);
            });
        }

        /**
         * Delete a keyword from the database
         * @param  {int} keywordId
         * @return {mix}
         */
        function deleteKeyword(keyword) {
            KeywordService.deleteKeyword(keyword.id).then(function(successResponse) {
                if (successResponse.status == CommonService.statusCode.HTTP_NO_CONTENT) {
                    var idx = vm.keywords.indexOf(keyword);
                    if (idx !== -1) {
                        vm.keywords.splice(idx, 1);
                    }
                }
            }, handleErrorResponse);
        }

        /**
         * Show description for a given action
         * @param  {string} action  action edit|delete
         * @param  {object} keyword
         * @return {string}         =
         */
        function showActionDescription(action, keyword) {
            if (angular.isUndefined(keyword)) {
                vm.actionDescription = "";
            } else {
                if (action === 'edit') {
                    vm.actionDescription = "Edit: " + keyword.description;
                } else {
                    vm.actionDescription = "Delete: " + keyword.description;
                }
            }
        }

        /***
         * Function execute every time we interact with the pagination control
         */
        function pageChanged() {
            vm.isPaging = true;
            $timeout(function() {
                vm.isPaging = false;
            }, 800);
        }

        function paginate(value) {
            var begin, end, index;
            begin = (vm.currentPage - 1) * vm.itemPerPage;
            end = begin + vm.itemPerPage;
            index = vm.keywords.indexOf(value);
            return (begin <= index && index < end);
        }

    }
})();
