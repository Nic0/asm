<?php

    require_once '../lib/Model.php';

    /**
     * @brief Permet de différencier les accès aux deux bases de données
     */
    class GLPIModel extends Model {

        /**
         * @brief Le constructeur se contente d'appeler le constructeur parent
         *        avec le bon mon de base (repris dans config)
         * @note  Si des méthodes commune à tout les modèles GLPI doit être implémenté
         *        cette classe est le bon endroit.
         */
        public function __construct() {
            parent::__construct('glpi');
        }

    }