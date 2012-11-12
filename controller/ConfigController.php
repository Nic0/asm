<?php

    require_once '../lib/Controller.php';
    require_once '../lib/AsmConfig.php';

    class ConfigController extends Controller {

        public function getJsonConfig () {
            $json = AsmConfig::getJsonConfig();
            $this->renderJson($json);
        }

    }