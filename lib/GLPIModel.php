<?php

    require_once '../lib/Model.php';

    class GLPIModel extends Model {

        public function __construct() {
            parent::__construct('glpi');
        }

    }