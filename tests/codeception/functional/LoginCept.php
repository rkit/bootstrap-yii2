<?php

use tests\codeception\_pages\LoginPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that login works');

$page = LoginPage::openBy($I);

$I->see('login-button');

$I->amGoingTo('try to login with empty credentials');
$page->login('', '');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with wrong credentials');
$page->login('admin@example.com', 'qrerop2j34');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with correct credentials');
$page->login('admin@example.com', 'fghfgh');
$I->expectTo('see user info');
$I->see('logout');
$I->dontSee('login');
