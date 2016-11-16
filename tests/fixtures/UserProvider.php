<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * UserProvider fixture
 */
class UserProvider extends ActiveFixture
{
    public $modelClass = 'app\models\UserProvider';
    public $depends = ['app\tests\fixtures\User'];
    public $dataFile = '@tests/_data/models/user_provider.php';
}
