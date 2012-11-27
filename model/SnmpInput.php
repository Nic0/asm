<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    class SnmpInput extends PhpAsmModel {

        public $id;
        public $snmp_id;
        public $value;
        public $date;

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

    }