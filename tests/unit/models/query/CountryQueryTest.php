<?php

namespace app\tests\unit\models\query;

use app\tests\fixtures\Country as CountryFixture;
use app\models\Country;

class CountryQueryTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'country' => [
                 'class' => CountryFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/country.php',
             ],
        ]);
    }

    public function testFindLike()
    {
        $models = Country::find()->like('title', 'Country-')->all();
        expect(count($models))->equals(3);
    }
}
