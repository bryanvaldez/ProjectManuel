/* global CONTEXT */

angular.module('app').factory('Usuario', function ($http, $q, Constants) {
    var context = Constants.SERVER + 'services/';
    return {
        login: function (params) {
            var deferred = $q.defer();
            $http({
                url: context + 'login',
                method: 'POST',
                data: params,
                //transformRequest: angular.identity,
                headers: {'Content-Type': 'application/json'}
            }).then(
                function (response) {
                    deferred.resolve(response.data);
                },
                function (errResponse) {
                    console.error('Error: Service.');
                    deferred.reject(errResponse);
                }
            );
            return deferred.promise;
        }
    };


});