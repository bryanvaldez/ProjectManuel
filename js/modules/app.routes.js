angular.module('app').config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {



        $urlRouterProvider.otherwise('/');
        $stateProvider.state('/', {
            url: '/',
            templateUrl: 'http://localhost:8081/MANUEL/components/main/main.html',
            controller: "mainController",
            controllerAs: "ctrl"
        });

        $stateProvider.state('/login', {
            url: '/login',
            templateUrl: 'http://localhost:8081/MANUEL/components/usuario/login.html',
            controller: "loginController",
            controllerAs: "ctrl"
        });

    }]);
