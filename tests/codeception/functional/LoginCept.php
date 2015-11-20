<?php

use tests\codeception\_pages\LoginPage;

$I = new FunctionalTester($scenario);

$I->wantTo('test login to control panel');
$page = LoginPage::openBy($I);

$I->amGoingTo('test empty credentials');
$page->login('', '');
$I->expectTo('see validations errors');
$I->see('Password cannot be blank', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test empty email');
$page->login('', 'fghfgh');
$I->expectTo('see validations errors');
$I->see('Email cannot be blank', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test empty password');
$page->login('demo@example.com', '');
$I->expectTo('see validations errors');
$I->see('Password cannot be blank', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test too short password');
$page->login('demo@example.com', 'fgh');
$I->expectTo('see validations errors');
$I->see('Incorrect email or password', '.help-block-error');
$I->dontSee('Welcome');

$I->amGoingTo('test wrong credentials');
$page->login('demo@example.com', 'qrerop2j34');
$I->expectTo('see validations errors');
$I->see('Incorrect email or password', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test blocked credentials');
$page->login('example-blocked@example.com', '123123');
$I->expectTo('see validations errors');
$I->see('Your account has been suspended', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test deleted credentials');
$page->login('example-deleted@example.com', '123123');
$I->expectTo('see validations errors');
$I->see('Your account has been deleted', '.help-block-error');
$I->dontSee('logout');

$I->amGoingTo('test correct credentials');
$page->login('example-2@example.com', '123123');
$I->expectTo('see index page');
$I->see('logout');
$I->dontSee('login');
