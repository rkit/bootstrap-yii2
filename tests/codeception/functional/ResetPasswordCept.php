<?php

use tests\codeception\_pages\ResetPasswordPage;
use app\models\User;

$I = new FunctionalTester($scenario);

$user = User::findByEmail('example-2@example.com');

$I->wantTo('test reset password');
$page = ResetPasswordPage::openBy($I, ['token' => $user->password_reset_token]);

$I->amGoingTo('test empty password');
$page->submit('');
$I->expectTo('see validations errors');
$I->see('Password cannot be blank', '.help-block-error');

$I->amGoingTo('test too short password');
$page->submit('two');
$I->expectTo('see validations errors');
$I->see('Password should contain at least', '.help-block-error');

$I->amGoingTo('test correct password');
$page->submit('fghfgh');
$I->expectTo('see a success message');
$I->see('New password was saved');
