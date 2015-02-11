<?php

use tests\codeception\_pages\PasswordResetRequestPage;

$I = new FunctionalTester($scenario);
$I->wantTo('password reset request');

$page = PasswordResetRequestPage::openBy($I);

$I->amGoingTo('try to with empty credentials');
$page->submit('');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to with wrong credentials');
$page->submit('non-exist@example.com');
$I->expectTo('see validations errors');
$I->see('.help-block-error');

$I->amGoingTo('try to with correct credentials');
$page->submit('admin@example.com');
$I->expectTo('see redirect');
$I->seeInCurrentUrl('index');