<?php

namespace app\behaviors;

use yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\File;

/**
 * Binding files to the owner.
 * The rules are checked in UploadAction.
 *
 * Usage:
 * ~~~
 * 'class' => 'app\components\behaviors\FileBehavior',
 * 'attributes' => [
 *     'preview' => [
 *         'ownerType' => File::OWNER_TYPE_NEWS_PREVIEW,
 *         'savePath' => true, // save 'path' in current model
 *         'rules' => [
 *             'imageSize'  => ['minWidth' => 300, 'minHeight' => 300],
 *             'mimeTypes'  => ['image/png', 'image/jpg', 'image/jpeg'],
 *             'extensions' => ['jpg', 'jpeg', 'png'],
 *             'maxSize'    => 1024 * 1024 * 2, // 2 MB
 *         ]
 *     ],
 * ]
 * ~~~
 */
class FileBehavior extends Behavior
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT  => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeSave($insert)
    {
        foreach ($this->attributes as $attribute => $data) {
            $oldValue = $this->owner->isNewRecord ? null : $this->owner->getOldAttribute($attribute);
            $isAttributeChanged = $oldValue === null ? true : $this->owner->isAttributeChanged($attribute);

            $this->attributes[$attribute]['isAttributeChanged'] = $isAttributeChanged;
            $this->attributes[$attribute]['oldValue'] = $oldValue;
        }
    }

    public function afterSave()
    {
        foreach ($this->attributes as $attribute => $data) {
            $fileId = $this->owner->{$attribute};

            if ($data['isAttributeChanged'] === false || $fileId === null) {
                continue;
            }

            $file = File::bind($this->owner->primaryKey, $data['ownerType'], $fileId);
            // if savePath, then path saved in current model
            if (isset($data['savePath']) && $data['savePath'] === true) {
                if (is_object($file)) {
                    $path = $file->path();
                } elseif ($file === false && $data['oldValue'] !== null) {
                    $path = $data['oldValue'];
                } else {
                    $path = '';
                }
                $this->owner->updateAttributes([$attribute => $path]);
            }
        }
    }

    public function beforeDelete()
    {
        foreach ($this->attributes as $attribute => $data) {
            File::deleteByOwner($this->owner->primaryKey, $data['ownerType']);
        }
    }

    /**
     * Get ownerType.
     *
     * @param string $attribute
     * @return int
     */
    public function getFileOwnerType($attribute)
    {
        return $this->attributes[$attribute]['ownerType'];
    }

    /**
     * Get files.
     *
     * @param string $attribute
     * @return array
     */
    public function getFiles($attribute)
    {
        return File::getByOwner($this->owner->primaryKey, $this->getFileOwnerType($attribute));
    }

    /**
     * Get rules.
     *
     * @param string $attribute
     * @return array
     */
    public function getFileRules($attribute)
    {
        return $this->attributes[$attribute]['rules'];
    }

    /**
     * Get rules description.
     *
     * @param string $attribute
     * @return string
     */
    public function getFileRulesDescription($attribute)
    {
        $text = '';

        $rules = $this->attributes[$attribute]['rules'];

        if (isset($rules['imageSize'])) {
            $text .= $this->prepareImageSizeDescription($rules['imageSize']);
            $text = !empty($text) ? $text . '<br>' : $text;
        }

        if (isset($rules['extensions'])) {
            $text .= $this->prepareExtensionDescription($rules['extensions']);
            $text = isset($rules['maxSize']) ? $text . '<br>' : $text;
        }

        if (isset($rules['maxSize'])) {
            $text .= $this->prepareMaxSizeDescription($rules['maxSize']);
        }

        return $text;
    }

    private function prepareMaxSizeDescription($rules)
    {
        $maxSize = Yii::$app->formatter->asShortSize($rules);
        return Yii::t('app', 'Max. file size') . ': ' . $maxSize . ' ';
    }

    private function prepareExtensionDescription($rules)
    {
        $extensions = strtoupper(implode(', ', $rules));
        return Yii::t('app', 'File types') . ': ' . $extensions . ' ';
    }

    private function prepareImageSizeDescription($rules)
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        $text = '';
        if (count($rules) == 4 && ($maxWidth == $minWidth && $maxHeight == $minHeight)) {
            $text .= Yii::t('app', 'Image size') . ': ' . $maxWidth . 'x' . $maxHeight . 'px ';
        } elseif (count($rules) == 2 && $minWidth && $minHeight) {
            $text .= Yii::t('app', 'Min. size of image') . ': ' . $minWidth . 'x' . $minHeight . 'px ';
        } elseif (count($rules) == 2 && $maxWidth && $maxHeight) {
            $text .= Yii::t('app', 'Max. size if image') . ': ' . $maxWidth . 'x' . $maxHeight . 'px ';
        } else {
            $text .= $this->prepareImageSizeFullDescription($rules);
        }

        return $text;
    }

    private function prepareImageSizeFullDescription($rules)
    {
        $text = '';
        foreach ($rules as $rule => $value) {
            switch ($rule) {
                case 'minWidth':
                    $text .= Yii::t('app', 'Min. width') . ' ' . $value . 'px ';
                    break;
                case 'minHeight':
                    $text .= Yii::t('app', 'Min. height') . ' ' . $value . 'px ';
                    break;
                case 'maxWidth':
                    $text .= Yii::t('app', 'Max. width') . ' ' . $value . 'px ';
                    break;
                case 'maxHeight':
                    $text .= Yii::t('app', 'Max. height') . ' ' . $value . 'px ';
                    break;
            }
        }

        return $text;
    }
}
