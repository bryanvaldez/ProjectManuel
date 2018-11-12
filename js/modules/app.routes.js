angular.module('app').config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {

        $urlRouterProvider.otherwise('/');
        $stateProvider.state('/', {
            url: '/',
            templateUrl: 'http://localhost:8081/MANUEL/archivos/pagina.html',
            controller: "dashboardController",
            controllerAs: "d"
        });

        $stateProvider.state('/login', {
            url: '/login',
            templateUrl: 'http://localhost:8081/MANUEL/components/usuario/login.html',
            controller: "loginController",
            controllerAs: "l"
        });

    }]);
