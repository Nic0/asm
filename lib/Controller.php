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

            $this->addInputFunctionPassword();
            $this->addInputFunctionColor();
            $this->addInputFunction();
            $this->addDisplayPriorityColor();

        }


        private function addInputFunctionColor() {
            function inputcolor ($label, $name, $value) {

                return
                '<div class="control-group">'
                    .'<label class="control-label" for="'.$name.'">'.$label.'</label>'
                    .'<div class="controls">'
                    .'<input class="color" type="text" name="'.$name.'"'
                           .'value="'.$value.'">'
                    .'</div>'
                .'</div>';

            }
            $this->twig->addFunction('inputcolor', new Twig_Function_Function('inputcolor'));
        }

        private function addInputFunctionPassword() {
            function inputpass ($label, $name, $value) {

                return
                '<div class="control-group">'
                    .'<label class="control-label" for="'.$name.'">'.$label.'</label>'
                    .'<div class="controls">'
                    .'<input type="password" name="'.$name.'"'
                           .'value="'.$value.'">'
                    .'</div>'
                .'</div>';

            }
            $this->twig->addFunction('inputpass', new Twig_Function_Function('inputpass'));
        }

        private function addInputFunction() {
            function input ($label, $name, $value) {

                return
                '<div class="control-group">'
                    .'<label class="control-label" for="'.$name.'">'.$label.'</label>'
                    .'<div class="controls">'
                    .'<input type="text" name="'.$name.'"'
                           .'value="'.$value.'">'
                    .'</div>'
                .'</div>';

            }
            $this->twig->addFunction('input', new Twig_Function_Function('input'));
        }

        private function addDisplayPriorityColor () {
            function displayPriority ($priority, $element) {
                $conf = AsmConfig::getConfig();
                $p = 'priority' . $priority;
                return "style='background-color: #". $conf->css->$element->$p ."' ";

            }
            $this->twig->addFunction('displayPriority', new Twig_Function_Function('displayPriority'));
        }

    }