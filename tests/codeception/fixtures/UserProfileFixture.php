<?php

namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;

/**
 * UserProfile fixture
 */
class UserProfileFixture extends ActiveFixture
{
    public $modelClass = 'app\models\UserProfile';
    public $depends = ['tests\codeception\fixtures\UserFixture'];
}
