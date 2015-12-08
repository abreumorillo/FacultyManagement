(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'IndexService', 'appConfig', 'toastr'];

    /* @ngInject */
    function IndexController($scope, IndexService, appConfig, toastr) {
        var vm = this;
        vm.title = 'IndexController';
        vm.searchTerm = "";
        vm.search = search;
        //Pagination options
        vm.totalItems = 0;
        vm.currentPage = 1;
        vm.itemPerPage = 10;
        vm.isSearchResult = false;
        vm.papers = [];

        //FUNCTIONS
        vm.closeSearch = closeSearch;
        vm.getKeywordLabel = getKeywordLabel;

        vm.closeSearch = function() {
            vm.isSearchResult = false;
        };

        activate();

        ////////////////

        function activate() {
            console.log('Index Controller Activated');
        }

        function search() {

            if (vm.searchTerm.length <= 0) {
                return;
            }

            IndexService.searchPaper(vm.searchTerm, vm.currentPage, vm.itemPerPage).then(function(response) {

                if (response.status === 200 && (angular.isObject(response.data)) || angular.isArray(response.data)) {
                    vm.papers = [];

                    if (angular.isArray(response.data)) {
                        vm.papers = response.data;
                    } else {
                        vm.papers.push(response.data);
                    }

                    vm.isSearchResult = true;
                    console.log(vm.papers);
                } else {
                    toastr.warning("No paper found that maches " + vm.searchTerm);
                }

            }, function(errorResponse) {
                toastr.warning("No paper found that maches " + vm.searchTerm);
            });
        }

        function closeSearch() {
            vm.papers = [];
            vm.isSearchResult = false;
        }

        function getKeywordLabel(keyword) {
            switch (keyword) {
                case 'course assignment':
                case 'department management':
                case 'faculty':
                case 'tools':
                case 'education':
                case 'IT fluency':
                    return 'label label-info';
                case 'Web 2.0':
                case 'department management':
                case 'web services':
                case 'XML':
                    return 'label label-primary';
                case 'PHP':
                case 'C#':
                case 'Java':
                    return 'label label-warning';
                case 'Tomcat':
                case 'IIS':
                    return 'label label-danger';
                case 'database':
                case 'data mining':
                case 'informatics':
                    return 'label label-success';
                default:
                    return 'label label-default';

            }
            //return 'label label-info';
        }
    }
})();
