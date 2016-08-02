<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * UserProfile fixture
 */
class UserProfile extends ActiveFixture
{
    public $modelClass = 'app\models\UserProfile';
    public $depends = ['tests\fixtures\User'];
}
