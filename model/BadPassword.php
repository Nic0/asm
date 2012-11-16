<?php

    require_once '../lib/LDAPModel.php';

    class BadPassword extends LDAPModel {

        static public $filter = "(&(objectClass=user)(!(objectClass=computer))(badPwdCount>=5))";
        public $cn;
        public $distinguishedname;
        public $samaccountname;
        public $attributes = array('cn', 'distinguishedname', 'samaccountname');

        public function getAll () {

            $data = $this->cleanUpData($this->fetchEveryHost());
            $result = $this->createObjectFromArrayData($data);

            return $result;
        }


        private function fetchEveryHost () {
            $data = $this->sendRequest('dc1.crbn.intra', self::$filter, $this->attributes);
            $data = array_merge($data, $this->sendRequest('dc2.crbn.intra', self::$filter, $this->attributes));
            $data = array_merge($data, $this->sendRequest('dc3.crbn.intra', self::$filter, $this->attributes));

            return $data;
        }

        private function cleanUpData ($data) {
            unset($data['count']);
            for ($i=0; $i < sizeOf($data); $i++) {
                $data[$i] = $this->array_keep($data[$i], $this->attributes);
                foreach ($this->attributes as $value) {
                    $data[$i][$value] = $data[$i][$value][0];
                }
            }

            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

            return $data;
        }

        private function array_keep($array, $keys) {
            return array_intersect_key($array, array_fill_keys($keys, null));
        }
    }