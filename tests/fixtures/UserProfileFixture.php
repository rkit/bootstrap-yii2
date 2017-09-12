<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UserProfileFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entity\UserProfile';
    public $depends = ['app\tests\fixtures\UserFixture'];
    public $dataFile = '@tests/_data/tables/user_profile.php';
}
