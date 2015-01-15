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
     * @param string $fullName
     * @param string $email
     * @param string $password
     */
    public function signup($fullName, $email, $password)
    {
        $this->actor->fillField('input[name="SignupForm[fullName]"]', $fullName);
        $this->actor->fillField('input[name="SignupForm[email]"]', $email);
        $this->actor->fillField('input[name="SignupForm[password]"]', $password);
        $this->actor->click('signup-button');
    }
}
