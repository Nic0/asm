<?php

    /**
     * @brief Classe générale pour les controlleurs
     */
    class Controller {

        public $template; /** @brief Template dont le rendu sera généré */

        /**
         * @brief Constructeur permettant de mettre le template en place
         * @param string $template Nom du template
         */
        public function __construct($template=null) {
            $this->template = $template;
        }

        /**
         * @brief Rendu du template avec Twig
         * @param  array  $data Données à passer au template
         * @return None
         *
         * Le rendu se fait à l'aide de Twig (template utilisé pour Synfony2)
         * Se référer à leur documentation pour les deux lignes le concernant.
         *
         */
        public function render($data=array()) {
            $this->handleTwig();
            echo $this->twig->render($this->template, $data);
        }

        /**
         * @brief Rendu pour des données json
         * @param  string $json donnée json à utiliser
         * @return None
         */
        public function renderJson ($json) {
            header('Content-Type: application/json');
            echo $json;
        }

        private function handleTwig() {
            $loader = new Twig_Loader_Filesystem('../view/');
            $this->twig = new Twig_Environment($loader);
            require_once '../lib/helper.php';
        }

        public function redirect ($url) {
            header('Location: ' . $url);
        }
    }