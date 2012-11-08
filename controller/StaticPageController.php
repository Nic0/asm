<?php

    require_once '../lib/Controller.php';

    class StaticPageController extends Controller {

        public function home () {
            $this->render();
        }
    }