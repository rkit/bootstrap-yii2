<?php

namespace tests\codeception\functional;

use tests\codeception\_pages\SignupPage;
use app\models\User;

class SignupCest
{
    /**
     * This method is called after each cest class test method, even if test failed.
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll([
            'email' => 'newuser@example.com'
        ]);
    }
    
    /**
     *
     * @param \codeception\FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignup($I, $scenario)
    {
        $I->wantTo('ensure that signup works');
        
        $page = SignupPage::openBy($I);
        
        $I->see('signup-button');
        
        $I->amGoingTo('try to signup with empty credentials');
        $page->signup('', '', '');
        $I->expectTo('see validations errors');
        $I->see('.help-block-error');
        
        $I->amGoingTo('try to signup with empty password');
        $page->signup('Mike', 'newuser@example.com', '');
        $I->expectTo('see validations errors');
        $I->see('.help-block-error');
        
        $I->amGoingTo('try to signup with empty email');
        $page->signup('Mike', '', 'fghfgh');
        $I->expectTo('see validations errors');
        $I->see('.help-block-error');
        
        $I->amGoingTo('try to signup with empty name');
        $page->signup('', 'newuser@example.com', 'fghfgh');
        $I->expectTo('see validations errors');
        $I->see('.help-block-error');
        
        $I->amGoingTo('try to signup with wrong credentials');
        $page->signup('Mike', 'newuser@example.com', 'wrong');
        $I->expectTo('see validations errors');
        $I->see('.help-block-error');
        
        $I->amGoingTo('try to signup with correct credentials');
        $page->signup('Mike', 'newuser@example.com', 'fghfgh');
        $I->expectTo('see user info');
        $I->see('logout');
        $I->dontSee('signup');
    }
}
