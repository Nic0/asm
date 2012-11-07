<?php
    require_once 'lib/Loader.php';
    require_once 'lib/AsmConfig.php';

    Loader::load();
    $config = AsmConfig::getConfig();
    debug($config, "config");


    require_once 'model/ZabbixEvent.php';
    $am = new ZabbixEvent();
    var_dump($am->getLast());
