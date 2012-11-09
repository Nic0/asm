<?php

    class Controller {

        public $template;

        public function __construct($template=null) {
            $this->template = $template;
        }

        public function render($data=array()) {
            $loader = new Twig_Loader_Filesystem('../view/');
            $twig = new Twig_Environment($loader);

            echo $twig->render($this->template, $data);
        }
    }