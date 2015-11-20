<?php

use tests\codeception\_pages\PasswordResetRequestPage;

$I = new FunctionalTester($scenario);

$I->wantTo('test password reset request');
$page = PasswordResetRequestPage::openBy($I);

$I->amGoingTo('test empty email');
$page->submit('');
$I->expectTo('see validations errors');
$I->see('Email cannot be blank', '.help-block-error');

$I->amGoingTo('test wrong email');
$page->submit('qwe');
$I->expectTo('see validations errors');
$I->see('Email is not a valid email address', '.help-block-error');

$I->amGoingTo('test non exist email');
$page->submit('non-exist@example.com');
$I->expectTo('see validations errors');
$I->see('There is no user with such email', '.help-block-error');

$I->amGoingTo('test blocked credentials');
$page->submit('example-blocked@example.com');
$I->expectTo('see validations errors');
$I->see('There is no user with such email', '.help-block-error');

$I->amGoingTo('test deleted credentials');
$page->submit('example-deleted@example.com');
$I->expectTo('see validations errors');
$I->see('There is no user with such email', '.help-block-error');

$I->amGoingTo('test correct email');
$page->submit('example-2@example.com');
$I->expectTo('see a success message');
$I->see('We\'ve sent you an email with instructions to reset your password');
