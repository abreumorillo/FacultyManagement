(function() {
    'use strict';

    angular
        .module('frdApp')
        .controller('PaperAddController', PaperAddController);

    PaperAddController.$inject = ['PaperService', 'KeywordService', 'UserService', '$state', 'CommonService', 'toastr', '$q'];

    /* @ngInject */
    function PaperAddController(PaperService, KeywordService, UserService, $state, CommonService, toastr, $q) {
        var vm = this;

        activate();

        //\/\/\\\//\ BINDABLE MEMBERS //\\/\\\\/\\/\/\/\/
        vm.paper = {};
        vm.keywords = [];
        vm.faculties = [];
        vm.errors = [];
        //\/\/\/\/\\\/FUNCTIONS /\\/\\\\\/\
        vm.isInvalid = isInvalid;
        vm.goBack = goBack;
        vm.clearForm = clearForm;
        vm.save = save;

        function activate() {
            //Get keywords and faculty
            $q.all([KeywordService.getAll(), UserService.getFacultiesList()]).then(function  (data) {
                handleKeyword(data[0]);
                handleFaculties(data[1]);
            }, handleErrorResponse);
        }

        function handleKeyword (response) {
            if(response.status === CommonService.statusCode.HTTP_OK) {
                vm.keywords = response.data;
                console.log(response.data);
            }
        }

        function handleFaculties (response) {
            if(CommonService.isValidResponse(response)) {
                vm.faculties = CommonService.getResponse(response);
            }
        }

        /**
         * This function is used for validation purpose. It evaluates if a given form element is dirty and invalid
         * @param formElement
         * @returns {rd.$dirty|*|dg.$dirty|$dirty|rd.$invalid|b.ctrl.$invalid} boolean
         */
        function isInvalid(formElement) {
            return formElement.$dirty && formElement.$invalid;
        }

        /**
         * Go back to a particular state
         * @param  {string} url area to navigate
         * @return {mix}
         */
        function goBack(url) {
            $state.go(url);
        }


        function clearForm(form) {
            vm.paper = {};
            form.$setPristine();
        }


        function handleErrorResponse(error) {
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

        function save (form) {
            console.log(form);
            console.log(vm.paper);
        }
    }
})();
