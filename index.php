<?php
    require_once 'lib/Loader.php';
    require_once 'lib/Config.php';

    Loader::load();
    $config = Config::getConfig();
    debug($config, "conf");