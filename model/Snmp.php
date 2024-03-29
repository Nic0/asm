<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Expression;

    /**
     * @brief identification d'un élément SNMP
     *
     * Toutes données permettant d'obtenir les information par rapport à une
     * valeur obtenue par SNMP
     */
    class Snmp extends PhpAsmModel {

        /** @brief Identifiant auto-incrémanté */
        public $id;
        /** @brief IP */
        public $ip;
        /** @brief Nom donnée qui serra affiché à l'utilisateur */
        public $name;
        /** @brief Community du SNMP, généralement "public" */
        public $community;
        /** @brief Object identifiant, spécifique à SNMP */
        public $oid;
        /** @brief seuil de warning */
        public $warning;
        /** @brief seuil d'alerte */
        public $alert;
        /** @brief valeur moyenne, servant à l'affichage */
        public $value;
        /** @brief point attribué en fonction du niveau atteint, servant à calculer la moyenne */
        public $point;
        public $coeff = 1;

        /**
         * @brief Sauvegarde d'un élément SNMP
         * @return None
         */
        public function save () {
            unset($this->value);
            unset($this->point);
            parent::save();
        }

        /**
         * @brief Obtient les statistiques de tout les SNMP
         * @return array Les statistiques
         */
        public function getStats () {
            $data = $this->getAll();

            $select = $this->sql
                ->select()
                ->from('snmp_input')
                ->group('snmp_id')
                ->where('unix_timestamp(date) > ' . (time() - (60*6)))
                ->columns(array(new Expression('(MAX(value) - MIN(value))/(TIMESTAMPDIFF(SECOND, MIN(date), MAX(date))) as value'), 'snmp_id'));

            $result = $this->select($select);

            $r = array();
            foreach ($result as $row) {
                $r[$row->snmp_id] = $row->value;
            }

            foreach ($data as $snmp) {
                if (isset($r[$snmp->id])) {
                    $snmp->value = $r[$snmp->id];
                    $this->setPoint($snmp);
                }
            }

            return $data;
        }

        /**
         * @brief Mise à jour d'un SNMP
         * @param  int      $id     identifiant du SNMP
         * @param  array    $values données à mettre à jours
         * @return None
         */
        public function update ($id, $values) {

            $update = $this->sql
                ->update()
                ->table('snmp')->where('id='.$id)->set(array(
                    'ip' => $values['ip'],
                    'name' => $values['name'],
                    'community' => $values['community'],
                    'oid' => $values['oid'],
                    'warning' => $values['warning'],
                    'coeff' => $values['coeff'],
                    'warning' => $values['warning']));
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }

        /**
         * @brief Attribue les points en fonction du niveau d'alerte levé
         * @param None
         *
         * Attribution de points en fonction de la couleur
         *   - vert:  0 point
         *   - jaune: 1 point
         *   - rouge: 2 points
         *
         */
        private function setPoint ($snmp) {
            $value = $snmp->value;
            $convert = 1024*1024;
            $warning = $snmp->warning * $convert;
            $alert = $snmp->alert * $convert;

            if ($warning < $alert) {
                if ($value <= $warning) {
                    $snmp->point = 0;
                } else if ($value <= $alert) {
                    $snmp->point = 1;
                } else {
                    $snmp->point = 2;
                }
            } else {
                if ($value >= $warning) {
                    $snmp->point = 0;
                } else if ($value >= $alert) {
                    $snmp->point = 1;
                } else {
                    $snmp->point = 2;
                }
            }
        }

    }