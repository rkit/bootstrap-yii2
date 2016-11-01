<?php

namespace app\modules\admin\helpers;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Formatting of validation rules
 */
class FileRulesDescription
{
    private static function maxSizeDescription($value)
    {
        if ($value === null) {
            return '';
        }
        $value = Yii::$app->formatter->asShortSize($value);
        return Yii::t('app.validators', 'Max. file size') . ': ' . $value . ' ';
    }

    private static function maxFilesDescription($value)
    {
        if ($value === null) {
            return '';
        }
        return Yii::t('app.validators', 'Max. file number') . ': ' . $value . ' ';
    }

    private static function extensionDescription($value)
    {
        if ($value === null) {
            return '';
        }
        $value = strtoupper(implode(', ', $value));
        return Yii::t('app.validators', 'File types') . ': ' . $value . ' ';
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private static function imageSizeDescription($rules)
    {
        if ($rules === null) {
            return '';
        }

        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        $text = [];
        switch ($rules) {
            case self::isImageWithStrictSize($rules):
                $text[] = Yii::t('app.validators', 'Image size') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                break;
            case self::isImageWithMinAndMaxSize($rules):
                $text[] = Yii::t('app.validators', 'Min. size of image') . ': ' . $minWidth . 'x' . $minHeight . 'px';
                $text[] = Yii::t('app.validators', 'Max. size of image') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                break;
            case self::isImageWithMinSize($rules):
                $text[] = Yii::t('app.validators', 'Min. size of image') . ': ' . $minWidth . 'x' . $minHeight . 'px';
                $text[] = self::prepareImageSizeDescription($rules, ['minWidth', 'minHeight']);
                break;
            case self::isImageWithMaxSize($rules):
                $text[] = Yii::t('app.validators', 'Max. size of image') . ': ' . $maxWidth . 'x' . $maxHeight . 'px';
                $text[] = self::prepareImageSizeDescription($rules, ['maxWidth', 'maxHeight']);
                break;
            default:
                $text[] = self::prepareImageSizeDescription($rules);
                break;
        }

        return implode('<br>', array_filter($text));
    }

    private static function isImageWithStrictSize($rules)
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return count($rules) == 4 && ($maxWidth == $minWidth && $maxHeight == $minHeight);
    }

    private static function isImageWithMinAndMaxSize($rules)
    {
        return count($rules) == 4;
    }

    private static function isImageWithMinSize($rules)
    {
        $minWidth  = ArrayHelper::getValue($rules, 'minWidth');
        $minHeight = ArrayHelper::getValue($rules, 'minHeight');

        return (count($rules) == 2 || count($rules) == 3) && $minWidth && $minHeight;
    }

    private static function isImageWithMaxSize($rules)
    {
        $maxWidth  = ArrayHelper::getValue($rules, 'maxWidth');
        $maxHeight = ArrayHelper::getValue($rules, 'maxHeight');

        return (count($rules) == 2 || count($rules) == 3) && $maxWidth && $maxHeight;
    }

    private static function prepareImageSizeDescription($rules, $exclude = [])
    {
        foreach ($exclude as $item) {
            unset($rules[$item]);
        }

        $text = [];
        foreach ($rules as $rule => $value) {
            switch ($rule) {
                case 'minWidth':
                    $text[] = Yii::t('app.validators', 'Min. width') . ' ' . $value . 'px';
                    break;
                case 'minHeight':
                    $text[] = Yii::t('app.validators', 'Min. height') . ' ' . $value . 'px';
                    break;
                case 'maxWidth':
                    $text[] = Yii::t('app.validators', 'Max. width') . ' ' . $value . 'px';
                    break;
                case 'maxHeight':
                    $text[] = Yii::t('app.validators', 'Max. height') . ' ' . $value . 'px';
                    break;
            }
        }

        return implode('<br>', array_filter($text));
    }

    /**
     * Get a description of the validation rules in as text
     *
     * @param array $rules Validation rules
     * @return string
     */
    public static function toText($rules)
    {
        $text = [];
        $text[] = self::imageSizeDescription(ArrayHelper::getValue($rules, 'imageSize'));
        $text[] = self::extensionDescription(ArrayHelper::getValue($rules, 'extensions'));
        $text[] = self::maxSizeDescription(ArrayHelper::getValue($rules, 'maxSize'));
        $text[] = self::maxFilesDescription(ArrayHelper::getValue($rules, 'maxFiles'));

        return implode('<br>', array_filter($text));
    }
}
