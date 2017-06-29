<?php

namespace app\modules\admin\models\forms;

use Yii;
use app\models\News;

class NewsForm extends \yii\base\Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $text;
    /**
     * @var string
     */
    public $preview ;
    /**
     * @var string
     */
    public $date_create;
    /**
     * @var string
     */
    public $date_update;
    /**
     * @var string
     */
    public $date_pub;
    /**
     * @var int
     */
    public $status;
    /**
     * @var array
     */
    public $gallery;
    /**
     * @var array
     */
    public $galleryTitles;
    /**
     * @var \app\models\News
     */
    private $model;

   /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [
                ['title', 'text', 'date_pub'],
                'required'
            ],
            [
                [
                    'title', 'text', 'date_pub', 'preview',
                    'gallery', 'galleryTitles', 'status'
                ], 'safe'
            ],

            ['title', 'string', 'max' => 255],
            ['text', 'string'],

            [
                'date_pub',
                'date',
                'format' => 'php:Y-m-d H:i:s',
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
            'date_pub' => Yii::t('app', 'Date publication'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Set model
     *
     * @param News $model
     */
    public function setModel(News $model): void
    {
        $this->model = $model;

        $this->id = $model->id;
        $this->title = $model->title;
        $this->text = $model->text;
        $this->preview = $model->preview;
        $this->gallery = $model->gallery;
        $this->galleryTitles = $model->galleryTitles;
        $this->date_create = $model->date_create;
        $this->date_update = $model->date_update;
        $this->date_pub = $model->date_pub;
        $this->status = $model->status;
    }

    /**
     * Get model
     *
     * @return News
     */
    public function model(): News
    {
        if ($this->model === null) {
            $this->model = new News();
        }

        return $this->model;
    }

    public function save()
    {
        $model = $this->model();

        $model->title = $this->title;
        $model->text = $this->text;
        $model->preview = $this->preview;
        $model->gallery = $this->gallery;
        $model->galleryTitles = $this->galleryTitles;
        $model->date_pub = $this->date_pub;
        $model->status = $this->status;

        if (!$model->save()) {
            throw new \yii\base\Exception(Yii::t('app.msg', 'An error occurred while saving'));
        }

        $this->id = $model->id;
        return $model;
    }
}
