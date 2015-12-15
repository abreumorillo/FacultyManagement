/**
 * Purpose      : This service provide features related to authentication the security of this application is based on $_SESSION  $_COOKIE.
 * Date         : 3/14/2015
 * @author      : Neris S. Abreu.
 */
angular
    .module('frdApp')
    .factory('AuthService', AuthService);

AuthService.$inject = ['$http', '$q', 'appConfig', '$cookies'];

/* @ngInject */
function AuthService($http, $q, appConfig, $cookies) {
    var serviceUrl = appConfig.baseUrl = 'server/controllers/LoginController.php',
        userData = {},
        service = {
            login: login,
            register: register,
            isAuthenticated: isAuthenticated,
            logOut: logOut,
            isManager: isManager
        };

    return service;

    ////////////////

    /**
     * This function post the user information to the PHP script for processing.
     * @param userInfo
     * @returns {d.promise|promise|m.ready.promise|fd.g.promise}
     */
    function login(userInfo) {
        userInfo.action = "login";
        var postData = 'data=' + JSON.stringify(userInfo);
        var deferred = $q.defer();
        $http({
            method: 'POST',
            url: serviceUrl,
            data: postData
        }).success(function(data, status) {
            userData = data;
            $cookies.putObject(appConfig.cookieName, data);
            deferred.resolve({
                data: data,
                status: status
            });
        }).error(function(error, status) {
            deferred.reject({
                error: error,
                status: status
            });
        });
        return deferred.promise;
    }

    /**
     * This function provide registration functionality to the application.
     * all user are registered as normal user.
     * @param userInfo
     * @returns {d.promise|promise|m.ready.promise|fd.g.promise}
     */
    function register(userInfo) {
        userInfo.action = "register";
        var postData = 'data=' + JSON.stringify(userInfo);
        var url = appConfig.baseUrl + 'server/authentication.php';
        var deferred = $q.defer();
        $http({
            method: 'POST',
            url: url,
            data: postData
        }).success(function(data) {
            deferred.resolve(data);
        }).error(function(data, status) {
            deferred.reject(status);
        });
        return deferred.promise;
    }

    /**
     * Determine if a user is authenticated
     * @returns {*|$rootScope.isAuthenticated}
     */
    function isAuthenticated() {
        return userData.isAuthenticated;
    }

    function isManager () {
        return (userData.role === 'Faculty' || userData.role === 'Admin');
    }

    /**
     * Provide logout funtionality to the application
     * @returns {d.promise|promise|m.ready.promise|fd.g.promise}
     */
    function logOut() {
        var logoutData = 'data=' + JSON.stringify({
            action: 'logout'
        });
        var deferred = $q.defer();
        $http({
            method: 'POST',
            url: serviceUrl,
            data: logoutData
        }).success(function(data, status) {
            userData = {};
            $cookies.remove(appConfig.cookieName);
            deferred.resolve({
                data: data,
                status: status
            });
        }).error(function(error, status) {
            deferred.reject({
                error: error,
                status: status
            });
        });
        return deferred.promise;
    }

}
