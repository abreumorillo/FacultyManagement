(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'IndexService','CommonService', 'toastr'];

    /* @ngInject */
    function IndexController($scope, IndexService, CommonService, toastr) {
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
        vm.isSearching = false;

        //FUNCTIONS
        vm.closeSearch = closeSearch;
        vm.getKeywordLabel = getKeywordLabel;

        vm.closeSearch = function() {
            vm.isSearchResult = false;
        };

        activate();

        ////////////////

        function activate() {
            // toastr.info('Index Controller Activated');
        }

        /**
         * Execute the search
         * @return {array}
         */
        function search() {

            if (vm.searchTerm.length <= 0) {
                return;
            }
            vm.isSearching = true;
            IndexService.searchPaper(vm.searchTerm, vm.currentPage, vm.itemPerPage).then(function(response) {
                vm.isSearching = false;
                if(response.status === CommonService.statusCode.HTTP_NO_CONTENT) {
                    toastr.info('No paper in the database');
                    vm.papers = [];
                }
                if (CommonService.isValidResponse(response)) {
                    vm.papers = [];
                    vm.papers = CommonService.getResponse(response);
                    vm.isSearchResult = true;
                    console.log(vm.papers);
                } else {
                    toastr.warning("No paper found that maches " + vm.searchTerm);
                }

            }, function(errorResponse) {
                toastr.warning("No paper found that maches " + vm.searchTerm);
                vm.isSearching = false;
            });
        }

        /**
         * Close a search
         * @return {[type]} [description]
         */
        function closeSearch() {
            vm.papers = [];
            vm.isSearchResult = false;
        }

        /**
         * Get label for the keywords
         * @param  {string} keyword
         * @return {bootstrap class}
         */
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
