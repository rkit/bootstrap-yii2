<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class User extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $dataFile = '@tests/_data/models/user.php';
}
