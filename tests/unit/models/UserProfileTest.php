<?php

namespace app\tests\unit\models;

use Yii;
use yii\helpers\FileHelper;
use app\tests\fixtures\UserProfile as UserProfileFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\UserProfile;

class UserProfileTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
             'userProfile' => [
                 'class' => UserProfileFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user_profile.php',
             ],
        ]);

        FileHelper::removeDirectory(Yii::getAlias('@tests/_tmp/files'));
        FileHelper::copyDirectory(
            Yii::getAlias('@tests/_data/files'),
            Yii::getAlias('@tests/_tmp/files')
        );
    }

    public function testAddPhoto()
    {
        $model = UserProfile::findOne(1);

        $file = $model->createFile('photo', Yii::getAlias('@tests/_tmp/files/300x300.png'), '300x300', false);
        expect_that($file);

        $model->photo = $file->id;
        expect_that($model->save());

        $thumb = $model->thumbPath('photo', '1000x1000');
        expect($thumb)->contains('1000x1000');
        // because original was replaced
        expect_not(file_exists($thumb));
    }
}
