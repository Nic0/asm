<?php
    require_once 'lib/Loader.php';
    require_once 'lib/AsmConfig.php';

    Loader::load();
    $config = AsmConfig::getConfig();
    debug($config, "config");


    require_once 'lib/AbstractModel.php';
    $am = new AbstractModel("zabbix");
    var_dump($am->select());
