<?php

    require_once '../lib/Route.php';
    require_once '../controller/DashboardController.php';
    require_once '../controller/HomeController.php';
    require_once '../controller/UserController.php';
    require_once '../controller/ConfigController.php';
    require_once '../controller/StateController.php';
    require_once '../controller/SnmpController.php';
    require_once '../controller/GroupController.php';

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

                /**
                 * Route pour Dashboard
                 */
                '/dashboard' => array(
                    'controller' =>'Dashboard',
                    'action' => 'dashboard',
                    'template' => 'dashboard'
                ),
                '/zabbix/update' => array(
                    'controller' =>'Dashboard',
                    'action' => 'updateZabbix',
                    'template' => 'zabbix'
                ),
                '/glpi/update' => array(
                    'controller' =>'Dashboard',
                    'action' => 'updateGlpi',
                    'template' => 'glpi'
                ),
                '/badpasswd/update' => array(
                    'controller' =>'Dashboard',
                    'action' => 'updateBadpasswd',
                    'template' => 'badpasswd'
                ),
                '/snmp_renater' => array(
                    'controller' =>'Dashboard',
                    'action' => 'snmp_renater'
                ),
                '/snmp_adista' => array(
                    'controller' =>'Dashboard',
                    'action' => 'snmp_adista'
                ),
                /**
                 * Route pour Home
                 */
                '/' => array(
                    'controller' =>'Home',
                    'action' => 'home',
                    'template' => 'home'
                ),
                '/tv' => array(
                    'controller' =>'Home',
                    'action' => 'tv',
                    'template' => 'tv'
                ),
                '/ajax/glpi_stats' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_glpi_stats'
                ),
                '/ajax/glpi_type' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_glpi_type'
                ),
                '/ajax/init_snmp' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_init_snmp'
                ),
                '/ajax/zabbix_home' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_zabbix',
                    'template' => 'zabbix'
                ),
                '/ajax/partial_zabbix_home' => array(
                    'controller' =>'Home',
                    'action' => 'partial_ajax_zabbix',
                    'template' => 'zabbixtv'
                ),
                '/ajax/glpipie_home' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_glpipie',
                    'template' => 'glpipie'
                ),
                '/ajax/glpibar_home' => array(
                    'controller' =>'Home',
                    'action' => 'ajax_glpibar',
                    'template' => 'glpibar'
                ),
                /**
                 * Route pour STATE
                 */

                '/state/add' => array(
                    'controller' =>'State',
                    'action' => 'add',
                    'template' => 'add'
                ),
                '/state/del' => array(
                    'controller' =>'State',
                    'action' => 'delete'
                ),
                '/state/update' => array(
                    'controller' =>'State',
                    'action' => 'update',
                    'template' => 'update'
                ),

                /**
                 * Route pour SNMP
                 */
                '/snmp/add' => array(
                    'controller' =>'Snmp',
                    'action' => 'add'
                ),
                '/snmp/feed' => array(
                    'controller' =>'Snmp',
                    'action' => 'feed'
                ),
                '/snmp/del' => array(
                    'controller' =>'Snmp',
                    'action' => 'delete'
                ),
                '/snmp/update' => array(
                    'controller' =>'Snmp',
                    'action' => 'update',
                    'template' => 'update'
                ),

                /**
                 * AJAX pour STATE
                 */
                '/state/ajax_zabbix_host' => array(
                    'controller' =>'State',
                    'action' => 'ajax_item',
                    'template' => 'ajax_item'
                ),

                /**
                 * Deny
                 */

                '/deny' => array(
                    'controller' =>'Dashboard',
                    'action' => 'deny',
                    'template' => 'deny'
                ),

                /**
                 * Route pour USER
                 */
                '/login' => array(
                    'controller' =>'User',
                    'action' => 'login',
                    'template' => 'login'
                ),
                '/logout' => array(
                    'controller' =>'User',
                    'action' => 'logout'
                ),

                /**
                 * Route pour la CONFIGURATION
                 */
                '/config/config.json' => array(
                    'controller' =>'Config',
                    'action' => 'getJsonConfig'
                ),
                '/config' => array(
                    'controller' =>'Config',
                    'action' => 'setup',
                    'template' => 'setup'
                ),
                '/config-sys' => array(
                    'controller' =>'Config',
                    'action' => 'setup_sys',
                ),
                '/config/dbzabbix' => array(
                    'controller' =>'Config',
                    'action' => 'ajax_dbzabbix',
                    'template' => '_db_zabbix'
                ),
                '/config/dbglpi' => array(
                    'controller' =>'Config',
                    'action' => 'ajax_dbglpi',
                    'template' => '_db_glpi'
                ),
                '/config/reset' => array(
                    'controller' =>'Config',
                    'action' => 'ajax_reset',
                    'template' => '_reset'
                ),

                /**
                 * Route pour les groupes
                 */
                '/group/add' => array(
                    'controller' =>'Group',
                    'action' => 'add'
                ),
                '/group/del' => array(
                    'controller' =>'Group',
                    'action' => 'delete'
                ),
                '/group/update' => array(
                    'controller' =>'Group',
                    'action' => 'update',
                    'template' => 'update'
                ),
            );
            parent::__construct();
        }
    }