<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;

    class ZabbixEvent extends ZabbixModel {

        public $eventid;
        public $host;
        public $description;
        public $priority;
        public $lastchange;

        public function getLast() {

            $config = AsmConfig::getConfig();

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('e' => 'events'))
                   ->join(array('t' => 'triggers'), 'e.objectid = t.triggerid',  array('triggerid', 'description', 'priority', 'lastchange'))
                   ->join(array('f' => 'functions'), 't.triggerid = f.triggerid', array())
                   ->join(array('i' => 'items'), 'f.itemid = i.itemid', array())
                   ->join(array('h' => 'hosts'), 'i.hostid = h.hostid', array('host'))
                   ->limit($config->db->zabbix->limit)
                   ->order('lastchange DESC')
                   ->group('triggerid')
                   ->where(array(
                        'h.status' => 0,
                        'i.status' => 0,
                        't.status' => 0,
                        'e.object' => 0,
                        't.value' => 1))
                   ->columns(array('eventid', 'clock'));
            $results = $this->select($select);

            $data = array();

            foreach ($results as $row) {

                $event = $this->createObjectFromSingleData($row);
                $event->description = str_replace('{HOSTNAME}', $event->host, $event->description);
                $data[] = $event;
            }

            return $data;
        }
    }