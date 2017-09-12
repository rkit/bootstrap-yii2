<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class AuthItemFixture extends ActiveFixture
{
    public $modelClass = 'app\models\entity\AuthItem';
    public $dataFile = '@tests/_data/tables/auth_item.php';
    public $depends = [
        'app\tests\fixtures\AuthItemChildFixture',
        'app\tests\fixtures\AuthAssignmentFixture',
    ];
}
