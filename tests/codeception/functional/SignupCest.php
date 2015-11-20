<?php

namespace tests\codeception\functional;

use tests\codeception\_pages\SignupPage;
use app\models\User;

class SignupCest
{
    /**
     *
     * @param \codeception\FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testSignup($I, $scenario)
    {
        $page = SignupPage::openBy($I);

        $I->wantTo('test signup');

        $I->amGoingTo('test empty credentials');
        $page->signup('', '', '');
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->see('Password cannot be blank', '.help-block-error');

        $I->amGoingTo('test empty password');
        $page->signup('demo', 'demo@example.com', '');
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');

        $I->amGoingTo('test too short password');
        $page->signup('demo', 'demo@example.com', 'fgh');
        $I->expectTo('see validations errors');
        $I->see('Password should contain at least', '.help-block-error');

        $I->amGoingTo('test empty email');
        $page->signup('demo', '', 'fghfgh');
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');

        $I->amGoingTo('test empty name');
        $page->signup('', 'demo@example.com', 'fghfgh');
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');

        $I->amGoingTo('test exist');
        $page->signup('demo', 'example-2@example.com', 'fghfgh');
        $I->expectTo('see validations errors');
        $I->see('This email address has already been taken', '.help-block-error');

        $I->amGoingTo('test correct credentials');
        $page->signup('demo', 'demo@example.com', 'fghfgh');
        $I->expectTo('see index page');
        $I->see('logout');
        $I->dontSee('signup');
        $I->see('Activate Your Account');
    }
}
