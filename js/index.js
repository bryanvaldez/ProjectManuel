/* global CONTEXT */
angular.module('app').controller('indexController', indexController);


indexController.$inject = ['$scope', '$localStorage', 'Constants', '$rootScope'];
function indexController($scope, $localStorage, Constants, $rootScope) {
    var self = this;
    self.init = init;
    self.logout = logout;    


    function init() {
        self.user = $localStorage.usuario;
        self.nombres = $localStorage.nombres;
        self.apPaterno = $localStorage.apPaterno;
        self.timestamp = new Date().getTime();
    }


    $rootScope.$on('update', function(event, codes){
        init();
    });


    function logout() {
        $localStorage.$reset();
        location.href = Constants.SERVER;
    }
}

