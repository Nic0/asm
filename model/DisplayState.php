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
        public $point;

        function __construct() {
            parent::__construct('zabbix');
        }

        public function getAll () {
            $state = new State();
            $data = $state->getAll('states', 'State');
            if(!empty($data)) {
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
                    $this->setPoint($item);
                    $zabbixState[] = $item;
                }

                // Triage des résultats en fonction de l'id
                function cmp($a, $b) {
                    return strcmp($a->id, $b->id);
                }
                usort($zabbixState, "cmp");

                return $zabbixState;
            }
        }

        public function setPoint ($zabbix) {
            $value = $zabbix->lastvalue;

            $warning = $zabbix->warning;
            $alert = $zabbix->alert;

            if ($warning < $alert) {
                if ($value <= $warning) {
                    $zabbix->point = 0;
                } else if ($value <= $alert) {
                    $zabbix->point = 1;
                } else {
                    $zabbix->point = 2;
                }
            } else {
                if ($value >= $warning) {
                    $zabbix->point = 0;
                } else if ($value >= $alert) {
                    $zabbix->point = 1;
                } else {
                    $zabbix->point = 2;
                }
            }
        }
    }