var app = angular.module('app');

app.factory('Constants', function () {
    return {
        SERVER: 'http://' + window.location.href.split("/")[2] + '/MANUEL/',
    };
});