<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;

    /**
     * @brief Gestion d'un Host par Zabbix
     */
    class ZabbixHost extends ZabbixModel {

        /** @brief identifiant de l'host */
        public $hostid;
        /** @brief Nom de l'host */
        public $host;

        /**
         * @brief Obtient tout les hosts surveillés par Zabbix
         * @return array tableau d'objet ZabbixHost comportant la totalité des hosts
         */
        public function getAll() {

            $select = $this->sql->select();
            $select->from('hosts')->columns(array('hostid', 'host'));

            $result = $this->select($select);
            return $this->createObjectFromArrayData($result);

        }
    }