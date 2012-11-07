<?php

    require_once 'lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;

    class ZabbixEvent extends ZabbixModel {

        public $host;
        public $description;
        public $priority;

        public function getLast() {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('e' => 'events'))
                   ->join(array('t' => 'triggers'), 'e.objectid = t.triggerid',  array())
                   ->join(array('f' => 'functions'), 't.triggerid = f.triggerid', array())
                   ->join(array('i' => 'items'), 'f.itemid = i.itemid', array())
                   ->join(array('h' => 'hosts'), 'i.hostid = h.hostid', array('host'))
                   ->limit('10, 10')
                   ->columns(array('eventid'));
            $results = $this->select($select);


            foreach ($results as $row) {
                var_dump($row);
            }

        }
    }