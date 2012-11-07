<?php

    function debug($data, $param=null) {
        $conf = Config::getConfig();

        if ($param === null || $conf['debug'][$param]) {
            echo ">>> debug de " . $param;
            var_dump($data);
            echo "<<< fin debug de " . $param . "<br /><br />";
        }
    }