<?php

    require_once '../lib/AbstractModel.php';

    class Model extends AbstractModel {

        public function createObjectFromSingleData($data, $class=null) {
            if ($class == null) {
                $class = get_called_class();
            }

            // classe dynamique. Toute classe modèle doit avoir
            // un constructeur vide
            $object = new $class();

            foreach ($data as $key => $value) {
                // utilisation d'une variable variable pour les données membres
                $object->$key = $value;
            }

            return $object;
        }

        public function createObjectFromArrayData($array, $class=null) {
            $result = array();
            foreach ($array as $key => $value) {
                $result[] = $this->createObjectFromSingleData($value, $class);
            }

            return $result;
        }

    }