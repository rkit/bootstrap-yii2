<?php

namespace app\controllers\common;

use app\models\File;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\web\UploadedFile;
use Yii;

class UploadAction extends Action
{
    /**
     * @var string $modelName
     */
    public $modelName;
    /**
     * @var string $attribute
     */
    public $attribute;
    /**
     * @var string $inputName
     */
    public $inputName;
    /**
     * @var string $type Image Or File
     */
    public $type = 'image';
    /**
     * @var string $multiple
     */
    public $multiple = false;
    /**
     * @var string $template Path to template.
     */
    public $template;
    /**
     * @var string $resultName
     */
    public $resultName = 'path';
    /**
     * @var bool $saveTmpFile Save temporary file.
     */
    public $saveTmpFile = false;
    /**
     * @var ActiveRecord $model
     */
    private $model;
    /**
     * @var array $rules
     */
    private $rules;
    /**
     * @var array $resizeRules
     */
    private $resizeRules;

    public function init()
    {
        if ($this->modelName === null) {
            throw new InvalidParamException('The "modelName" attribute must be set.');
        }

        $this->model = new $this->modelName();

        $this->rules = $this->model->getFileRules($this->attribute);
        $this->resizeRules = $this->model->getFileResizeRules($this->attribute);

        if (isset($this->rules['imageSize'])) {
            $this->rules = array_merge($this->rules, $this->rules['imageSize']);
            unset($this->rules['imageSize']);
        }
    }

    public function run()
    {
        $file = UploadedFile::getInstanceByName($this->inputName);

        if (!$file) {
            return $this->controller->response(['error' => Yii::t('app', 'An error occured, try again later…')]);
        }

        $model = new DynamicModel(compact('file'));
        $model->addRule('file', $this->type, $this->rules)->validate();

        if ($model->hasErrors()) {
            return $this->controller->response(['error' => $model->getFirstError('file')]);
        } else {
            $ownerType = $this->model->getFileOwnerType($this->attribute);
            if ($file = File::createFromUploader($file, $ownerType, $this->saveTmpFile)) {
                if (count($this->resizeRules)) {
                    File::resize(
                        $file->path(),
                        $this->resizeRules['width'],
                        $this->resizeRules['height'],
                        $this->resizeRules['ratio'], true);
                }
                if ($this->multiple) {
                    return $this->controller->response(
                        $this->controller->renderPartial($this->template, [
                            'file' => $file,
                            'model' => $this->model,
                            'attribute' => $this->attribute
                        ])
                    );
                } else {
                    return $this->controller->response(['id' => $file->id, $this->resultName => $file->path()]);
                }
            } else {
                return $this->controller->response(['error' => Yii::t('app', 'Error saving file')]);
            }
        }
    }
}
