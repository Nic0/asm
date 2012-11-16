<?php

    require_once '../lib/SQLModel.php';

    /**
     * @brief Permet de différencier les accès aux deux bases de données
     */
    class ZabbixModel extends SQLModel {
        /**
         * @brief Le constructeur se contente d'appeler le constructeur parent
         *        avec le bon mon de base (repris dans config)
         * @note  Si des méthodes commune à tout les modèles Zabbix doit être implémenté
         *        cette classe est le bon endroit.
         */
        public function __construct() {
            parent::__construct('zabbix');
        }

    }