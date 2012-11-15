<?php

    require_once '../lib/Loader.php';
    require_once '../AsmRoute.php';

    session_start();
    Loader::load();
    new AsmRoute();