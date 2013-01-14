<?php

    /**
     * @brief traitement de la route renseignée dans la classe fille
     */
    class Route {

        /** @brief URL demandé (sans http...) */
        public $requestURL;
        /** @brief ensemble controller/action/template qui match à la route */
        public $match;
        public $arg = null;

        /**
         * @brief Trouver les controller/action/template, et appeler en fonction
         */
        public function __construct () {
            $this->getRouteArgs();
            $this->match = $this->matchRoute();
            if ($this->match !== null) {
                $this->callController();
            } else {
                $this->render404();
            }
        }

        /**
         * @brief Découpage de l'url
         *
         * On retire l'élément renseigné dans basurl de la config si besoin
         *
         * @return string URL
         *
         * @note Le contenu peut être visible en activant la config `debug:url:true`
         */
        private function getRouteArgs () {
            $config = AsmConfig::getConfig();
            $requestURL = $this->explodeUrl($_SERVER['REQUEST_URI']);
            $baseurl = $this->explodeUrl('/', $config->install->baseurl);

            for ($i=0; $i < sizeof($baseurl); $i++) {
                if ($baseurl[$i] == $requestURL[$i]) {
                    unset($requestURL[$i]);
                }
            }

            $this->requestURL = array_values($requestURL);

            if (sizeof($this->requestURL) == 3) {
                $this->arg = $this->requestURL[2];
                unset($this->requestURL[2]);
            }

            debug($this->requestURL, 'url');
        }

        /**
         * @brief On cherche la route correspondant, c'est a dire l'ensemble
         * controller/action/template.
         *
         * @return array La route
         *
         * @note Visible en activant dans la config `debug:route:true`
         */
        private function matchRoute () {
            foreach ($this->route as $key => $value) {
                if ($this->explodeUrl($key) == $this->requestURL) {
                    debug($value, 'route');
                    return $value;
                }
            }
        }

        /**
         * @brief Utilitaire pour retourner un tableau
         *
         * @param  string $url URL à découper
         * @return array       URL découpé
         *
         * L'utilité est :
         * - explose: découpe l'url en tableau, en fonction de /
         * - array_filter: suppression des espaces vides
         * - array_values: permet de repartir sur un indice 0, 1, 2... en cas de
         *                 suppression
         */
        private function explodeUrl ($url) {
            return array_values(array_filter(explode('/', $url)));
        }

        /**
         * @brief Appel du controlleur/action
         * @return None
         *
         * On veut récupérer le controller de la route, l'instancier avec comme
         * argument le template, puis, appeler sa méthode (action) correspondante.
         */
        private function callController () {
            $controllerName = $this->match['controller'] . 'Controller';
            $controller = new $controllerName($this->getTemplate());
            $action = $this->match['action'];
            $controller->$action($this->arg);
        }

        /**
         * @brief Construit le path du template
         * @return string path du template
         *
         * Concaténation du controlleur, du nom du template et .html.
         *
         */
        private function getTemplate () {
            if (isset($this->match['template'])) {
                return $this->match['controller'] . '/' .$this->match['template'] .'.html';
            } else {
                return null;
            }
        }

        /**
         * @brief Créer un rendu pour une page 404
         * @return None
         * @todo Faire une vrai page 404
         */
        private function render404 () {
            // TODO create 404
            echo "404";
        }

    }