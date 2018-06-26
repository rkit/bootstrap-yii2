<?php

namespace app\modules\admin\helpers;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Converting file validation rules to text
 */
class FileRulesTextHelper
{
    /**
     * @var array Validation rules
     */
    private $rules;
    /**
     * @var array Validation rules of image
     */
    private $imageSize;
    /**
     * @var string Delimiter for rules
     */
    private $delimiter = '<br>';

    /**
     * @param array $rules Validation rules
     * @param string $delimiter Delimiter for rules. Default `<br>` 
     */
    public function __construct(array $rules, $delimiter = null)
    {
        $this->rules = $rules;
        $this->imageSize = ArrayHelper::getValue($rules, 'imageSize', []);

        if ($delimiter !== null) {
            $this->delimiter = $delimiter;
        }
    }

    /**
     * Get a description of the validation rules in as text
     *
     * @return string
     */
    public function __toString()
    {
        $text = [];
        
        $text[] = $this->imageSizeDescription();
        $text[] = $this->extensionDescription();
        $text[] = $this->maxSizeDescription();
        $text[] = $this->maxFilesDescription();

        return implode($this->delimiter, array_filter($text));
    }

    /**
     * Get description for rule the `maximum size`
     * 
     * @return string|null
     */
    public function maxSizeDescription(): ?string
    {
        $rules = ArrayHelper::getValue($this->rules, 'maxSize');
        if ($rules === null) {
            return null;
        }

        return Yii::t('app', 'Max. file size') . ': ' . Yii::$app->formatter->asShortSize($rules) . ' ';
    }

    /**
     * Get description for rule the `maximum count of files`
     * 
     * @return string|null
     */
    public function maxFilesDescription(): ?string
    {
        $rules = ArrayHelper::getValue($this->rules, 'maxFiles');
        if ($rules === null) {
            return null;
        }

        return Yii::t('app', 'Max. file number') . ': ' . $rules . ' ';
    }

    /**
     * Get description for rule the `allowed extensions`
     * 
     * @return string|null
     */
    public function extensionDescription(): ?string
    {
        $rules = ArrayHelper::getValue($this->rules, 'extensions');
        if ($rules === null) {
            return null;
        }

        return Yii::t('app', 'File types') . ': ' . strtoupper(implode(', ', $rules)) . ' ';
    }

    /**
     * Get description for rule the `image sizes`
     * 
     * @return string|null
     */
    public function imageSizeDescription(): ?string
    {
        $text = [];
        switch ($this->imageSize) {
            case $this->isImageStrictSize():
                $text[] = $this->imageStrictSizeDescription();
                break;
            case $this->isImageMinAndMaxSize():
                $text[] = $this->imageMinSizeDescription();
                $text[] = $this->imageMaxSizeDescription();
                break;
            case $this->isImageMinSize():
                $text[] = $this->imageMinSizeDescription();
                $text = array_merge($text, $this->imageRules(['minWidth', 'minHeight']));
                break;
            case $this->isImageMaxSize():
                $text[] = $this->imageMaxSizeDescription();
                $text = array_merge($text, $this->imageRules(['maxWidth', 'maxHeight']));
                break;
            default:
                $text = $this->imageRules();
                break;
        }

        if (count($text)) {
            return implode($this->delimiter, array_filter($text));
        }

        return null;
    }

    /**
     * Whether the image has strict size
     * 
     * @return bool 
     */
    public function isImageStrictSize(): bool
    {
        $maxWidth  = ArrayHelper::getValue($this->imageSize, 'maxWidth');
        $minWidth  = ArrayHelper::getValue($this->imageSize, 'minWidth');
        $maxHeight = ArrayHelper::getValue($this->imageSize, 'maxHeight');
        $minHeight = ArrayHelper::getValue($this->imageSize, 'minHeight');

        return count($this->imageSize) == 4 && ($maxWidth == $minWidth && $maxHeight == $minHeight);
    }

    /**
     * Get description for image with strict size
     * 
     * @return string
     */
    public function imageStrictSizeDescription(): string
    {
        return
            Yii::t('app', 'Image size') . ': ' . 
            ArrayHelper::getValue($this->imageSize, 'maxWidth') . 'x' . 
            ArrayHelper::getValue($this->imageSize, 'maxHeight') . 'px';
    }

    /**
     * Whether the image has max and min size
     * 
     * @return bool 
     */
    public function isImageMinAndMaxSize(): bool
    {
        return count($this->imageSize) == 4;
    }

    /**
     * Whether the image has min size
     * 
     * @return bool 
     */
    public function isImageMinSize(): bool
    {
        $minWidth  = ArrayHelper::getValue($this->imageSize, 'minWidth');
        $minHeight = ArrayHelper::getValue($this->imageSize, 'minHeight');

        return (count($this->imageSize) == 2 || count($this->imageSize) == 3) && $minWidth && $minHeight;
    }

    /**
     * Get description for image with min size
     * 
     * @return string
     */
    public function imageMinSizeDescription(): string
    {
        return
            Yii::t('app', 'Min. size of image') . ': ' . 
            ArrayHelper::getValue($this->imageSize, 'minWidth') . 'x' . 
            ArrayHelper::getValue($this->imageSize, 'minHeight') . 'px';
    }

    /**
     * Whether the image has max size
     * 
     * @return bool 
     */
    public function isImageMaxSize(): bool
    {
        $maxWidth  = ArrayHelper::getValue($this->imageSize, 'maxWidth');
        $maxHeight = ArrayHelper::getValue($this->imageSize, 'maxHeight');

        return (count($this->imageSize) == 2 || count($this->imageSize) == 3) && $maxWidth && $maxHeight;
    }

    /**
     * Get description for image with max size
     * 
     * @return string
     */
    public function imageMaxSizeDescription(): string
    {
        return
            Yii::t('app', 'Max. size of image') . ': ' . 
            ArrayHelper::getValue($this->imageSize, 'maxWidth') . 'x' . 
            ArrayHelper::getValue($this->imageSize, 'maxHeight') . 'px';
    }

    private function imageRules(array $exclude = []): array
    {
        $rules = $this->imageSize;
        foreach ($exclude as $item) {
            unset($rules[$item]);
        }

        $text = [];
        foreach ($rules as $rule => $value) {
            switch ($rule) {
                case 'minWidth':
                    $text[] = Yii::t('app', 'Min. width') . ' ' . $value . 'px';
                    break;
                case 'minHeight':
                    $text[] = Yii::t('app', 'Min. height') . ' ' . $value . 'px';
                    break;
                case 'maxWidth':
                    $text[] = Yii::t('app', 'Max. width') . ' ' . $value . 'px';
                    break;
                case 'maxHeight':
                    $text[] = Yii::t('app', 'Max. height') . ' ' . $value . 'px';
                    break;
            }
        }

        return $text;
    }
}
