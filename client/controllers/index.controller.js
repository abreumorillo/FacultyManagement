(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'IndexService', 'appConfig'];

    /* @ngInject */
    function IndexController($scope, IndexService, appConfig) {
        var vm = this;
        vm.title = 'IndexController';
        vm.searchTerm = "";
        vm.search = search;
        //Pagination options
        vm.totalItems = 0;
        vm.currentPage = 1;
        vm.itemPerPage = 10;
        vm.isSearchResult = false;
        vm.closeSearch = function  () {
            vm.isSearchResult = false;
        };

        activate();

        ////////////////

        function activate() {
            console.log('Index Controller Activated');
        }

        function search() {

            IndexService.searchPaper(vm.searchTerm, vm.currentPage, vm.itemPerPage).then(function  (response) {
                if(response.status === 200) {
                    vm.isSearchResult = true;
                }
               // console.log(response);
            });
        }

        /**
         * Watch for changes in the searm term and fire a search every x seconds
         * @param  {string} tmpSearch) {
         * @return {mix}
         */
        // $scope.$watch('vm.searchTerm', function(tmpSearch) {
        //     if(!tmpSearch || tmpSearch.length === 0) {
        //         return;
        //     }
        //     if(tmpSearch === vm.searchTerm) {
        //         search();
        //     }
        // });
    }
})();
