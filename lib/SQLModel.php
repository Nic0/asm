<?php

    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;
    require_once '../lib/Model.php';

    /**
     * @brief Abstraction pour l'accès à la base de donnée
     *
     * Accès direct à la base de donnée, via Zend\Db. Voir la documentation de
     * Zend pour plus de détail.
     *
     */
    class SQLModel extends Model {

        /**
         * @brief Nom de variable repris de Zend, correspondant à la liaison
         * avec la base de données
         */
        public $adapter;

        /**
         * @brief création de la liaison à la base de données
         * @param string $name nom de la base dans le fichier de config.
         *
         * le nom de la variable doit être repris dans le fichier de configuration
         * comme dans l'exemple suivant :
         *
         * ~~~~~~~~~~~~~
         *     mysql:
         *         nom:
         *             driver: Pdo_Mysql
         *             ...
         *~~~~~~~~~~~~~
         */
        public function __construct($name) {
            $mysql = AsmConfig::getConfig()->mysql->$name;

            $this->adapter = new Zend\Db\Adapter\Adapter(array(
               'driver'   => $mysql->driver,
               'host'     => $mysql->host,
               'port'     => $mysql->port,
               'username' => $mysql->username,
               'password' => $mysql->password,
               'database' => $mysql->dbname,
               'driver_options'  => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"')
            ));

        }

        /**
         * @brief envoie de la requête select à la base de données
         * @param  Sql\select $select requête select SQL, construit selon le
         *                            framework Zend\Db\Sql\Sql
         * @return Zend\Db\ResultSet\ResultSet résultat de la requềte sous forme
         *                                     de ResultSet propre à Zend
         * @note la valeur de retour peut être vu en utilisant debug:sql:true dans
         * le fichier de configuration
         */
        public function select($select) {

            $selectString = $this->sql->getSqlStringForSqlObject($select);
            $adapter = $this->adapter;
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            debug($results, 'sql');

            return $results;
        }


    }