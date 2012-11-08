<?php

    class Controller {

        public $template;

        public function __construct($template=null) {
            $this->template = $template;
        }

        public function render($data) {
            // TODO twig render
        }

    }