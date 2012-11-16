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


    function convert_ldap_date ($ad_date) {

        if ($ad_date == 0) {
            return '0000-00-00';
        }

        $secsAfterADEpoch = $ad_date / (10000000);
        $AD2Unix=((1970-1601) * 365 - 3 + round((1970-1601)/4) ) * 86400;

        // Why -3 ?
        // "If the year is the last year of a century, eg. 1700, 1800, 1900, 2000,
        // then it is only a leap year if it is exactly divisible by 400.
        // Therefore, 1900 wasn't a leap year but 2000 was."

        $unixTimeStamp=intval($secsAfterADEpoch-$AD2Unix);
        $myDate = date("d/m/Y", $unixTimeStamp); // formatted date

        return $myDate;
    }

    $this->twig->addFunction('convert_ldap_date', new Twig_Function_Function('convert_ldap_date'));