<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;


    class ZabbixHost extends ZabbixModel {

        public $hostid;
        public $host;

        public function getAll() {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from('hosts')->columns(array('hostid', 'host'));

            $result = $this->select($select);
            return $this->createObjectFromArrayData($result);

        }
    }