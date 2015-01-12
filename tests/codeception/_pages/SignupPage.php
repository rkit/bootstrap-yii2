<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents signup page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class SignupPage extends BasePage
{
    public $route = '/signup';

    /**
     * @param string $email
     * @param string $password
     */
    public function signup($email, $password)
    {
        $this->actor->fillField('input[name="SignupForm[email]"]', $email);
        $this->actor->fillField('input[name="SignupForm[password]"]', $password);
        $this->actor->click('signup-button');
    }
}
