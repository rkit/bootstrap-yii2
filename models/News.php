<?php

namespace app\models;

use app\components\BaseActive;
use app\helpers\Util;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $typeId
 * @property string $title
 * @property string $text
 * @property string $preview
 * @property string $dateCreate
 * @property string $dateUpdate
 * @property string $datePub
 * @property string $reference
 * @property integer $status
 */
class News extends BaseActive
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE  = 1;
    
    public $tagsList;
    public $gallery;
    
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
            [['title'], 'trim'],
            [['title', 'typeId', 'text', 'datePub'], 'required'],
            [['title', 'typeId', 'text', 'datePub', 'preview', 'gallery', 'reference', 'status', 'tagsList'], 'safe'],
        
            ['typeId', 'integer'],
            ['typeId', 'exist', 'targetClass' => NewsType::className(), 'targetAttribute' => ['typeId' => 'id']],
            
            ['title', 'string', 'max' => 255],

            ['text', 'string'],
            
            ['preview', 'string', 'max' => 255],

            ['datePub', 'date', 'format' => 'php:Y-m-d H:i:s'],
            
            ['reference', 'url'],
            ['reference', 'string', 'max' => 255],
            
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
            'typeId' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'preview' => Yii::t('app', 'Preview'),
            'gallery' => Yii::t('app', 'Gallery'),
            'dateCreate' => Yii::t('app', 'Date create'),
            'dateUpdate' => Yii::t('app', 'Date update'),
            'datePub' => Yii::t('app', 'Date publication'),
            'reference' => Yii::t('app', 'Reference'),
            'tagsList' => Yii::t('app', 'Tags'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => 'dateUpdate',
                'value' => new \yii\db\Expression('NOW()'),
            ],

            [
                'class' => 'app\behaviors\TagBehavior',
                'attribute' => 'tagsList',
                'tableRelation' => 'newsToTag',
                'tableRelationField' => 'newsId'
            ],
            
            [
                'class' => 'app\behaviors\FileBehavior',
                'attributes' => [
                    'text' => [
                        'ownerType' => File::OWNER_TYPE_NEWS_TEXT,
                        'rules' => [
                            'mimeTypes'  => ['image/png', 'image/jpg', 'image/jpeg'],
                            'extensions' => ['jpg', 'jpeg', 'png'],
                            'maxSize'    => 1024 * 1024 * 2, // 2 MB
                        ]
                    ],
                    'preview' => [
                        'ownerType' => File::OWNER_TYPE_NEWS_PREVIEW,
                        'savePath' => true, // save 'path' in current model
                        'rules' => [
                            'imageSize'  => ['minWidth' => 300, 'minHeight' => 300],
                            'mimeTypes'  => ['image/png', 'image/jpg', 'image/jpeg'],
                            'extensions' => ['jpg', 'jpeg', 'png'],
                            'maxSize'    => 1024 * 1024 * 2, // 2 MB
                        ]
                    ],
                    'gallery' => [
                        'ownerType' => File::OWNER_TYPE_NEWS_GALLERY,
                        'rules' => [
                            'imageSize'  => ['minWidth' => 300, 'minHeight' => 300],
                            'mimeTypes'  => ['image/png', 'image/jpg', 'image/jpeg'],
                            'extensions' => ['jpg', 'jpeg', 'png'],
                            'maxSize'    => 1024 * 1024 * 2, // 2 MB
                        ]
                    ]
                ]
            ]
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
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->datePub = Util::convertTz($this->datePub, Yii::$app->params['mainTimeZone'], 'UTC');
            return true;
        }
        
        return false;
    }

    /**
     * Get all statuses.
     *
     * @return array
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
        $statuses = $this->getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(NewsType::className(), array('id' => 'typeId'));
    }
}
