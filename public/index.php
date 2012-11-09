<?php

    require_once '../lib/Loader.php';
    require_once '../lib/AsmConfig.php';
    require_once '../AsmRoute.php';

    Loader::load();

    new AsmRoute();