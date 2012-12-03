<?php
    $I = new WebGuy($scenario);
    $I->wantTo('Access the dashboard as an unlogged user');
    $I->amOnPage('/logout');
    $I->amOnPage('/dashboard');
    $I->see('Vous devez vous identifier ');
    $I->dontSee('Tickets GLPI');
    $I->dontSee('Triggers levés par Zabbix');
    $I->dontSee('Comptes bloqués sur LDAP');
?>