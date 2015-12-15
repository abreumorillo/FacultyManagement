(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('PaperUpdateController', PaperUpdateController);

    PaperUpdateController.$inject = ['PaperService', 'KeywordService', 'UserService', '$state', 'CommonService', 'toastr', '$q', '$stateParams'];

    /* @ngInject */
    function PaperUpdateController(PaperService, KeywordService, UserService, $state, CommonService, toastr, $q, $stateParams) {

        var vm = this;

        activate();

        //\/\/\\\//\ BINDABLE MEMBERS //\\/\\\\/\\/\/\/\/
        vm.paper = {};
        vm.keywords = [];
        vm.faculties = [];
        vm.errors = [];
        vm.isLoaded = false;
        //\/\/\/\/\\\/FUNCTIONS /\\/\\\\\/\
        vm.isInvalid = CommonService.isInvalidFormElement;
        vm.goBack = goBack;
        vm.clearForm = clearForm;
        vm.update = update;

        ////////////////

        /**
         * Function run on controller activation
         * @return {mix}
         */
        function activate() {
            //Get keywords and faculty
            $q.all([KeywordService.getAll(), UserService.getFacultiesList()]).then(function(data) {
                handleKeyword(data[0]);
                handleFaculties(data[1]);
            }, handleErrorResponse).then(function() {
                PaperService.getById($stateParams.paperId).then(function(successResponse) {
                    if (CommonService.isValidResponse(successResponse)) {
                        vm.paper = successResponse.data;
                        vm.paper.title = CommonService.sanitize(vm.paper.title);
                        vm.paper.abstract = CommonService.sanitize(vm.paper.abstract);
                        vm.paper.citation = CommonService.sanitize(vm.paper.citation);
                        vm.isLoaded = true;
                    }
                }, handleErrorResponse);
            });
        }

        /**
         * Handle the keywords
         * @param  {$http} response
         * @return {mix}
         */
        function handleKeyword(response) {
            if (response.status === CommonService.statusCode.HTTP_OK) {
                vm.keywords = response.data;
            }
        }

        /**
         * Handle the faculty response
         * @param  {$http} response
         * @return {mix}
         */
        function handleFaculties(response) {
            if (CommonService.isValidResponse(response)) {
                vm.faculties = CommonService.getResponse(response);
            }
        }

        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            CommonService.goToUrl(url);
        }


        /**
         * Clears the HTML form object and set it to pristine
         * @param  {HTML form} form
         * @return {mix}
         */
        function clearForm(form) {
            vm.paper = {};
            form.$setPristine();
        }

        /**
         * Handle error respose
         * @param  {$http} error
         * @return {[mix]}
         */
        function handleErrorResponse(error) {
            toastr.error('An error has occurred', "Code: " + error.status);
            console.log(error);
            if (error.status === 422) { //server side validation error
                vm.errors = error.error;
                var msg = "";
                angular.forEach(vm.errors, function(data) {
                    msg += data + '<br>';
                });
                toastr.error(msg, 'Validation Error');
            } else {
                toastr.error('An error has occurred while processing the data');
            }
        }

        /**
         * Save a paper
         * @param  {form element} form
         * @return {mix}
         */
        function update(form) {
            PaperService.insertOrUpdate(vm.paper).then(function(successResponse) {
                if (successResponse.status === CommonService.statusCode.HTTP_NO_CONTENT) {
                    CommonService.goToUrl('paperindex');
                    return;
                }
                toastr.error("An error has occurred");
            }, handleErrorResponse);
        }
    }
})();
