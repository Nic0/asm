<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;


    class ZabbixItem extends ZabbixModel {

        public $itemid;
        public $hostid;
        public $name;
        public $lastvalue;
        public $key_;

        public function getByHost($hostid) {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from('items')
                   ->where("hostid = '" . $hostid . "' AND lastvalue IS NOT NULL")
                   ->columns(array('itemid', 'hostid', 'name', 'lastvalue', 'key_'));

            $result = $this->select($select);


            $data = array();
            foreach ($result as $row) {
                $item = $this->createObjectFromSingleData($row);
                $arg = explode(',', substr(strstr($item->key_, '['), 1));
                $arg = $arg[0];
                $item->name = str_replace('$1', $arg, $item->name);
                $data[] = $item;
            }

            return $data;

        }
    }