<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use app\models\forms\SignupForm;
use app\models\User;
use tests\codeception\fixtures\UserFixture;
use Codeception\Specify;

class SignupFormTest extends DbTestCase
{
    use Specify;

    protected function setUp()
    {
        parent::setUp();

        Yii::$app->mailer->fileTransportCallback = function ($mailer, $message) {
            return 'testing_message.eml';
        };
    }

    protected function tearDown()
    {
        User::deleteAll();
        @unlink($this->getMessageFile());
        parent::tearDown();
    }

    public function testSignupNotCorrect()
    {
        $form = new SignupForm([
            'full_name' => 'Demo',
            'email' => $this->user['2-active']['email'],
            'password' => 'two',
        ]);

        $this->assertFalse($form->signup());
        $this->assertFalse($form->sendEmail());
        $this->assertNotEmpty($form->errors['password'][0]);
    }

    public function testSignupEmptyFullName()
    {
        $form = new SignupForm([
            'full_name' => '',
            'email' => $this->user['2-active']['email'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->signup());
        $this->assertFalse($form->sendEmail());
        $this->assertNotEmpty($form->errors['email'][0]);
    }

    public function testSignupEmptyPassword()
    {
        $form = new SignupForm([
            'full_name' => 'Demo',
            'email' => $this->user['2-active']['email'],
            'password' => '',
        ]);

        $this->assertFalse($form->signup());
        $this->assertFalse($form->sendEmail());
        $this->assertNotEmpty($form->errors['password'][0]);
    }

    public function testSignupEmptyEmail()
    {
        $form = new SignupForm([
            'full_name' => 'Demo',
            'email' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->signup());
        $this->assertFalse($form->sendEmail());
        $this->assertNotEmpty($form->errors['email'][0]);
    }

    public function testSignupExist()
    {
        $form = new SignupForm([
            'full_name' => 'Demo',
            'email' => $this->user['2-active']['email'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->signup());
        $this->assertFalse($form->sendEmail());
        $this->assertNotEmpty($form->errors['email'][0]);
    }

    public function testSignupCorrect()
    {
        $form = new SignupForm([
            'full_name' => 'Demo',
            'email' => 'demo@example.com',
            'password' => 'gw35hhbp',
        ]);

        $user = $form->signup();
        $this->assertInstanceOf('app\models\User', $user);
        $this->assertFalse($user->isConfirmed());
        $this->assertEquals('demo@example.com', $user->email);
        $this->assertTrue($user->validatePassword('gw35hhbp'));
        $this->assertTrue($form->sendEmail());
        $this->assertTrue(file_exists($this->getMessageFile()));

        $user = User::findByEmail('demo@example.com');
        $this->assertContains('Demo', $user->profile->full_name);
    }

    private function getMessageFile()
    {
        return Yii::getAlias(Yii::$app->mailer->fileTransportPath) . '/testing_message.eml';
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/user.php',
            ],
        ];
    }
}
