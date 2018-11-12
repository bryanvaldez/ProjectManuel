angular.module('app').controller('loginController', LoginController);
LoginController.$inject = ['Usuario', '$location', '$localStorage', '$timeout'];
function LoginController(Usuario, $location, $localStorage, $timeout) {
    var ctrl = this;
    console.log("loginController");
}
