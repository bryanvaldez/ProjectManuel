angular.module('app').controller('loginController', LoginController);

LoginController.$inject = ['Usuario', '$location', '$localStorage', '$timeout', 'Constants', '$rootScope'];
function LoginController(Usuario, $location, $localStorage, $timeout, Constants, $rootScope) {
    var self = this;

    self.submit = submit;
    self.clean = clean;

    self.user = ""; 
    self.password = "";


    function submit($event){
    	$event.preventDefault();
        Usuario.login({user: self.user, password: self.password}).then(
            function (d) {
                console.log(d);
                if(d.success){
                    $localStorage.usuario = d.data.usuario;
                    $localStorage.nombres = d.data.nombres;
                    $localStorage.apMaterno = d.data.apMaterno;
                    $localStorage.apPaterno = d.data.apPaterno;
                    $rootScope.$emit('update', 1);                    
                    $location.path('/');
                    return true;
                }else{
                    self.message = 'El usuario y/o contraseña que ha ingresado no son válidos, inténtelo de nuevo.';
                }
            },
            function (errResponse) {
                console.error('Error while fetching Users ' + errResponse);
            }
        );
    }
    function clean(){
        self.message = null;
    }


}
