<?php

namespace app\tests\unit\components;

use Yii;
use app\tests\fixtures\Tag as TagFixture;
use app\tests\fixtures\User as UserFixture;
use app\components\BaseController;
use app\models\Tag;
use app\models\User;

class BaseControllerTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
             'tag' => [
                 'class' => TagFixture::className(),
                 'dataFile' => codecept_data_dir() . 'tag.php',
             ],
        ]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Page not found
     */
    public function testLoadModelWithNonExistModel()
    {
        $controller = new BaseController('test', 'default');
        $controller->loadModel(new Tag(), 100);
    }

    public function testLoadModel()
    {
        $controller = new BaseController('test', 'default');
        $model = $controller->loadModel(new Tag(), 1);
        expect($model)->isInstanceOf('app\models\Tag');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Page not found
     */
    public function testLoadModelAndCheckOwnerWithGuest()
    {
        $controller = new BaseController('test', 'default');
        $model = $controller->loadModel(new Tag(), 1, 'checkAccess');
        expect($model)->isInstanceOf('app\models\Tag');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Page not found
     */
    public function testLoadModelAndCheckOwnerWithWrongUser()
    {
        Yii::$app->user->setIdentity(User::findOne(2));

        $controller = new BaseController('test', 'default');
        $model = $controller->loadModel(new Tag(), 1, 'checkAccess');
        expect($model)->isInstanceOf('app\models\Tag');
    }

    public function testLoadModelAndCheckOwner()
    {
        Yii::$app->user->setIdentity(User::findOne(1));

        $controller = new BaseController('test', 'default');
        $model = $controller->loadModel(new Tag(), 1, 'checkAccess');
        expect($model)->isInstanceOf('app\models\Tag');
    }
}
