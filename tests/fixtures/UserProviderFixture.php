<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UserProviderFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entity\UserProvider';
    public $depends = ['app\tests\fixtures\UserFixture'];
    public $dataFile = '@tests/_data/tables/user_provider.php';
}
