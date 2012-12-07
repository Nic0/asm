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
        public $point;

        public function nested ($groups) {
            $data = array();
            foreach ($groups as $g) {
                if ($g->group_id == null) {
                    $data[$g->id] = $g;
                } else {
                    $data[$g->group_id]->sous_group[$g->id] = $g;
                }
            }

            return $data;
        }

    }