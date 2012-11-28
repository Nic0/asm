<?php

    require_once '../lib/ZabbixModel.php';
    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    class State extends PhpAsmModel {

        public $id;
        public $name;
        public $hostid;
        public $itemid;
        public $warning;
        public $alert;
        public $coeff;

        public function save () {

            $this->sql = new Sql($this->adapter);
            $insert = $this->sql->insert();
            $insert->into('states')
                   ->columns(array('name', 'hostid', 'itemid', 'warning', 'alert', 'coeff'))
                   ->values(array(
                        'name'      => $this->name,
                        'hostid'    => $this->hostid,
                        'itemid'    => $this->itemid,
                        'warning'   => $this->warning,
                        'alert'     => $this->alert,
                        'coeff'     => $this->coeff
                    ));

            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        }

        public function update ($id, $values) {
            $this->sql = new Sql($this->adapter);
            $update = $this->sql->update();

            $update->table('states')->where('id='.$id)->set(array(
                'name' => $values['name'],
                'warning' => $values['warning'],
                'alert' => $values['alert'],
                'coeff' => $values['coeff']));
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }

    }