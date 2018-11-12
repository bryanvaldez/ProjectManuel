<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html ng-app="app" xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">     
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->    
<head>
    <title>Manuel</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"  /> 
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="icon" type="image/png" href="{{$_BASE_URL}}favicon.ico" />
    <link rel="stylesheet" href="{{$_BASE_URL}}css/reset.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{$_BASE_URL}}css/lib/angular-material.min.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{$_BASE_URL}}css/lib/material-icon.css" type="text/css" media="screen"/>
</head>


<body ng-controller="mainController as ctrl" ng-cloak>    


    <div layout="row" flex>
        <ui-view class="container containerMargen colorGreen" layout="column" flex ng-cloak>

        </ui-view>
    </div>


    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular.min.js" ></script>
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular-ui-router.min.js" ></script>        
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular-aria.min.js" ></script>    
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular-animate.min.js" ></script>    
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular-material.min.js" ></script>    
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/angular-messages.min.js" ></script> 
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/lib/ngStorage.min.js" ></script>     


    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/modules/app.module.js"></script>
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/modules/app.config.js"></script>
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/modules/app.directives.js"></script>
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/modules/app.routes.js"></script>

    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}js/index.js"></script>

    <!--Login-->
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}components/usuario/loginController.js"></script>
    <script type="text/javascript" charset="UTF-8" src="{{$_BASE_URL}}components/usuario/usuarioService.js"></script>

  
</body>