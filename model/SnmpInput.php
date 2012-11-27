<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    class SnmpInput extends PhpAsmModel {

        public $id;
        public $snmp_id;
        public $value;
        public $date;

    }