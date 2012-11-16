<?php

    require_once '../lib/Model.php';

    class LDAPModel extends Model {

        private $username;
        private $pass;
        private $host;
        private $connect;

        function __construct() {
            $config = AsmConfig::getConfig();
            $this->username = $config->ldap->username;
            $this->pass = $config->ldap->password;
        }

        public function sendRequest ($host, $filter, $attributes) {
            $this->host = $host;
            $this->connectToHost();
            $sr=ldap_search($this->connect,"dc=crbn,dc=intra", $filter, $attributes);
            $result = ldap_get_entries($this->connect, $sr);
            ldap_close($this->connect);

            return $result;
        }

        private function connectToHost () {
            $this->connect=ldap_connect($this->host);

            if ($this->connect) {
                ldap_set_option($this->connect, LDAP_OPT_REFERRALS, 0);
                ldap_set_option($this->connect, LDAP_OPT_PROTOCOL_VERSION, 3);

                ldap_bind($this->connect, $this->username, $this->pass);
            }
        }
    }