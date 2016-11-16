<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * UserProfile fixture
 */
class UserProfile extends ActiveFixture
{
    public $modelClass = 'app\models\UserProfile';
    public $depends = ['app\tests\fixtures\User'];
    public $dataFile = '@tests/_data/models/user_profile.php';
}
