<?php

use tests\codeception\_pages\admin\LoginPage;

$I = new FunctionalTester($scenario);

$I->wantTo('test login');
$page = LoginPage::openBy($I);

$I->amGoingTo('test empty credentials');
$page->login('', '');
$I->expectTo('see validations errors');
$I->see('Password cannot be blank', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test empty username');
$page->login('', 'fghfgh');
$I->expectTo('see validations errors');
$I->see('Username cannot be blank', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test empty password');
$page->login('demo', '');
$I->expectTo('see validations errors');
$I->see('Password cannot be blank', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test too short password');
$page->login('demo', 'fgh');
$I->expectTo('see validations errors');
$I->see('Incorrect username or password', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test wrong credentials');
$page->login('demo', 'qrerop2j34');
$I->expectTo('see validations errors');
$I->see('Incorrect username or password', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test blocked credentials');
$page->login('example-blocked', '123123');
$I->expectTo('see validations errors');
$I->see('Your account has been suspended', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test deleted credentials');
$page->login('example-deleted', '123123');
$I->expectTo('see validations errors');
$I->see('Your account has been deleted', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test correct credentials');
$page->login('superuser', 'fghfgh');
$I->expectTo('see control panel');
$I->see('Welcome');
