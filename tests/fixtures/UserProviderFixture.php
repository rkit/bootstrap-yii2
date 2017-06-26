<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UserProviderFixture extends ActiveFixture
{
    public $modelClass = 'app\models\UserProvider';
    public $depends = ['app\tests\fixtures\UserFixture'];
    public $dataFile = '@tests/_data/tables/user_provider.php';
}
