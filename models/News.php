<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\File;
use app\models\query\NewsQuery;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $preview
 * @property string $date_create
 * @property string $date_update
 * @property string $date_pub
 * @property integer $status
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE  = 1;

    /**
     * @var array
     */
    public $gallery;
    /**
     * @var array
     */
    public $galleryTitles;

    public function __construct($config = [])
    {
        $this->attachBehavior('fileManager', require __DIR__ . '/behaviors/news/filemanager.php');
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['title', 'text', 'date_pub'], 'required'
            ],
            [
                [
                    'title', 'text', 'date_pub', 'preview',
                    'gallery', 'galleryTitles', 'status'
                ], 'safe'
            ],

            ['title', 'string', 'max' => 255],
            ['text', 'string'],

            ['date_pub', 'date', 'format' => 'php:Y-m-d H:i:s',
                'timestampAttribute' => 'date_pub',
                'timestampAttributeFormat' => 'php:Y-m-d H:i:s'
            ],

            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(News::getStatuses())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'preview' => Yii::t('app', 'Preview'),
            'gallery' => Yii::t('app', 'Gallery'),
            'date_create' => Yii::t('app', 'Date create'),
            'date_update' => Yii::t('app', 'Date update'),
            'date_pub' => Yii::t('app', 'Date publication'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    public function transactions()
    {
        return [
            'create' => self::OP_ALL,
            'update' => self::OP_ALL,
            'delete' => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     * @return NewsQuery
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }

    /**
     * Get all statuses
     *
     * @return string[]
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_BLOCKED => Yii::t('app', 'Not published'),
            self::STATUS_ACTIVE  => Yii::t('app', 'Published'),
        ];
    }

    /**
     * Get statuse name
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    /**
     * Is it blocked?
     *
     * @param bool
     */
    public function isBlocked()
    {
        return $this->status == self::STATUS_BLOCKED;
    }

    /**
     * Is it active?
     *
     * @param bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function getFiles($callable = null)
    {
        return $this
            ->hasMany(File::class, ['id' => 'file_id'])
            ->viaTable('news_files', ['news_id' => 'id'], $callable);
    }
}
