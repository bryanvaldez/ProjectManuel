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

        self.bussy = false;

        self.constants = Constant;
        self.link = link;
        self.back = back;
        self.add = add;
        self.remove = remove;
        self.close = close;
        self.edit = edit;
        self.save = save;

        self.modulos = [{id:1, nombre:"Modulo Incidentes", descripcion:"descripcion1"}, {id:2, nombre:"Modulo Producto", descripcion:"descripcion2"}, {id:3, nombre:"Modulo Cliente", descripcion:"descripcion3"}, {id:4, nombre:"Modulo Categoria", descripcion:"descripcion4"}];
        self.incidentes = [];
        self.categorias = [];       
        self.productos = [];         
        self.clientes = [];   

        initModulo();
        initIncidente();
        initCategoria();
        initProducto();
        initCliente();

        function initModulo(){
            self.modulo = {id:null, nombre:"", descripcion:""};            
        }
        function initIncidente(){
            self.incidente = {id:null, detalle:'',observacion:'', estado:null, edit:1};    
            return self.incidente;      
        }
        function initCategoria(){
            self.categoria = {id:null, nombre:'', edit:1}; 
            return self.categoria;            
        }
        function initProducto(){
            self.producto = {id:null, nombre:'', marca:'', modelo:'', serie:'', idCategoria:'', edit:1};            
            return self.producto;     
        }
        function initCliente(){
            self.cliente = {id:null, ntipoDoc:null, documento:'', apPaterno:'', apMaterno:'', nombres:'', estado:null, edit:1};            
            return self.cliente;     
        }                        


        function link(modulo){
            self.modulo = modulo;      
            getListData();      
        }
        function back(){
            self.bussy = false;
            initModulo();
            initIncidente();
            initCategoria();
            initProducto();
            initCliente();
        }                
        function add(){
            self.bussy = true;
            var modulo = self.modulo.id;
            if(modulo ==1){
                self.incidentes.push(initIncidente());
            }else if(modulo ==2){
                self.productos.push(initProducto()); 
            }else if(modulo ==3){
                self.clientes.push(initCliente());
            }else if(modulo ==4){
                self.categorias.push(initCategoria());                
            }
        }

        function remove(i){
            Service.removeData({type: self.modulo.id, data: i}).then(
                function (d) {
                    if(d.success){
                        close(i);
                    }else{
                        console.log("no se pudo eliminar");
                    }
                },
                function (errResponse) {
                    console.error('Error while fetching Users ' + errResponse);
                }
            ); 
        }

        function close(i){
            self.bussy = false;            
            var modulo = self.modulo.id;
            if(modulo == 1){
                var index = self.incidentes.indexOf(i);                
                if(i.id== null){
                    if (index > -1) {
                        self.incidentes.splice(index, 1);
                    }
                }else{                
                    self.incidentes[index].edit = 0;    
                }
            }else if(modulo == 2){
                var index = self.productos.indexOf(i);                
                if(i.id== null){
                    if (index > -1) {
                        self.productos.splice(index, 1);
                    }    
                }else{
                    self.productos[index].edit = 0; 
                }                          
            }else if(modulo == 3){
                var index = self.clientes.indexOf(i);                  
                if(i.id== null){
                    if (index > -1) {
                        self.clientes.splice(index, 1);
                    }     
                }else{
                    self.clientes[index].edit = 0; 
                }                               
            }else if(modulo == 4){         
                var index = self.categorias.indexOf(i);                
                if(i.id== null){
                    if (index > -1) {
                        self.categorias.splice(index, 1);
                    }  
                }else{
                    self.categorias[index].edit = 0; 
                }                                 
            }                       
        }

        function edit(i){
            var modulo = self.modulo.id;
            if(modulo == 1){
                var index = self.incidentes.indexOf(i);
                if (index > -1) {
                    self.incidentes[index].edit = 1;
                }
            }else if(modulo == 2){
                var index = self.productos.indexOf(i);
                if (index > -1) {
                    self.productos[index].edit = 1;
                }               
            }else if(modulo == 3){
                var index = self.clientes.indexOf(i);
                if (index > -1) {
                    self.clientes[index].edit = 1;
                }                              
            }else if(modulo == 4){           
                var index = self.categorias.indexOf(i);
                if (index > -1) {
                    self.categorias[index].edit = 1;
                }  
            }              
        }        

        function save(i){
            Service.submiData({type: self.modulo.id, data: i}).then(
                function (d) {
                    if(d.success){
                        self.bussy = false;
                        getListData();
                    }
                },
                function (errResponse) {
                    console.error('Error while fetching Users ' + errResponse);
                }
            );               
        }
        function getListData(){
            Service.getListData(self.modulo.id).then(
                function (d) {
                    if(d.success){
                        setListData(d.data);
                    }
                },
                function (errResponse) {
                    console.error('Error while fetching Users ' + errResponse);
                }
            );            
        }

        initCategorias();
        function initCategorias(){
            Service.getListData(4).then(
                function (d) {
                    if(d.success){
                        self.categorias = d.data;
                    }
                },
                function (errResponse) {
                    console.error('Error while fetching Users ' + errResponse);
                }
            );   
        }

        function setListData(data){
            var modulo = self.modulo.id;
            if(modulo ==1){
                self.incidentes = data;
            }else if(modulo ==2){
                self.productos = data; 
            }else if(modulo ==3){
                self.clientes = data;
            }else if(modulo ==4){                
                self.categorias = data;
            } 
        }


    }

    mainServices.$inject = ['$http', '$q', 'Constants'];
    function mainServices($http, $q, Constants) {

        var context = Constants.SERVER + 'services/';
        return {
            getListData: function (code) {
                var deferred = $q.defer();
                $http({
                    url: context + 'data',
                    method: 'POST',
                    data: code,                    
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
            },

            submiData: function (params) {
                var deferred = $q.defer();
                $http({
                    url: context + 'data/save',
                    method: 'POST',
                    data: params,                    
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
            },

            removeData: function (params) {
                var deferred = $q.defer();
                $http({
                    url: context + 'data/remove',
                    method: 'POST',
                    data: params,                    
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
    }


})();
