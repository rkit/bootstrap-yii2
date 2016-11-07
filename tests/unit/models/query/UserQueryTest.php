<?php

namespace app\tests\unit\models\query;

use app\tests\fixtures\User as UserFixture;
use app\models\User;

class UserQueryTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
             ],
        ]);
    }

    public function testFindActive()
    {
        $models = User::find()->active()->all();
        expect(count($models))->equals(4);
        foreach ($models as $model) {
            expect($model->isActive())->true();
        }
    }

    public function testFindBlocked()
    {
        $models = User::find()->blocked()->all();
        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->isBlocked())->true();
        }
    }

    public function testFindDeleted()
    {
        $models = User::find()->deleted()->all();
        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->isDeleted())->true();
        }
    }

    public function testFindLike()
    {
        $models = User::find()->like('username', 'user-')->all();
        expect(count($models))->equals(5);
    }
}
