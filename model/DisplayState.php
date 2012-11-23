<?php

    use Zend\Db\Sql\Sql;
    require_once '../model/State.php';

    class DisplayState extends State {

        public $hostname;
        public $itemname;
        public $lastvalue;
        public $prevvalue;

        function __construct() {
            parent::__construct('zabbix');
        }

        public function getAll ($stateList) {
            $state = new State();
            $data = $state->getAll();
            $where = '';
            for ($i=0; $i < sizeof($data); $i++) {
                $state = $data[$i];
                if ($i == sizeof($data)-1) {
                    $where .= "i.itemid = '" . $state->itemid . "'";
                } else {
                    $where .= "i.itemid = '" . $state->itemid . "' OR ";
                }
            }

            foreach ($data as $state) {
            }

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('i' => 'items'))
                   ->join(array('h' => 'hosts'), 'i.hostid = h.hostid', array('hostname' => 'host'))
                   ->where($where)
                   ->columns(array('itemname' => 'name', 'lastvalue', 'prevvalue'));

            $results = $this->select($select);



            foreach ($results as $i => $row) {
                foreach ($data[$i] as $key => $value) {
                    $row->$key = $value;
                }
                var_dump($row);
            }
        }
    }