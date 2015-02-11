<?php

use tests\codeception\_pages\admin\LoginPage;

$I = new FunctionalTester($scenario);
$I->wantTo('admin, login');

$page = LoginPage::openBy($I);

$I->see('login-button');

$I->amGoingTo('try to login with empty credentials');
$page->login('', '');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with empty username');
$page->login('', 'fghfgh');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with empty password');
$page->login('editor', '');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with wrong credentials');
$page->login('editor', 'qrerop2j34');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to login with correct credentials');
$page->login('admin', 'fghfgh');
$I->expectTo('see welcome');
$I->see('Добро пожаловать');
