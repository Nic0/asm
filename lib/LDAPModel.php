<?php

    require_once '../lib/Model.php';

    /**
     * @brief Permet de se connecter à l'annuaire LDAP
     */
    class LDAPModel extends Model {

        /** @brief utilisateur (DN) pour la connection */
        private $username;
        /** @brief mot de passe assossié */
        private $pass;
        /** @brief host, ex: dc1.crbn.intra */
        private $host;
        /** @brief liaison à l'annuaire, utilisé de façon interne */
        private $connect;

        /**
         * @brief N'initialise que les identifiants pour la connection.
         */
        function __construct() {
            $config = AsmConfig::getConfig();
            $this->username = $config->ldap->username;
            $this->pass = $config->ldap->password;
        }

        /**
         * @brief envois la requête à l'annuaire.
         * @param  string $host       host, parce qu'on peut vouloir dc1, dc2 ou dc3
         * @param  string $filter     filtre à appliqué, syntaxe propre à LDAP
         * @param  array  $attributes éléments à récupérer, et ainsi ne pas récupérer tout
         *                            le sous-arbre.
         * @return array
         *
         * Voir la page http://www.php.net/manual/en/function.ldap-search.php
         * Pour plus d'informations sur le filtre (qui permet de ne prendre que
         * ce qu'on cherche), et attributes qui permet de récupérer que quelques
         * éléments, et non tout le sous-arbre de ce qu'on cherche.
         *
         * Comme l'objet badpassword sera construit en fonction des attributs retourné
         * il est intéressant de ne pas en avoir de trop.
         *
         */
        public function sendRequest ($host, $filter, $attributes) {
            $this->host = $host;
            $this->connectToHost();
            $sr=ldap_search($this->connect,"dc=crbn,dc=intra", $filter, $attributes);
            $result = ldap_get_entries($this->connect, $sr);
            ldap_close($this->connect);

            return $result;
        }


        /**
         * @brief connection et liaison (connect, bind) à LDAP, permet de séparer
         * cette étape.
         * @return None
         */
        private function connectToHost () {
            $this->connect=ldap_connect($this->host);

            if ($this->connect) {
                ldap_set_option($this->connect, LDAP_OPT_REFERRALS, 0);
                ldap_set_option($this->connect, LDAP_OPT_PROTOCOL_VERSION, 3);

                ldap_bind($this->connect, $this->username, $this->pass);
            }
        }
    }