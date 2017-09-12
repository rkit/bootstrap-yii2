<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entity\User';
    public $dataFile = '@tests/_data/tables/user.php';
}
