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
        vm.itemPerPage = 2;
        vm.isSearchResult = false;
        vm.papers = [];
        vm.isSearching = false;
        vm.isShowingDetails = false;
        vm.paperDetails = {};

        //FUNCTIONS
        vm.closeSearch = closeSearch;
        vm.getKeywordLabel = CommonService.getKeywordLabel;
        vm.getPaperDetails = getPaperDetails;
        vm.closeDetails = closeDetails;

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

        function getPaperDetails (paper) {
            vm.isShowingDetails = true;
            vm.paperDetails = paper;
        }

        function closeDetails () {
            vm.isShowingDetails = false;
        }

    }
})();
