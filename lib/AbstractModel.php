<?php

    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;


    class AbstractModel {

        private $adapter;

        public function __construct($dbname) {
            $config = AsmConfig::getConfig();
            $this->adapter = new Zend\Db\Adapter\Adapter(array(
               'driver'   => $config->mysql->driver,
               'host'     => $config->mysql->host,
               'port'     => $config->mysql->port,
               'username' => $config->mysql->username,
               'password' => $config->mysql->password,
               'database' => $config->mysql->dbname->$dbname
            ));
        }


        public function select() {

            $sql = new Sql($this->adapter);
            $select = $sql->select();
            $select->from('hosts');
            $select->limit('10, 10');
            $selectString = $sql->getSqlStringForSqlObject($select);
            $ad = $this->adapter;
            $results = $this->adapter->query($selectString, $ad::QUERY_MODE_EXECUTE);
            debug($results, 'sql');

            return $results;
        }


    }