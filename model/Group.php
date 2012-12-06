<?php

    require_once '../lib/PhpAsmModel.php';

    /**
     * @brief Etat d'un élément Zabbix (base phpasm)
     */
    class Group extends PhpAsmModel {

        public $id;
        public $name;
        public $logo;
        public $coeff;
        public $group_id;
        public $sous_group = array();
        public $state = array();

    }