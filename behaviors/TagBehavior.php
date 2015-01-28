<?php

namespace app\behaviors;

use yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\Tag;

/** 
 * Tag.
 * 
 * Usage:
 * ~~~
 * [
 *     'class' => 'app\components\behaviors\TagBehavior',
 *     'attribute' => 'tagsList',
 *     'tableRelation' => 'news_tag_assn',
 *     'tableRelationField' => 'news_id'
 * ]
 * ~~~
 */ 
class TagBehavior extends Behavior
{
    /**
     * @var string $attribute
     */
    public $attribute = 'tagsList';
    /**
     * @var string $tableRelation
     */
    public $tableRelation = null;
    /** 
     * @var string $tableRelationField
     */
    public $tableRelationField = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->tableRelation == null || $this->tableRelationField == null) {
            throw new InvalidParamException('Invalid $tableRelation or $tableRelationField parameter');
        }
    }
       
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT  => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function afterSave()
    {    
        if ($this->owner->{$this->attribute} !== null) {
            $this->saveTags();
        }
    }
    
    public function beforeDelete()
    {
        $this->updateTagsCount(false);
        
        Yii::$app->db
            ->createCommand()
            ->delete(
                $this->tableRelation,
                [$this->tableRelationField => $this->owner->primaryKey]
            )->execute();
    }
    
    /**
     * Save tags.
     */
    public function saveTags()
    {
        $this->beforeDelete();
        
        $tags = Tag::prepare(explode(',', $this->owner->{$this->attribute}));
        
        foreach ($tags as $tag) {
            $this->owner->link('tags', $tag);
        }       
        
        $this->updateTagsCount(true);
    }
    
    /**
     * Get tags.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->owner
            ->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable($this->tableRelation, [$this->tableRelationField => 'id']);
    }
    
    /**
     * Update count tags.
     *
     * @param bool $increment
     * @return int
     */
    public function updateTagsCount($increment = true)
    {
        $condition = sprintf(
            'id IN (SELECT tag_id FROM %s WHERE %s = :%s) %s', 
            $this->tableRelation, 
            $this->tableRelationField, 
            $this->tableRelationField,
            !$increment ? ' AND count > 0' : ''
        );

        return Tag::updateAllCounters(
            ['count' => $increment ? +1 : -1], 
            $condition, 
            [':' . $this->tableRelationField => $this->owner->primaryKey]
        );
    }

    /**
     * Get tags as string.
     *
     * @return string
     */
    public function tagsToString()
    {
        if (!$this->owner->tags) {
            return '';
        }
        
        return implode(',', ArrayHelper::getColumn($this->owner->tags, 'title', false));
    } 
}
