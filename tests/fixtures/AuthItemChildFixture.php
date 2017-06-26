<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class AuthItemChildFixture extends ActiveFixture
{
    public $tableName = 'auth_item_child';
    public $dataFile = '@tests/_data/tables/auth_item_child.php';
}
