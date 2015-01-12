<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class ResetPasswordPage extends BasePage
{
    public $route = '/reset-password';

    /**
     * @param string $password
     */
    public function submit($password)
    {
        $this->actor->fillField('input[name="ResetPasswordForm[password]"]', $password);
        $this->actor->click('button');
    }
}
