<?php

namespace app\tests\unit\components;

use Yii;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class NotifyTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
    }

    public function testSendMessage()
    {
        Yii::$app->settings->set('emailMain', 'editor@mail.com');
        Yii::$app->settings->set('emailName', 'Editor');
        Yii::$app->settings->set('emailPrefix', 'Prefix');

        $user = User::findOne(1);

        Yii::$app->notify->sendMessage(
            $user->email,
            'Subject',
            'emailConfirmToken',
            ['user' => User::findOne(1)]
        );

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($user->email);
        expect($message->getFrom())->hasKey('editor@mail.com');
        expect($message->getFrom())->contains('Editor');
        expect($message->getSubject())->equals('Prefix: Subject');
    }

    public function testSendMessageWithoutSubjectPrefix()
    {
        Yii::$app->settings->set('emailMain', 'editor@mail.com');
        Yii::$app->settings->set('emailName', 'Editor');
        Yii::$app->settings->set('emailPrefix', '');

        $user = User::findOne(1);

        Yii::$app->notify->sendMessage(
            $user->email,
            'Subject',
            'emailConfirmToken',
            ['user' => User::findOne(1)]
        );

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($user->email);
        expect($message->getFrom())->hasKey('editor@mail.com');
        expect($message->getFrom())->contains('Editor');
        expect($message->getSubject())->equals('Subject');
    }
}
