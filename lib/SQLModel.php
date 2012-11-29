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

        public function getById ($id, $class=null) {

            if ($class === null) $class = strtolower(get_called_class());

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from($class)->where("id=".$id);
            $results = $this->select($select);

            return $this->createObjectFromSingleData($results[0]);

        }

        public function getAll ($table=null, $class=null) {

            if ($class === null) $class = get_called_class();
            if ($table === null) $table = strtolower(get_called_class());

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select()->from($table);

            return $this->createObjectFromArrayData($this->select($select), $class);
        }

        public function delete ($id, $table=null) {

            if ($table === null) $table = strtolower(get_called_class());

            $this->sql = new Sql($this->adapter);
            $delete = $this->sql->delete()->from($table)->where('id='.$id);
            $statement = $this->sql->prepareStatementForSqlObject($delete);
            $result = $statement->execute();
        }

        public function create ($post) {
            return $this->createObjectFromSingleData($post);
        }

        public function save () {
            $objectArray = (array) $this;
            unset($objectArray['adapter']);
            $this->sql = new Sql($this->adapter);
            $insert = $this->sql->insert();
            $insert->into(strtolower(get_called_class()))
                   ->columns(array_keys($objectArray))
                   ->values($objectArray);

            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        }

    }