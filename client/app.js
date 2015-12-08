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
        baseUrl: baseUrl
    };

    angular
        .module('frdApp', [
            'ui.router', //Angular module for providing routing functionality.
            'ngAnimate', //Angular module for animation. - https://github.com/theoinglis/ngAnimate.css
            'ngMessages', //Output Error Messages
            'toastr', //Angular module for providing a message functionality -  https://github.com/Foxandxss/angular-toastr
            'underscore', //Javascript library for managing collection of data
            'angular-loading-bar' //Display loading bar when XHR request are fired
            // 'ui.bootstrap.pagination' //Bootstrap component for pagination
        ]) //APPLICATION ROUTES
        .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
            function($stateProvider, $urlRouterProvider, $locationProvider) {
                // For any unmatched url, redirect to /index
                $urlRouterProvider.otherwise("/index");
                //$locationProvider.html5Mode(true).hashPrefix('!');
                // Now set up the states
                $stateProvider
                    .state('index', {
                        url: '/index',
                        controller: 'IndexController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/index.html'
                    })
                    .state('admin', {
                        url: "/admin",
                        abstract: true,
                        controller: 'AdminController',
                        controllerAs: 'vm',
                        templateUrl: "client/views/admin.html"
                    })
                    .state('admin.index', {
                        url: '/index',
                        templateUrl: 'client/views/admin.index.html'
                    })
                    .state('admin.keyword', {
                        url: '/keyword',
                        templateUrl: 'client/views/admin.keyword.html'
                    })
                    .state('admin.statistic', {
                        url: '/statistic',
                        templateUrl: 'client/views/admin.statistic.html'
                    })
                    .state('userindex', {
                        url: '/userindex',
                        controller: 'UserIndexController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/userindex.html'
                    })
                    .state('useradd', {
                        url: '/useradd',
                        controller: 'UserAddController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/useradd.html'
                    })
                    .state('userupdate', {
                        url: '/userupdate',
                        controller: 'UserUpdateController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/userupdate.html'
                    })
                    .state('login', {
                        url: '/login',
                        controller: 'LoginController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/login.html'
                    });
            }
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
                allowHtml: true
            });
        });
    // .run(['AuthService', function(AuthService) {
    //     AuthService.logOut();
    // }]);
})();
