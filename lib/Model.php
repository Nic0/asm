<?php

    require_once '../lib/AbstractModel.php';

    /**
     * @brief méthodes commune à tout les modèles.
     */
    class Model extends AbstractModel {

        /**
         * @brief Construction d'un objet grâce à un tableau, ou un autre objet.
         * @param  mixed  $data  Array ou ArrayObject provenant d'une requête
         * @param  string $class Nom de la classe à instancier, si non renseigné,
         *                       c'est la classe fille qui sera instancié
         * @return object        Instance de l'objet voulu
         *
         * Avec Zend, c'est un object *ArrayObject* qui est renvoyé. La méthode
         * instancie l'objet en question, et remplis chaque données membre en fonction
         * des éléments de l'objet $data.
         *
         */
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
        /**
         * @brief Permet de construire un tableau d'object en utilisant createObjectFromSingleData
         * @param  mixed  $array array ou ArrayObject provenant d'une requête
         * @param  string $class Nom de la classe à instancier, si non renseigné,
         *                       c'est la classe fille qui sera instancié
         * @return array         Tableau d'instance des objets voulu
         *
         * La méthode fait une simple boucle de createObjectFromSingleData, pour retourner
         * un tableau
         *
         */
        public function createObjectFromArrayData($array, $class=null) {
            $result = array();
            foreach ($array as $key => $value) {
                $result[] = $this->createObjectFromSingleData($value, $class);
            }

            return $result;
        }

    }