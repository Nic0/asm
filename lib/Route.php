<?php
    require_once '../controller/StaticPageController.php';
    class Route {

        public $requestURL;
        public $match;

        public function __construct () {
            $this->getRouteArgs();
            $this->match = $this->matchRoute();
            if ($this->match !== null) {
                $this->callController();
            } else {
                $this->render404();
            }
        }


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
            debug($this->requestURL, 'url');
        }

        private function matchRoute () {
            foreach ($this->route as $key => $value) {
                if ($this->explodeUrl($key) == $this->requestURL) {
                    debug($value, 'route');
                    return $value;
                }
            }
        }

        private function explodeUrl ($url) {
            return array_values(array_filter(explode('/', $url)));
        }

        private function callController () {
            $controllerName = $this->match['controller'] . 'Controller';
            $controller = new $controllerName($this->getTemplate());
            $action = $this->match['action'];
            $controller->$action();
        }

        private function getTemplate () {
            if (isset($this->match['template'])) {
                return $this->match['controller'] . '/' .$this->match['template'] .'.html';
            } else {
                return null;
            }
        }

        private function render404 () {
            // TODO create 404
            echo "404";
        }

    }