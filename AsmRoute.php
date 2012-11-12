<?php

    require_once '../lib/Route.php';
    require_once '../controller/StaticPageController.php';
    require_once '../controller/UserController.php';
    require_once '../controller/ConfigController.php';
    /**
     * @brief permet de construire un tableau servant à router les requêtes
     *
     * Équivalant à un front-controller
     *
     */
    class AsmRoute extends Route {

        public $route;

        /**
         * @brief mise en place de la donnée membre `$this->route`
         *
         * Le tableau permet de mapper une URI à un groupe controlleur/méthode/template
         *
         * - controller: Nom du controlleur à appeler, le nom de la classe, sans
         * "Controller" à la fin (exemple, `StaticPage`, `User`)
         * - action: Méthode qui serra appelé dans la classe du controlleur
         * - template: nom du template à appeler (sans le .html)
         *
         * la variable se présente donc sous la forme, avec la page d'acceuil
         * comme exemple :
         *
         * ~~~~~~~~~~~~~{.php}
         *     $this->route = array(
         *         '/' => array(
         *             'controller' => 'StaticPage',
         *             'action' => 'home',
         *             'template' => 'home'
         *         ),
         *         // Autres routes...
         *     )
         * ~~~~~~~~~~~~~
         *
         */
        public function __construct () {

            $this->route = array(
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
                    'action' => 'getJsonConfig'
                ),
            );
            parent::__construct();
        }
    }