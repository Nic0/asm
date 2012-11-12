<?php

    require_once '../lib/Route.php';
    require_once '../controller/StaticPageController.php';
    require_once '../controller/UserController.php';
    require_once '../controller/ConfigController.php';

    class AsmRoute extends Route {

        public $route = array(
            '/' => array(
                'controller' =>'StaticPage',
                'action' => 'home',
                'template' => 'home'
            ),
            '/zabbix/update' => array(
                'controller' =>'StaticPage',
                'action' => 'updateZabbix',
                'template' => 'zabbix'
            ),
            '/glpi/update' => array(
                'controller' =>'StaticPage',
                'action' => 'updateGlpi',
                'template' => 'glpi'
            ),
            '/login' => array(
                'controller' =>'User',
                'action' => 'login',
                'template' => 'login'
            ),
            '/logout' => array(
                'controller' =>'User',
                'action' => 'logout'
            ),
            '/config/config.json' => array(
                'controller' =>'Config',
                'action' => 'getJsonConfig',
                'template' => 'json'
            ),
        );
    }