<?php

    require_once '../lib/ZabbixModel.php';

    use Zend\Db\Sql\Sql;

    /**
     * @brief Modèle pour un évènement (trigger) de Zabbix
     */
    class ZabbixEvent extends ZabbixModel {

        public $eventid;        /** @brief ID de l'évènement (non utilisé) */
        public $host;           /** @brief Nom de l'hôte */
        public $description;    /** @brief Descriptif */
        public $priority;       /** @brief Niveau de priorité (1-6) */
        public $lastchange;     /** @brief Date de la dernière modification */
        public $acknowledged;

        /**
         * @brief Permet d'obtenir les X dernier évènements déclanché.
         * @return array Tableau d'objet ZabbixEvent
         *
         * La requête est construite avec la jointure de 5 tables,
         * La requête est proche de la SQL suivante (à quleques modifs près)
         *
         * ~~~~~~~~~~~~~~
         *  SELECT host, t.description, f.triggerid, e.acknowledged, t.value, i.lastvalue
         *    FROM triggers t
         *   INNER JOIN functions f ON ( f.triggerid = t.triggerid )
         *   INNER JOIN items i ON ( i.itemid = f.itemid )
         *   INNER JOIN hosts ON ( i.hostid = hosts.hostid )
         *   INNER JOIN events e ON ( e.objectid = t.triggerid )
         *   WHERE (e.object-0)=0
         *     AND hosts.status =0
         *     AND i.status =0
         *     AND t.status =0
         *   GROUP BY f.triggerid
         *   ORDER BY t.lastchange DESC
         *~~~~~~~~~~~~~~
         *
         * Voir GLPITicket pour plus de détails
         */
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
                   ->order('clock DESC')
                   //->group('acknowledged')
                   //->group('triggerid')
                   ->where(array(
                        'h.status' => 0,
                        'i.status' => 0,
                        't.status' => 0,
                        'e.object' => 0,
                        't.value' => 1))
                   ->columns(array('eventid', 'clock', 'acknowledged'));
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