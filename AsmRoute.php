<?php

    require_once 'lib/Route.php';
    require_once 'controller/StaticPageController.php';
    require_once 'controller/UserController.php';

    class AsmRoute extends Route {

        public $route = array(
            '/' => array(
                'controller' =>'StaticPage',
                'action' => 'home',
                'template' => 'home'
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
        );
    }