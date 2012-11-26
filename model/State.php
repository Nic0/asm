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

        public function getAll () {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from('states');
            $results = $this->select($select);

            return $this->createObjectFromArrayData($results);
        }

        public function del ($id) {
            $this->sql = new Sql($this->adapter);
            $delete = $this->sql->delete();
            $delete->from('states')->where('id='.$id);
            $statement = $this->sql->prepareStatementForSqlObject($delete);
            $result = $statement->execute();
        }

    }