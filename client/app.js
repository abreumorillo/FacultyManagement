/**
 * Purpose      : This is the angular main module for application. We only use one module as this is a little application.
 *                In this module are declared routes and some configurations
 * Date         : 3/14/2015
 * @author      : Neris S. Abreu.
 */
(function() {
    'use strict';
    //obtain the base url of the application
    var baseUrl = location.protocol + "//" + location.host + location.pathname;
    var appConfig = {
        baseUrl: baseUrl,
        cookieName: 'frdUserInfo'
    };

    angular
        .module('frdApp', [
            'ui.router', //Angular module for providing routing functionality.
            'ngAnimate', //Angular module for animation. - https://github.com/theoinglis/ngAnimate.css
            'ngMessages', //Output Error Messages
            'ngCookies',
            'toastr', //Angular module for providing a message functionality -  https://github.com/Foxandxss/angular-toastr
            'underscore', //Javascript library for managing collection of data
            'ngSanitize',
            //'angular-loading-bar', //Display loading bar when XHR request are fired
            'ui.bootstrap.pagination', //Bootstrap component for pagination
            'ui.bootstrap.popover',
            'ui.bootstrap.modal'
        ])
        .config(['$compileProvider', function($compileProvider) {
            $compileProvider.debugInfoEnabled(false); //false for production
        }])
        .config(['$httpProvider', function($httpProvider) {
            $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8'; //This headers is needed in order to be able to POST form data to php scripts
        }])
        .value('appConfig', appConfig) //Provide general settings to the application.
        .config(['$compileProvider', function($compileProvider) {
            $compileProvider.debugInfoEnabled(false);
        }])
        .config(function(toastrConfig) {
            angular.extend(toastrConfig, {
                allowHtml: true,
                maxOpened: 1,
                positionClass: 'toast-bottom-right',
                preventDuplicates: true,
                preventOpenDuplicates: true,
            });
        })
        .run(['AuthService', function(AuthService) {
            AuthService.logOut();
        }]);
    // .run(['AuthService', function(AuthService) {
    //     AuthService.logOut();
    // }]);
})();
