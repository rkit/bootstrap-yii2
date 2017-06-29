<?php

namespace app\modules\admin\helpers;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Formatting of validation rules
 */
class FileRulesDescription
{
    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param array $rules Validation rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    private function maxSizeDescription(): string
    {
        $rules = ArrayHelper::getValue($this->rules, 'maxSize');

        if ($rules === null) {
            return '';
        }
        $rules = Yii::$app->formatter->asShortSize($rules);
        return Yii::t('app.msg', 'Max. file size') . ': ' . $rules . ' ';
    }

    private function maxFilesDescription(): string
    {
        $rules = ArrayHelper::getValue($this->rules, 'maxFiles');

        if ($rules === null) {
            return '';
        }
        return Yii::t('app.msg', 'Max. file number') . ': ' . $rules . ' ';
    }

    private function extensionDescription(): string
    {
        $rules = ArrayHelper::getValue($this->rules, 'extensions');

        if ($rules === null) {
            return '';
        }
        $rules = strtoupper(implode(', ', $rules));
        return Yii::t('app.msg', 'File types') . ': ' . $rules . ' ';
    }

    private function imageSizeDescription(): string
    {
        $rules = ArrayHelper::getValue($this->rules, 'imageSize');

        if ($rules === null) {
            return '';
        }

        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        $text = [];
        switch ($rules) {
            case $this->isImageWithStrictSize($rules):
                $text[] = Yii::t('app.msg', 'Image size') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                break;
            case $this->isImageWithMinAndMaxSize($rules):
                $text[] = Yii::t('app.msg', 'Min. size of image') . ': ' . $minWidth . 'x' . $minHeight . 'px';
                $text[] = Yii::t('app.msg', 'Max. size of image') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                break;
            case $this->isImageWithMinSize($rules):
                $text[] = Yii::t('app.msg', 'Min. size of image') . ': ' . $minWidth . 'x' . $minHeight . 'px';
                $text[] = $this->prepareImageSizeDescription($rules, ['minWidth', 'minHeight']);
                break;
            case $this->isImageWithMaxSize($rules):
                $text[] = Yii::t('app.msg', 'Max. size of image') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                $text[] = $this->prepareImageSizeDescription($rules, ['maxWidth', 'maxHeight']);
                break;
            default:
                $text[] = $this->prepareImageSizeDescription($rules);
                break;
        }

        return implode('<br>', array_filter($text));
    }

    private function isImageWithStrictSize(array $rules): bool
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return count($rules) == 4 && ($maxWidth == $minWidth && $maxHeight == $minHeight);
    }

    private function isImageWithMinAndMaxSize(array $rules): bool
    {
        return count($rules) == 4;
    }

    private function isImageWithMinSize(array $rules): bool
    {
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return (count($rules) == 2 || count($rules) == 3) && $minWidth && $minHeight;
    }

    private function isImageWithMaxSize(array $rules): bool
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');

        return (count($rules) == 2 || count($rules) == 3) && $maxWidth && $maxHeight;
    }

    private function prepareImageSizeDescription(array $rules, array $exclude = []): string
    {
        foreach ($exclude as $item) {
            unset($rules[$item]);
        }

        $text = [];
        foreach ($rules as $rule => $value) {
            switch ($rule) {
                case 'minWidth':
                    $text[] = Yii::t('app.msg', 'Min. width') . ' ' . $value . 'px';
                    break;
                case 'minHeight':
                    $text[] = Yii::t('app.msg', 'Min. height') . ' ' . $value . 'px';
                    break;
                case 'maxWidth':
                    $text[] = Yii::t('app.msg', 'Max. width') . ' ' . $value . 'px';
                    break;
                case 'maxHeight':
                    $text[] = Yii::t('app.msg', 'Max. height') . ' ' . $value . 'px';
                    break;
            }
        }

        return implode('<br>', array_filter($text));
    }

    /**
     * Get a description of the validation rules in as text
     *
     * @return string
     */
    public function toText(): string
    {
        $text = [];

        $text[] = $this->imageSizeDescription();
        $text[] = $this->extensionDescription();
        $text[] = $this->maxSizeDescription();
        $text[] = $this->maxFilesDescription();

        return implode('<br>', array_filter($text));
    }
}
