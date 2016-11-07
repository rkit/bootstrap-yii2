<?php

namespace app\tests\unit\models\query;

use app\tests\fixtures\Tag as TagFixture;
use app\models\Tag;

class TagQueryTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'tag' => [
                 'class' => TagFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/tag.php',
             ],
        ]);
    }

    public function testFindLike()
    {
        $models = Tag::find()->like('title', 'Tag-')->all();
        expect(count($models))->equals(3);
    }
}
