<?php

    require_once '../lib/Model.php';

    class ZabbixModel extends Model {

        public function __construct() {
            parent::__construct('zabbix');
        }

    }