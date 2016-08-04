<?php

namespace app\tests\functional\admin;

use Yii;
use yii\helpers\Url as Url;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class SettingsCest
{
    protected $pageTitle = 'Settings';
    protected $modelClass = 'app\models\Tag';
    protected $formName = 'Settings';
    protected $formId = '#settings-form';
    protected $url = '/admin/settings';
    protected $lastId;

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
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
        $I->expectTo('see success');
        $I->see('Saved successfully');
        $I->seeResponseCodeIs(200);

        expect(Yii::$app->settings->emailName)->equals('Test');
        expect(Yii::$app->settings->emailMain)->equals('test@test.com');
        expect(Yii::$app->settings->emailPrefix)->equals('Test');
    }

    public function testClear($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[emailName]' => '',
            $this->formName . '[emailMain]' => '',
            $this->formName . '[emailPrefix]' => '',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');
        $I->seeResponseCodeIs(200);

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
}
