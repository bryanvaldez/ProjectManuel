/* global CONTEXT */

angular.module('app').factory('Usuario', function ($http, $q) {
    // var context = CONTEXT + 'usuario/';
    return {
        // login: function (params) {
        //     var defered = $q.defer();
        //     var promise = defered.promise;
        //     $http({
        //         method: 'POST',
        //         url: context + 'login',
        //         params: params
        //     }).then(function (result) {
        //         defered.resolve(result.data);
        //     }, function (error) {
        //         defered.reject(error);
        //     });
        //     return promise;
        // },
        // init: function (params) {
        //     var defered = $q.defer();
        //     var promise = defered.promise;
        //     $http({
        //         method: 'GET',
        //         url: context + 'init',
        //         params: params
        //     }).then(function (result) {
        //         defered.resolve(result.data);
        //     }, function (error) {
        //         defered.reject(error);
        //     });
        //     return promise;
        // }
    };
});