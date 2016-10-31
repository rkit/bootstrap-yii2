<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * ActiveRecord for table "file"
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $name
 * @property integer $size
 * @property string $extension
 * @property string $mime
 * @property string $date_create
 * @property string $date_update
 * @property integer $ip
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @var string
     */
    public $path;
    
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     * @internal
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     * @internal
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @internal
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->user_id = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id; // @codeCoverageIgnore
                    $this->ip = ip2long(Yii::$app->request->getUserIP()); // @codeCoverageIgnore
                } // @codeCoverageIgnore
                $this->fillMetaInfo();
            }
            return true;
        }
        return false; // @codeCoverageIgnore
    }

    /**
     * Fill meta info
     *
     * @param string $path File path
     * @return void
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private function fillMetaInfo()
    {
        $pathInfo = pathinfo($this->path);
        if ($this->title === null) {
            $this->title = $pathInfo['filename'];
        }
        $this->size = filesize($this->path);
        $this->mime = FileHelper::getMimeType($this->path);
        $this->extension = $this->detectExtension($this->path, $this->mime);
        $this->name = $this->generateName();
    }

    /**
     * Get extension By MimeType
     *
     * @return string
     */
    private function detectExtension($path, $mimeType)
    {
        $extensions = FileHelper::getExtensionsByMimeType($mimeType);
        $pathExtension = pathinfo($path, PATHINFO_EXTENSION);
        $titleExtension = pathinfo($this->title, PATHINFO_EXTENSION);
        if (array_search($pathExtension, $extensions) !== false) {
            return $pathExtension;
        } elseif (array_search($titleExtension, $extensions) !== false) {
            return $titleExtension;
        }
        $extension = explode('/', $mimeType);
        $extension = end($extension);
        if (array_search($extension, $extensions) !== false) {
            return $extension;
        }
        return current($extensions); // @codeCoverageIgnore
    }

    /**
     * Generate a name
     *
     * @return string
     */
    private function generateName()
    {
        $name = date('YmdHis') . substr(md5(microtime() . uniqid()), 0, 10);
        return $name . '.' . $this->extension;
    }
}
