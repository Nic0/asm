 <?php
    $I = new WebGuy($scenario);
    $I->wantTo('Unloged user should not see dashboard');
    $I->amOnPage('/logout');
    $I->amOnPage('/');
    $I->see('Login');
    $I->dontSee('Dashboard');
?>