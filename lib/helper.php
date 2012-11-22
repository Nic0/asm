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

    /**
     * @brief Efface le message `flash` une fois que celui-ci a été effacé
     * @return None
     */
    function resetFlash () {
        $_SESSION['flash'] = null;
    }

    $this->twig->addFunction('resetFlash', new Twig_Function_Function('resetFlash'));


    /**
     * @brief Convertion d'une date ldap en date réel
     * @param  long  $ad_date date au format ldap
     * @return string          date au format dd/mm/yyyy
     */
    function convert_ldap_date ($ad_date) {

        if ($ad_date == 0) {
            return '0000-00-00';
        }

        $ad_date=$ad_date /10000000-11644473600;

        $myDate = date("d/m/Y H:i", $ad_date); // formatted date

        return $myDate;
    }

    $this->twig->addFunction('convert_ldap_date', new Twig_Function_Function('convert_ldap_date'));


    /**
     * @brief Un utilisateur est-il loggué ?
     * @return boolean Oui si un utilisateur est loggué
     */
    function logged () {
        return (isset($_SESSION['user']));
    }
    $this->twig->addFunction('logged', new Twig_Function_Function('logged'));

    /**
     * @brief Obtiens le nom (login) de l'utilisateur loggué
     * @return string nom
     */
    function loggedName () {
        return $_SESSION['user'];
    }
    $this->twig->addFunction('loggedName', new Twig_Function_Function('loggedName'));