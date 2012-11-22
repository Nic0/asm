<?php

    require_once '../lib/PhpAsmModel.php';

    use Zend\Db\Sql\Sql;
    use Zend\Authentication\AuthenticationService;
    use Zend\Authentication\Adapter\Ldap as AuthAdapter;

    class User extends PhpAsmModel {

        public function authenticate ($username, $password) {
            $auth = new AuthenticationService();

            $config = AsmConfig::getConfig();

            $options = array('prod' => array(
                'host' => $config->ldap->host,
                'port' => $config->ldap->port,
                'username' => $config->ldap->username,
                'password' => $config->ldap->password,
                'baseDn' => $config->ldap->basedn,
                'bindRequiresDn' => true,
                'useSsl' => $config->ldap->usessl,
                'accountFilterFormat' => '(&(objectClass=user)(!(objectClass=computer))(sAMAccountname='.$username.'))'
            ));

            $adapter = new AuthAdapter($options,
                                       $username,
                                       $password);

            $result = $auth->authenticate($adapter);
            debug($result, 'auth');

            return $result;
        }

        public function getRole ($login) {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from('user')->where("login='".$login."'")->columns(array('role'));
            $result = $this->select($select);

            foreach ($result as $value) {
                return $value->role;
            }

            return null;
        }
    }