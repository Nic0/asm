<?php

    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;


    class AbstractModel {

        public $adapter;

        public function __construct($dbname) {
            $mysql = AsmConfig::getConfig()->mysql;
            $this->adapter = new Zend\Db\Adapter\Adapter(array(
               'driver'   => $mysql->driver,
               'host'     => $mysql->host,
               'port'     => $mysql->port,
               'username' => $mysql->username,
               'password' => $mysql->password,
               'database' => $mysql->dbname->$dbname,
               'options'  => array( 'charset' => 'utf8' )
            ));
        }


        public function select($select) {

            $selectString = $this->sql->getSqlStringForSqlObject($select);
            $adapter = $this->adapter;
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            debug($results, 'sql');

            return $results;
        }


    }