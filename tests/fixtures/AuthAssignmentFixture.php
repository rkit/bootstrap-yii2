<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class AuthAssignmentFixture extends ActiveFixture
{
    public $tableName = 'auth_assignment';
    public $dataFile = '@tests/_data/tables/auth_assignment.php';
}
