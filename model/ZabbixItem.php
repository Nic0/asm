<?php

    require_once '../lib/ZabbixModel.php';

    /**
     * @brief Gestion d'un Item par Zabbix
     */
    class ZabbixItem extends ZabbixModel {

        /** @brief identifiant de l'item */
        public $itemid;
        /** @brief identifiant de l'host correspondant */
        public $hostid;
        /** @brief Nom/description de l'item */
        public $name;
        /** @brief Dernière valeur enregistrée */
        public $lastvalue;
        /** @brief Utilitaire pour remplacer les $1 de `name` */
        public $key_;

        /**
         * @brief Obtient un Item en fonction de l'identifiant de l'host
         * @param  int $hostid  identifiant de l'host
         * @return ZabbixItem   Item correspondant
         */
        public function getByHost($hostid) {

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