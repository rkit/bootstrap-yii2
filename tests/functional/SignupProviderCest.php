<?php

namespace app\tests\functional;

use Yii;
use Codeception\Util\Stub;
use yii\authclient\OAuthToken;
use yii\authclient\clients\VKontakte;
use yii\authclient\clients\Facebook;
use yii\authclient\clients\Twitter;
use app\tests\fixtures\User as UserFixture;
use app\controllers\IndexController;
use app\models\User;
use app\models\UserProvider;

class SignupProviderCest
{
    protected $formName = 'SignupProviderForm';
    protected $formId = '#form-signup';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => UserFixture::className(),
        ]);
    }

    private function getClientClass($type)
    {
        switch ($type) {
            case 'vkontakte':
                $clientClass = VKontakte::className();
                break;
            case 'twitter':
                $clientClass = Twitter::className();
                break;
            case 'facebook':
                $clientClass = Facebook::className();
                break;
        }
        return $clientClass;
    }

    private function getClientAttributes($type)
    {
        return require Yii::getAlias('@tests/_data/social/' . $type . '/attributes.php');
    }

    private function getClientParamsToken($type)
    {
        return require Yii::getAlias('@tests/_data/social/' . $type . '/tokens.php');
    }

    private function getProvider($type)
    {
        return Stub::make(
            $this->getClientClass($type),
            [
                'getId' => $type,
                'getUserAttributes' => function () use ($type) {
                    return $this->getClientAttributes($type);
                },
                'getAccessToken' => function () use ($type) {
                    return Stub::make(
                        OAuthToken::className(),
                        [
                            'getParams' => function () use ($type) {
                                return $this->getClientParamsToken($type);
                            },
                        ]
                    );
                },
            ]
        );
    }

    private function signup($I, $type, $email)
    {
        $controller = new IndexController('test', 'default');
        $controller->successCallback($this->getProvider($type));

        $I->amOnRoute('/index/signup-provider');
        $I->see('To complete the registration enter your email address');
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => $email,
        ]);
    }

    public function testSignUpLogged($I)
    {
        $I->amLoggedInAs(1);
        $I->amOnRoute('/index/signup-provider');
        $I->dontSee($this->formId);
    }

    public function testSignUpWithoutProvider($I)
    {
        $I->amOnRoute('/index/signup-provider');
        $I->dontSee($this->formId);
    }

    public function testEmptyEmail($I)
    {
        $this->signup($I, 'vkontakte', '');
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');
    }

    public function testWrongFormatEmail($I)
    {
        $this->signup($I, 'vkontakte', 'test_email');
        $I->see('Email is not a valid email address', '.help-block-error');
    }

    public function testFailEmail($I)
    {
        Yii::$app->settings->emailMain = null;

        $this->signup($I, 'vkontakte', 'test@test.com');
        $I->see('An error occurred while sending a message to activate account');
    }

    public function testSignupVkontakte($I)
    {
        Yii::$app->settings->emailName = 'admin';
        Yii::$app->settings->emailMain = 'admin@test.com';

        $this->signup($I, 'vkontakte', 'test@test.com');

        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);

        $user = $I->grabRecord('app\models\User', ['email' => 'test@test.com']);
        $I->assertEquals('1980-09-20', $user->profile->birth_day);
        $I->assertEquals('Test Tester', $user->profile->full_name);
        $I->assertNotEmpty($user->profile->photo);
        $I->assertEquals(UserProvider::TYPE_VKONTAKTE, $user->providers[0]->type);
        $I->assertEquals(100, $user->providers[0]->profile_id);
        $I->assertEquals('https://vk.com/id100', $user->providers[0]->profile_url);
        $I->assertEquals('test1', $user->providers[0]->access_token);
    }

    public function testLoginAfterSignupVkontakte($I)
    {
        $this->signup($I, 'vkontakte', 'test@test.com');

        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);

        Yii::$app->user->logout();

        $controller = new IndexController('test', 'default');
        $controller->successCallback($this->getProvider('vkontakte'));

        $I->amOnRoute('/index/signup-provider');
        $I->dontSee('To complete the registration enter your email address');
        $I->see('Logout');
    }

    public function testLoginAfterSignupVkontakteAndBlocked($I)
    {
        $this->signup($I, 'vkontakte', 'test@test.com');

        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);

        $user = User::findByEmail('test@test.com');
        $user->status = User::STATUS_BLOCKED;
        $user->save();

        Yii::$app->user->logout();

        $controller = new IndexController('test', 'default');
        $controller->successCallback($this->getProvider('vkontakte'));

        $I->amOnRoute('/index/signup-provider');
        $I->see('Your account has been suspended');
    }

    public function testSignupTwitter($I)
    {
        $this->signup($I, 'twitter', 'test@test.com');

        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);

        $user = $I->grabRecord('app\models\User', ['email' => 'test@test.com']);
        $I->assertEquals('Test Tester', $user->profile->full_name);
        $I->assertNotEmpty($user->profile->photo);
        $I->assertEquals(UserProvider::TYPE_TWITTER, $user->providers[0]->type);
        $I->assertEquals(200, $user->providers[0]->profile_id);
        $I->assertEquals('https://twitter.com/test', $user->providers[0]->profile_url);
        $I->assertEquals('test1', $user->providers[0]->access_token);
        $I->assertEquals('test2', $user->providers[0]->access_token_secret);
    }

    public function testSignupFacebook($I)
    {
        $controller = new IndexController('test', 'default');
        $controller->successCallback($this->getProvider('facebook'));

        $I->amOnRoute('/index/signup-provider');
        $I->amOnRoute('/');
        $I->dontSee('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);

        $user = $I->grabRecord('app\models\User', ['email' => 'test@test.com']);
        $I->assertEquals('Test Tester', $user->profile->full_name);
        $I->assertEquals(UserProvider::TYPE_FACEBOOK, $user->providers[0]->type);
        $I->assertEquals(300, $user->providers[0]->profile_id);
        $I->assertEquals('https://www.facebook.com/300', $user->providers[0]->profile_url);
        $I->assertEquals('test1', $user->providers[0]->access_token);
        $I->assertEmpty($user->profile->photo);
    }
}
