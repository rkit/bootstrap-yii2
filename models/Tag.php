<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $title
 * @property integer $count
 */
class Tag extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'required'],
            ['title', 'unique'],
            ['title', 'string', 'max' => 255],

            ['count', 'integer'],
            ['count', 'default', 'value' => 0]
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
            'count' => Yii::t('app', 'Amount'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->userId = user()->id;
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Finds by title.
     *
     * @param string $title
     * @return static
     */
    public static function findByTitle($title)
    {
        return static::findOne(['title' => $title]);
    }
    
    /**
     * Prepare (and save new tags) tags.
     *
     * @param array $tags
     * @return array
     */
    public static function prepare($tags)
    {
        $data = [];
        foreach ($tags as $title) {
            $tag = static::findByTitle($title);
            
            if (!$tag) {
                $tag = new Tag();
                $tag->title = $title;
                
                if (!$tag->save()) {
                    continue;
                }
            }
            
            $data[] = $tag;
        }
        
        return $data;
    }
}
