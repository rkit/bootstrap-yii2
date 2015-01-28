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
     * @param string $full_name
     * @param string $email
     * @param string $password
     */
    public function signup($full_name, $email, $password)
    {
        $this->actor->fillField('input[name="SignupForm[full_name]"]', $full_name);
        $this->actor->fillField('input[name="SignupForm[email]"]', $email);
        $this->actor->fillField('input[name="SignupForm[password]"]', $password);
        $this->actor->click('signup-button');
    }
}
