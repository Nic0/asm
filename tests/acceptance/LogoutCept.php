 <?php
    $I = new WebGuy($scenario);
    $I->wantTo('check if I dont have the dashboard link as a visitor');
    $I->amOnPage('/logout');
    $I->amOnPage('/');
    $I->see('Login');
    $I->dontSeeLink('Dashboard');
?>