<?php

namespace app\tests\unit\models\query;

use Yii;
use app\tests\fixtures\Region as RegionFixture;
use app\models\Region;

class RegionQueryTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'region' => [
                 'class' => RegionFixture::className(),
                 'dataFile' => codecept_data_dir() . 'region.php',
             ],
        ]);
    }

    public function testFindLike()
    {
        $models = Region::find()->like('title', 'Region-')->all();
        expect(count($models))->equals(3);
    }
}
