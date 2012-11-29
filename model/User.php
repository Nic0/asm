<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Authentication\AuthenticationService;
    use Zend\Authentication\Adapter\Ldap as AuthAdapter;

    /**
     * @brief Classe pour un utilisateur
     *
     * L'authentification se fait sur LDAP, mais la gestion de rôle dans
     * l'application se fait sur la base de données phpasm
     */
    class User extends PhpAsmModel {

        /**
         * @brief Authentification par LDAP s'appuyant sur Zend2
         * @param  string $username Login de l'utilisateur
         * @param  string $password Mot de passe de l'utilisateur
         * @return int Valeur relatif à l'authentification et Zend
         */
        public function authenticate ($username, $password) {
            $auth = new AuthenticationService();

            $config = AsmConfig::getConfig();
            $options = array('prod' => array(
                'host'              => $config->ldap->host,
                'port'              => $config->ldap->port,
                'username'          => $config->ldap->username,
                'password'          => $config->ldap->password,
                'baseDn'            => $config->ldap->basedn,
                'bindRequiresDn'    => true,
                'useSsl'            => $config->ldap->usessl,
                'accountFilterFormat' => '(&(objectClass=user)(!(objectClass=computer))(sAMAccountname='.$username.'))'
            ));

            $adapter = new AuthAdapter($options, $username, $password);
            $result = $auth->authenticate($adapter);
            debug($result, 'auth');

            return $result;
        }

        /**
         * @brief Obtient le rôle enregistré dans la base phpasm en fonction de son login
         * @param  string $login Login de l'utilisateur
         * @return string        Rôle de l'utilisateur (admin, null)
         */
        public function getRole ($login) {
            $select = $this->sql
                ->select()
                ->from('user')
                ->where("login='".$login."'")
                ->columns(array('role'));
            $result = $this->select($select);

            return $result->current()->role;
        }
    }