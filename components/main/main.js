/* global CONTEXT */

(function () {
    'use strict';
    angular
            .module('app')
            .controller('mainController', mainController)
            .factory('mainServices', mainServices)
            .constant('mainConstantes', {
                SERVICE_CANDIDATOS:  'registroCandidatos/',
                GRID_ADD: '01',
                GRID_EDIT: '02',

            });
    mainController.$inject = ['$timeout', '$scope', '$mdDialog', 'mainConstantes', 'mainServices','$location'];
    function mainController($timeout, $scope, $mdDialog, Constant, Service, $location) {

        var self = this;
        self.constants = Constant;
        self.link = link;
        self.back = back;

        self.modulos = [{id:1, nombre:"Modulo Incidentes", descripcion:"descripcion1"}, {id:2, nombre:"Modulo Producto", descripcion:"descripcion2"}, {id:3, nombre:"Modulo Cliente", descripcion:"descripcion3"}, {id:4, nombre:"Modulo Categoria", descripcion:"descripcion4"}];
        initModulo();

        function link(modulo){
            self.modulo = modulo;
        }
        function back(){
            initModulo();
        }
                
        function initModulo(){
            self.modulo = {id:null, nombre:"", descripcion:""};            
        }

    }

    mainServices.$inject = ['$http', '$q', 'mainConstantes'];
    function mainServices($http, $q, Constant) {

        var SERVICE_CANDIDATOS = Constant.SERVICE_CANDIDATOS;
        var factory = {

        };
        return factory;

    }

})();
