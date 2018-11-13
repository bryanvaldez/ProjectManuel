/* global CONTEXT */
angular.module('app').controller('indexController', indexController);


indexController.$inject = ['$scope', '$localStorage'];
function indexController($scope, $localStorage) {
    var self = this;
    self.init = init;


    function init() {
        self.user = $localStorage.user;
        self.timestamp = new Date().getTime();
    }
    function logout() {
        var user = null;
        if ($localStorage.user !== undefined) {
            user = $localStorage.user.nombreUsuario;
        }
        $localStorage.$reset();
        //location.href = CONTEXT + 'usuario/logout' + (user !== null ? '?username=' + user : '');
    }
}

