<?php

    require_once '../lib/ZabbixModel.php';
    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    /**
     * @brief Etat d'un élément Zabbix (base phpasm)
     */
    class State extends PhpAsmModel {

        /** @brief Identifant de l'état */
        public $id;
        /**
         * @brief Nom à affiche
         * @var string
         * @note À l'affichage, si le nom n'est pas renseigné, c'est la concaténation
         *       de la description de l'item et de l'host qui serra affiché.
         */
        public $name;
        /** @brief identifiant de l'host correspondant */
        public $hostid;
        /** @brief identifiant de l'item correspondant */
        public $itemid;
        /** @brief niveau du seuil de warning */
        public $warning;
        /** @brief niveau du seuil d'alert */
        public $alert;
        /** @brief coefficient, ou pondération à affecter */
        public $coeff;

        /**
         * @brief Sauvegarde d'un état dans la table states
         * @return None
         */
        public function save () {

            $this->sql = new Sql($this->adapter);
            $insert = $this->sql->insert();
            $insert->into('state')
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

        /**
         * @brief Mise à jour d'un état
         * @param  int   $id     identifiant de l'état
         * @param  array $values valuers à mettre à jour
         * @return None
         */
        public function update ($id, $values) {
            $this->sql = new Sql($this->adapter);
            $update = $this->sql->update();

            $update->table('state')->where('id='.$id)->set(array(
                'name' => $values['name'],
                'warning' => $values['warning'],
                'alert' => $values['alert'],
                'coeff' => $values['coeff']));
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }

    }