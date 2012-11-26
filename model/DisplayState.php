<?php

    use Zend\Db\Sql\Sql;
    require_once '../model/State.php';

    class DisplayState extends State {

        public $id;
        public $hostname;
        public $itemname;
        public $lastvalue;
        public $prevvalue;
        public $key_;
        public $itemid;

        function __construct() {
            parent::__construct('zabbix');
        }

        public function getAll () {
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
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('i' => 'items'))
                   ->join(array('h' => 'hosts'), 'i.hostid = h.hostid', array('hostname' => 'host'))
                   ->where($where)
                   ->columns(array('itemname' => 'name', 'lastvalue', 'prevvalue', 'key_', 'itemid'));

            $results = $this->select($select);

            $zabbixState = array();

            foreach ($results as $i => $row) {
                foreach ($data as $d) {
                    if ($d->itemid == $row->itemid) {
                        foreach ($d as $key => $value) {
                            $row->$key = $value;
                        }
                    }
                }

                $item = $this->createObjectFromSingleData($row);
                $arg = explode(',', substr(strstr($item->key_, '['), 1));
                $arg = $arg[0];
                $item->itemname = str_replace('$1', $arg, $item->itemname);
                $zabbixState[] = $item;
            }

            return $zabbixState;
        }
    }