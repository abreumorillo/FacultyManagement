(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('PaperAddController', PaperAddController);

    PaperAddController.$inject = ['PaperService', 'KeywordService', 'UserService', 'CommonService', 'toastr', '$q'];

    /* @ngInject */
    function PaperAddController(PaperService, KeywordService, UserService,  CommonService, toastr, $q) {

        var vm = this;
        activate();

        //\/\/\\\//\ BINDABLE MEMBERS //\\/\\\\/\\/\/\/\/
        vm.paper = {};
        vm.keywords = [];
        vm.faculties = [];
        vm.errors = [];
        //\/\/\/\/\\\/FUNCTIONS /\\/\\\\\/\
        vm.isInvalid = CommonService.isInvalidFormElement;
        vm.goBack = goBack;
        vm.clearForm = clearForm;
        vm.save = save;

        /**
         * Function run on controller activation
         * @return {mix}
         */
        function activate() {
            //Get keywords and faculty
            $q.all([KeywordService.getAll(), UserService.getFacultiesList()]).then(function(data) {
                handleKeyword(data[0]);
                handleFaculties(data[1]);
            }, handleErrorResponse);
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
            console.log(error);
            toastr.error('An error has occurred', "Code: " + error.status);
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
        function save(form) {
            PaperService.insertOrUpdate(vm.paper).then(function(successResponse) {
                if(successResponse.status === CommonService.statusCode.HTTP_CREATED){
                    CommonService.goToUrl('paperindex');
                    return;
                }
                toastr.error("An error has occurred");
            }, handleErrorResponse);
        }
    }
})();
