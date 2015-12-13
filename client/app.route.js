(function() {
    'use strict';
    angular
        .module('frdApp')
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
                        templateUrl: 'client/views/admin.keyword.html',
                        controller: 'KeywordController',
                        controllerAs: 'vm'
                    })
                    .state('admin.statistic', {
                        url: '/statistic',
                        templateUrl: 'client/views/admin.statistic.html'
                    })
                    .state('userindex', {
                        url: '/userindex',
                        controller: 'UserIndexController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/user.index.html'
                    })
                    .state('useradd', {
                        url: '/useradd',
                        controller: 'UserAddController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/user.add.html'
                    })
                    .state('userupdate', {
                        url: '/userupdate/:userId',
                        controller: 'UserUpdateController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/user/user.update.html'
                    })
                    .state('paperindex', {
                        url: '/paperindex',
                        controller: 'PaperIndexController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/paper/paper.index.html'
                    })
                    .state('paperadd', {
                        url: '/paperadd',
                        controller: 'PaperAddController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/paper/paper.add.html'
                    })
                    .state('paperupdate', {
                        url: '/paperupdate/:paperId',
                        controller: 'PaperUpdateController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/paper/paper.update.html'
                    })
                    .state('login', {
                        url: '/login',
                        controller: 'LoginController',
                        controllerAs: 'vm',
                        templateUrl: 'client/views/login.html'
                    });
            }
        ]);
})();
