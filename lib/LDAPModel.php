<?php

    require_once '../lib/Model.php';

    class LDAPModel extends Model {

        public $rdn;
        public $pass;
        public $host;
        public $connect;

        function __construct() {
            $this->rdn = "CN=Binding PHPAsm,OU=Comptes Gestion DSI,OU=CRBN,DC=crbn,DC=intra";
            $this->pass = 'Ni4Ief3jo';
        }

        private function connectToHost () {
            $this->connect=ldap_connect($this->host);

            if ($this->connect) {
                ldap_set_option($this->connect, LDAP_OPT_REFERRALS, 0);
                ldap_set_option($this->connect, LDAP_OPT_PROTOCOL_VERSION, 3);

                ldap_bind($this->connect, $this->rdn, $this->pass);
            }
        }

        public function sendRequest ($host, $filter, $attributes) {
            $this->host = $host;
            $this->connectToHost();
            $sr=ldap_search($this->connect,"dc=crbn,dc=intra", $filter, $attributes);
            $result = ldap_get_entries($this->connect, $sr);
            ldap_close($this->connect);

            return $result;
        }
    }

?>