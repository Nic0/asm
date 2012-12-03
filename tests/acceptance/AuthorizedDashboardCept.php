<?php
    $I = new WebGuy($scenario);
    $I->wantTo('Access the dashboard as a logged user');
    $I->amOnPage('/login');
    $I->fillField('username','paris_n');
    $I->fillField('password','paris569');
    $I->click("S'identifier");

    $I->amOnPage('/dashboard');
    // On doit pas avoir le message demandant à s'identifier
    $I->dontSee('Vous devez vous identifier ');
    // Les titres des sections
    $I->see('Tickets GLPI');
    $I->see('Triggers levés par Zabbix');
    $I->see('Comptes bloqués sur LDAP');
    // la section glpi soit (normalement) avoir un ticket de la date d'aujourd'hui
    $I->see(date('d/m/y'), '#glpi');
?>