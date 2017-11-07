<?php

namespace app\modules\admin\helpers;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Formatting of validation rules
 */
class FileRulesDescription
{
    private static function maxSizeDescription(array $rules): string
    {
        $rules = ArrayHelper::getValue($rules, 'maxSize');

        if ($rules === null) {
            return '';
        }

        $rules = Yii::$app->formatter->asShortSize($rules);
        return Yii::t('app.msg', 'Max. file size') . ': ' . $rules . ' ';
    }

    private static function maxFilesDescription(array $rules): string
    {
        $rules = ArrayHelper::getValue($rules, 'maxFiles');

        if ($rules === null) {
            return '';
        }

        return Yii::t('app.msg', 'Max. file number') . ': ' . $rules . ' ';
    }

    private static function extensionDescription(array $rules): string
    {
        $rules = ArrayHelper::getValue($rules, 'extensions');

        if ($rules === null) {
            return '';
        }

        $rules = strtoupper(implode(', ', $rules));
        return Yii::t('app.msg', 'File types') . ': ' . $rules . ' ';
    }

    private static function imageSizeDescription(array $rules): array
    {
        $rules = ArrayHelper::getValue($rules, 'imageSize');

        if ($rules === null || !count($rules)) {
            return [];
        }

        $text = [];
        switch ($rules) {
            case self::isImageStrictSize($rules):
                $text = self::imageStrictSizeDescription($rules);
                break;
            case self::isImageMinAndMaxSize($rules):
                $text = self::imageMinAndMaxSizeDescription($rules);
                break;
            case self::isImageMinSize($rules):
                $text = array_merge(
                    self::imageMinSizeDescription($rules), 
                    self::imageRulesDescription($rules, ['minWidth', 'minHeight'])
                );
                break;
            case self::isImageMaxSize($rules):
                $text = array_merge(
                    self::imageMaxSizeDescription($rules), 
                    self::imageRulesDescription($rules, ['maxWidth', 'maxHeight'])
                );
                break;
            default:
                $text = self::imageRulesDescription($rules);
                break;
        }

        return $text;
    }

    private static function isImageStrictSize(array $rules): bool
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return count($rules) == 4 && ($maxWidth == $minWidth && $maxHeight == $minHeight);
    }

    private static function imageStrictSizeDescription(array $rules): array
    {
        return [
            Yii::t('app.msg', 'Image size') . ': ' . 
            ArrayHelper::getValue($rules, 'maxWidth') . 'x' . ArrayHelper::getValue($rules, 'maxHeight') . 'px'
        ];
    }

    private static function isImageMinAndMaxSize(array $rules): bool
    {
        return count($rules) == 4;
    }

    private static function imageMinAndMaxSizeDescription(array $rules): array
    {
        return [
            Yii::t('app.msg', 'Min. size of image') . ': ' . 
            ArrayHelper::getValue($rules, 'minWidth') . 'x' . ArrayHelper::getValue($rules, 'minHeight') . 'px',
            Yii::t('app.msg', 'Max. size of image') . ': ' . 
            ArrayHelper::getValue($rules, 'maxWidth') . 'x' . ArrayHelper::getValue($rules, 'maxHeight') . 'px'
        ];
    }

    private static function isImageMinSize(array $rules): bool
    {
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return (count($rules) == 2 || count($rules) == 3) && $minWidth && $minHeight;
    }

    private static function imageMinSizeDescription(array $rules): array
    {
        return [
            Yii::t('app.msg', 'Min. size of image') . ': ' . 
            ArrayHelper::getValue($rules, 'minWidth') . 'x' . ArrayHelper::getValue($rules, 'minHeight') . 'px'
        ];
    }

    private static function isImageMaxSize(array $rules): bool
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');

        return (count($rules) == 2 || count($rules) == 3) && $maxWidth && $maxHeight;
    }

    private static function imageMaxSizeDescription(array $rules): array
    {
        return [
            Yii::t('app.msg', 'Max. size of image') . ': ' . 
            ArrayHelper::getValue($rules, 'maxWidth') . 'x' . ArrayHelper::getValue($rules, 'maxHeight') . 'px'
        ];
    }

    private static function imageRulesDescription(array $rules, array $exclude = []): array
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

        return $text;
    }

    /**
     * Get a description of the validation rules in as text
     *
     * @param array $rules Validation rules
     * @return string
     */
    public static function asDescription(array $rules): string
    {
        $text = self::imageSizeDescription($rules);
        $text[] = self::extensionDescription($rules);
        $text[] = self::maxSizeDescription($rules);
        $text[] = self::maxFilesDescription($rules);

        return implode('<br>', array_filter($text));
    }
}
