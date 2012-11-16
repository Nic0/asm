<?php

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

    function displayPriority ($priority, $element) {
        $conf = AsmConfig::getConfig();
        $p = 'priority' . $priority;
        return "style='background-color: #". $conf->css->$element->$p ."' ";

    }
    $this->twig->addFunction('displayPriority', new Twig_Function_Function('displayPriority'));

    function submit ($label) {
        return
        '<div class="control-group">'
            .'<div class="controls">'
                .'<button type="submit" class="btn">'.$label.'</button>'
            .'</div>'
        .'</div>';
    }

    $this->twig->addFunction('submit', new Twig_Function_Function('submit'));

    function resetFlash () {
        $_SESSION['flash'] = null;
    }

    $this->twig->addFunction('resetFlash', new Twig_Function_Function('resetFlash'));