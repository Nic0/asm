<?php

    require_once '../lib/PhpAsmModel.php';

    /**
     * @brief Stockage des données pour un SNMP donné
     */
    class SnmpInput extends PhpAsmModel {

        /** @brief Identifiant de la donnée */
        public $id;
        /** @brief Relation avec la classe Snmp */
        public $snmp_id;
        /** @brief Valeur brute stockée */
        public $value;
        /** @brief date à laquel la donnée a été prise */
        public $date;

        /**
         * @brief  Fait la requête SNMP et l'enregistre dans la base snmp_input
         * @param  Snmp $snmp objet Snmp dont les données doivent être recueillis
         * @return None
         */
        public function save ($snmp) {

            $value = snmp_get_value($snmp->ip, $snmp->community, $snmp->oid);

            $this->execute($this->sql
                ->insert()
                ->into('snmp_input')
                ->columns(array('snmp_id', 'value'))
                ->values(array(
                    'snmp_id'   => $snmp->id,
                    'value'     => $value
                ))

            );
        }

        /**
         * @brief Élimine de la base les données dépassés en date
         * @return None
         */
        public function purge () {

            $this->execute($this->sql
                ->delete()
                ->from('snmp_input')
                ->where('unix_timestamp(date) < ' . (time() - (60*60)))
            );
        }

        public function getLast ($snmp_id) {
            $select = $this->sql
                ->select()
                ->from('snmp_input')
                ->order('date DESC')
                ->where('snmp_id = ' .$snmp_id)
                ->limit(20)
                ->columns(array('value', 'date'));


            $result = $this->select($select);

            $data = array();
            foreach ($result as $row) {
                $snmp = new SnmpInput();
                $snmp->value = $row->value;
                $snmp->date = $row->date;
                unset($snmp->snmp_id);
                unset($snmp->adapter);
                unset($snmp->sql);
                unset($snmp->id);
                $data[] = $snmp;
            }
            return $data;
        }

    }