<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class NewsFixture extends ActiveFixture
{
    public $modelClass = 'app\models\News';
    public $dataFile = '@tests/_data/tables/news.php';
}
