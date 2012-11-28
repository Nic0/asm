<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;
    use Zend\Db\Sql\Expression;

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

            $this->sql = new Sql($this->adapter);
            $insert = $this->sql->insert();
            $insert->into('snmp_input')
                   ->columns(array('snmp_id', 'value'))
                   ->values(array(
                        'snmp_id'   => $snmp->id,
                        'value'     => $value
                    ));

            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        }

        /**
         * @brief Élimine de la base les données dépassés en date
         * @return None
         */
        public function purge () {
            $this->sql = new Sql($this->adapter);
            $delete = $this->sql->delete();
            $delete->from('snmp_input')->where('unix_timestamp(date) < ' . (time() - (60*60)));
            $statement = $this->sql->prepareStatementForSqlObject($delete);
            $result = $statement->execute();
        }

    }