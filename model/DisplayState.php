<?php

    require_once '../model/State.php';
    require_once '../model/Group.php';

    /**
     * @brief Affichage d'un état Zabbix (base zabbix)
     *
     * La classe fait le pont entre les éléments stockés dans la table
     * phpasm/states et les tables zabbix/hosts et zabbix/itemid
     *
     * La classe permet de construire ainsi les données devant être affiché
     */
    class DisplayState extends State {

        /** @brief Nom de l'host */
        public $hostname;
        /** @brief Nom de l'élément monitoré (ex: Cpu load5) */
        public $itemname;
        /** @brief Dernière valeur récupéré par zabbix */
        public $lastvalue;
        /** @brief Valeur précédante (pouvant servir à des tendances) */
        public $prevvalue;
        /** @brief Permet de récupérer et remplacer $1 présant dans itemname */
        public $key_;
        /** @brief identifiant de l'élément */
        public $itemid;
        /** @brief points attribué, pour l'indicateur vert/jaune/rouge */
        public $point;

        /**
         * @brief Permet de se fixer sur la bonne base
         *
         * Comme les états prennent les informations sur une autre base, il convient
         * de l'indiquer dans le constructeur.
         */
        function __construct() {
            parent::__construct('zabbix');
        }

        /**
         * @brief Permet d'obtenir tout les états
         * @return array Tableau d'état de zabbix
         */
        public function getAll () {
            $data = array();
            $state = new State();
            $state = $state->getAll();
            $group = new Group();
            $group = $group->getAll();

            if(!empty($state)) {
                $where = '';
                for ($i=0; $i < sizeof($state); $i++) {
                    $s = $state[$i];
                    if ($i == sizeof($state)-1) {
                        $where .= "i.itemid = '" . $s->itemid . "'";
                    } else {
                        $where .= "i.itemid = '" . $s->itemid . "' OR ";
                    }
                }

                $select = $this->sql
                    ->select()
                    ->from(array('i' => 'items'))
                        ->join(array('h' => 'hosts'), 'i.hostid = h.hostid', array('hostname' => 'host'))
                    ->where($where)
                    ->columns(array('itemname' => 'name', 'lastvalue', 'prevvalue', 'key_', 'itemid'));

                $results = $this->select($select);

                $zabbixState = array();

                foreach ($results as $i => $row) {
                    foreach ($state as $s) {
                        if ($s->itemid == $row->itemid) {
                            foreach ($s as $key => $value) {
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

                foreach ($group as $g) {
                    if ($g->group_id == null) {
                        $data[$g->id] = $g;
                    } else {
                        $data[$g->group_id]->sous_group[$g->id] = $g;
                        foreach ($zabbixState as $s) {
                            if ($s->group_id == $g->id) {
                                $data[$g->group_id]->sous_group[$g->id]->state[] = $s;
                            }
                        }
                    }
                }

                return $data;
            }
        }

        /**
         * @brief Attribue un point (0,1,2) en fonction de la couleur, utile pour la moyenne
         * @param State $zabbix état zabbix
         */
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