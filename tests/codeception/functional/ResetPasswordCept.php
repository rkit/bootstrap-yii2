<?php

use tests\codeception\_pages\ResetPasswordPage;
use app\models\User;

$I = new FunctionalTester($scenario);
$I->wantTo('reset password');

$page = ResetPasswordPage::openBy($I, ['token' => User::findByEmail('admin@example.com')->password_reset_token]);

$I->amGoingTo('try to with empty credentials');
$page->submit('');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to with wrong credentials');
$page->submit('two');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to with correct credentials');
$page->submit('fghfgh');
$I->expectTo('see redirect');
$I->seeInCurrentUrl('index');