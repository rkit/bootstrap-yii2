<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class PasswordResetRequestPage extends BasePage
{
    public $route = '/index/request-password-reset';

    /**
     * @param string $email
     */
    public function submit($email)
    {
        $this->actor->fillField('input[name="PasswordResetRequestForm[email]"]', $email);
        $this->actor->click('button');
    }
}
