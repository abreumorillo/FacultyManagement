(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('PaperIndexController', PaperIndexController);

    PaperIndexController.$inject = ['PaperService', '$timeout', 'CommonService', 'toastr'];

    /* @ngInject */
    function PaperIndexController(PaperService, $timeout, CommonService, toastr) {
        var vm = this;

        //\\/\/\/\/\/\\\/ BINDABLE MEMBERS
        vm.title = 'PaperIndexController';
        vm.papers = [];
        vm.isSearching = false;
        vm.searchTerm = "";
        vm.isLoaded = false;

        //Pagination options
        vm.totalItems = 0;
        vm.currentPage = 1;
        vm.itemPerPage = 5;
        vm.isPaging = false;
        vm.isShowingDetails = false;
        vm.paperDetails = {};
        vm.closeDetails = closeDetails;


        //\/\/\\/\/\/\/\\FUNCTIONS //\/\/\/\/\
        vm.search = search;
        vm.paginate = paginate;
        vm.pageChanged = pageChanged;
        vm.getKeywordLabel = CommonService.getKeywordLabel;
        vm.remove = remove;
        vm.getPaperDetails = getPaperDetails;

        activate();


        /**
         * Activate function
         * @return {mix}
         */
        function activate() {
            // console.log(vm.title);
            countPapers().then(function() {
                if (vm.totalItems > 0) {
                    get();
                }
            });
        }

        /**
         * Search for  papers
         * @param  {string} searchTerm
         * @return {mix}
         */
        function search() {
            console.log('search');
            vm.isSearching = true;
            get(vm.searchTerm).then(function() {
                vm.isSearching = false;
            });
        }

        /**
         * Get the papers from the database
         * @param  {string} searchTerm
         * @return {mix}
         */
        function get(searchTerm) {
            searchTerm = searchTerm || '*';
            return PaperService.getPaper(searchTerm, vm.currentPage, vm.itemPerPage).then(function(successResponse) {
                if (CommonService.isValidResponse(successResponse)) {
                    vm.papers = CommonService.getResponse(successResponse);
                }
                vm.isLoaded = true;
            }, handleErrorResponse);
        }

        /**
         * Handle error response
         * @param  {object} errorResponse
         * @return {mix}
         */
        function handleErrorResponse(errorResponse) {
            console.log(errorResponse);
            toastr.error('An error has occurred', "Code: "+errorResponse.status);
            vm.isLoaded = true;
        }

        /***
         * Function execute every time we interact with the pagination control
         */
        function pageChanged() {
            vm.isPaging = true;
            get(vm.searchTerm, vm.currentPage, vm.itemPerPage).then(function() {
                vm.isPaging = false;
            });
        }

        /**
         * Paginate
         * @param  {[type]} value [description]
         * @return {[type]}       [description]
         */
        function paginate(value) {
            var begin, end, index;
            begin = (vm.currentPage - 1) * vm.itemPerPage;
            end = begin + vm.itemPerPage;
            index = vm.papers.indexOf(value);
            return (begin <= index && index < end);
        }

        /**
         * Count the number of paper in the database
         * @return {[type]} [description]
         */
        function countPapers() {
            return PaperService.count(vm.searchTerm).then(function(successResponse) {
                if (successResponse.status === CommonService.statusCode.HTTP_OK) {
                    vm.totalItems = parseInt(successResponse.data);
                }
            }, handleErrorResponse);
        }

        /**
         * Remove a paper from the database.
         * @param  {paper} paper
         * @return {mix}
         */
        function remove (paper) {
            PaperService.delete(paper.id).then(function(successResponse) {
                if (successResponse.status == CommonService.statusCode.HTTP_NO_CONTENT) {
                    var idx = vm.papers.indexOf(paper);
                    if (idx !== -1) {
                        vm.papers.splice(idx, 1);
                    }
                }
            }, handleErrorResponse);
        }

        /**
         * [getPaperDetails description]
         * @param  {object} paper
         * @return {mix}
         */
        function getPaperDetails (paper) {
            vm.paperDetails = paper;
            vm.isShowingDetails = true;
        }

        /**
         * Close details
         * @return {mix}
         */
        function closeDetails () {
            vm.isShowingDetails = false;
        }

    }
})();
