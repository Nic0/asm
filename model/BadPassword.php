<?php

    require_once '../lib/LDAPModel.php';

    /**
     * @brief Gestion des comptes bloqués sur LDAP
     */
    class BadPassword extends LDAPModel {

        /** @brief CN (Common Name) du compte bloqué */
        public $cn;
        /** @brief Nom (login) du compte bloqué */
        public $samaccountname;
        /**
         *  @brief Date à laquel le compte à été bloqué.
         *
         * Le format de la date ressemble à un timestamp, mais est spécifique
         * à LDAP. La convertion ldap_date -> date réel se fait dans la vue,
         * avec l'helper.php convert_ldap_date.
        */
        public $lockouttime;

        /** @brief  */
        private $attributes = array('cn', 'samaccountname', 'lockouttime');
        /** @brief  */
        static private $filter = "(&(objectClass=user)(!(objectClass=computer))(badPwdCount>=5))";

        /**
         * @brief Retourne l'ensemble des comptes bloqués (sur les trois hosts)
         * @return array(BadPassword) les comptes bloqués
         */
        public function getAll () {

            $data = $this->cleanUpData($this->fetchEveryHost());
            $result = $this->createObjectFromArrayData($data);

            return $result;
        }

        /**
         * @brief On veut merger les trois hosts ldap, parce que la synch n'est pas
         * toujours bonne, et qu'on veut être certain de tous les avoir.
         * @return array données brutes
         */
        private function fetchEveryHost () {
            $data = $this->sendRequest('dc1.crbn.intra', self::$filter, $this->attributes);
            $data = array_merge($data, $this->sendRequest('dc2.crbn.intra', self::$filter, $this->attributes));
            $data = array_merge($data, $this->sendRequest('dc3.crbn.intra', self::$filter, $this->attributes));

            return $data;
        }

        /**
         * @brief Netoyage des données, pour pouvoir construire les objets correspondant
         * proprement par la suite
         * @param  array $data données brutes
         * @return array       données netoyés et trié par date de bloquage
         */
        private function cleanUpData ($data) {
            unset($data['count']);
            for ($i=0; $i < sizeOf($data); $i++) {
                $data[$i] = $this->array_keep($data[$i], $this->attributes);
                foreach ($this->attributes as $value) {
                    $data[$i][$value] = $data[$i][$value][0];
                }
            }

            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
            return $this->sort_by_lockouttime($data);
        }

        /**
         * @brief Trie par la valeur lockouttime
         * @param  array $data données
         * @return array       données triées
         */
        private function sort_by_lockouttime ($data) {

            function custom($a, $b) {
                return strcmp($b["lockouttime"], $a["lockouttime"]);
            }
            usort($data, 'custom');
            return $data;
        }

        /**
         * @brief Permet de garder d'un tableau associatif, que les éléments voulus
         * @param  array $array tableau associatif
         * @param  array $keys  clées
         * @return array        tableau avec les bonnes clés/valeurs
         */
        private function array_keep($array, $keys) {
            return array_intersect_key($array, array_fill_keys($keys, null));
        }
    }