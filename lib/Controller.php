<?php

    /**
     * @brief Classe générale pour les controlleurs
     */
    class Controller {

        public $template; /** @brief Template dont le rendu sera généré */
        public $data = array(); /** @brief Les données sous forme d'array qui seront envoyé au template */

        /**
         * @brief Constructeur permettant de mettre le template en place
         * @param string $template Nom du template
         */
        public function __construct($template=null) {
            $this->template = $template;
            $this->data['config'] = AsmConfig::getConfig();
        }

        /**
         * @brief Rendu du template avec Twig
         * @param  array  $data Données à passer au template
         * @return None
         *
         * Le rendu se fait à l'aide de Twig (template utilisé pour Synfony2)
         * Se référer à leur documentation pour les deux lignes le concernant.
         * $this->data sera automatiquement envoyé au template.
         *
         */
        public function render() {
            $this->handleTwig();
            echo $this->twig->render($this->template, $this->data);
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

        /**
         * @brief charge et rajoute les helpers pour Twig
         * @return None
         */
        private function handleTwig() {
            $loader = new Twig_Loader_Filesystem('../view/');
            $this->twig = new Twig_Environment($loader);
            require_once '../lib/helper.php';
        }

        /**
         * @brief Simple redirection
         * @param  string $url location vers lequel on souhaite être redirigé
         * @return None
         */
        public function redirect ($url) {
            header('Location: ' . $url);
        }

        public function addData ($label, $data) {
            $this->data[$label] = $data;
            return $this;
        }
    }