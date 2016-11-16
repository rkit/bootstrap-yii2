<?php

namespace app\tests\functional\admin;

use Yii;
use yii\helpers\Url;
use app\tests\fixtures\User as UserFixture;

class SettingsCest
{
    protected $pageTitle = 'Settings';
    protected $formName = 'SettingsForm';
    protected $formId = '#settings-form';
    protected $url = '/admin/settings';
    protected $lastId;

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => UserFixture::class,
        ]);
        $I->amLoggedInAs($I->grabFixture('user', 'user-1'));
        $I->amOnRoute($this->url);
    }

    public function openPage($I)
    {
        $I->see($this->pageTitle);
    }

    public function testSave($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[emailName]' => 'Test',
            $this->formName . '[emailMain]' => 'test@test.com',
            $this->formName . '[emailPrefix]' => 'Test',
        ]);
        $I->seeResponseCodeIs(200);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        expect(Yii::$app->settings->emailName)->equals('Test');
        expect(Yii::$app->settings->emailMain)->equals('test@test.com');
        expect(Yii::$app->settings->emailPrefix)->equals('Test');
    }

    public function testSaveViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url), [
            $this->formName . '[emailName]' => 'Test2',
            $this->formName . '[emailMain]' => 'test2@test.com',
            $this->formName . '[emailPrefix]' => 'Test2',
        ]);
        $I->seeResponseCodeIs(302);

        $I->amOnRoute($this->url);

        expect(Yii::$app->settings->emailName)->equals('Test2');
        expect(Yii::$app->settings->emailMain)->equals('test2@test.com');
        expect(Yii::$app->settings->emailPrefix)->equals('Test2');
    }

    public function testClear($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[emailName]' => '',
            $this->formName . '[emailMain]' => '',
            $this->formName . '[emailPrefix]' => '',
        ]);
        $I->seeResponseCodeIs(200);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        expect(Yii::$app->settings->emailName)->isEmpty();
        expect(Yii::$app->settings->emailMain)->isEmpty();
        expect(Yii::$app->settings->emailPrefix)->isEmpty();
    }

    public function testWrongEmailMain($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[emailName]' => 'Test',
            $this->formName . '[emailMain]' => 'test_email',
            $this->formName . '[emailPrefix]' => 'Test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Primary email is not a valid email address', '.help-block');
    }

    public function testWrongEmailMainViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url), [
            $this->formName . '[emailName]' => 'Test',
            $this->formName . '[emailMain]' => 'test_email',
            $this->formName . '[emailPrefix]' => 'Test',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Primary email is not a valid email address');
    }
}
