<?php
    $I = new WebGuy($scenario);
    $I->wantTo('log in as regular user');
    $I->amOnPage('/logout');
    $I->amOnPage('/login');
    $I->see('Login');

    $I->fillField('username','paris_n');
    $I->fillField('password','xxxxxxx');
    $I->click("S'identifier");
    $I->see('Logout (paris_n)');
?>

