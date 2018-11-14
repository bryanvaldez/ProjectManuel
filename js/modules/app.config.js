angular.module('app').config(['$httpProvider', '$mdDateLocaleProvider',
    function ($httpProvider, $mdDateLocaleProvider) {

        $mdDateLocaleProvider.months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $mdDateLocaleProvider.shortMonths = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $mdDateLocaleProvider.days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $mdDateLocaleProvider.shortDays = ['D', 'L', 'M', 'Mi', 'J', 'V', 'S'];

        $mdDateLocaleProvider.firstDayOfWeek = 1;
        $mdDateLocaleProvider.formatDate = function (date) {
            return date ? moment(date).format('DD/MM/YYYY') : null;
        };
        $httpProvider.interceptors.push(['$q', '$location', '$localStorage', function ($q, $location, $localStorage) {
                return {
                    'request': function (config) {
                        config.headers = config.headers || {};
                        config.headers['x-access-ajax'] = true;
                        if ($localStorage.user !== undefined) {
                            config.headers['x-user'] = $localStorage.user.nombreUsuario;
                        }
                        return config;
                    },
                    'responseError': function (response) {
                        if (response.status === 401 || response.status === 403) {
                            $localStorage.$reset();
                            location.href = CONTEXT + 'usuario/logout';
                        }
                        return $q.reject(response);
                    }
                };
            }]);
        $mdDateLocaleProvider.parseDate = function (dateString) {
            var m = moment(dateString, 'DD/MM/YYYY', true);
            return m.isValid() ? m.toDate() : new Date(NaN);
        };

    }]).run(['$rootScope', '$location', '$localStorage', '$timeout', function ( $rootScope, $location, $localStorage, $timeout) {

        var path = function () {
            return $location.path();
        };

        $rootScope.$watch(path, function (path, oldVal) {
            if (path === '/' && ($localStorage.usuario === undefined)) {
                $location.path('/login').replace();
            } else if (path === '/login' && $localStorage.usuario !== undefined) {
                $location.path('/').replace();
            } else if ($localStorage.usuario !== undefined) {
                var exists = false;
                if (path === '/preview') {
                    exists = true;
                } else {
                    angular.forEach($localStorage.opciones, function (v, k) {
                        angular.forEach(v.listaFormulario, function (v1, k2) {
                            if (path === '/' + v1.url) {
                                angular.copy($localStorage.opciones[k].listaFormulario, $localStorage.listaFormularios);
                                exists = true;
                            }
                        });
                    });
                }
                if (!exists) {
                    $location.path('/').replace();
                }
            } else {
                $location.path('/login').replace();
            }
        });
    }]);