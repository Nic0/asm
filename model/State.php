<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;


    class State extends AsmPhpModel {

        public $id;
        public $name;
        public $hostid;
        public $itemid;
        public $low;
        public $high;
        public $coeff;

    }