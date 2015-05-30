<?php

namespace app\widgets\FileApi;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use Yii;

/**
 * FileApi
 * Widget for https://github.com/RubaXa/jquery.fileapi/
 *
 * Usage:
 * ~~~
 * <?= $form->field($model, $attribute, ['template' => "{label}\n{error}\n{input}\n{hint}"])
 *     ->widget(FileApi::className(), [
 *         'template' => '@app/modules/admin/views/shared/files/image-template',
 *         'crop' => $crop,
 *         'callbacks' => [
 *             'select' => new JsExpression('function (evt, ui) {

 *             }'),
 *             'filecomplete' => new JsExpression('function (evt, uiEvt) {

 *             }'),
 *         ],
 *         'settings' => [
 *             'url' => $attribute . '-upload',
 *             'imageSize' => $model->getFileRules($attribute)['imageSize'],
 *             'accept' => implode(',', $model->getFileRules($attribute)['mimeTypes']),
 *         ]
 *     ])
 *     ->hint($model->getFileRulesDescription($attribute), [
 *         'class' => 'fileapi-rules'
 *     ]
 * ); ?>
 * ~~~
 */
class Widget extends InputWidget
{
    /**
     * @var string FileAPI selector.
     */
    public $selector;
    /**
     * @var string The parameter name for the file form data (the request argument name).
     */
    public $paramName = 'file';
    /**
     * @var array {@link https://github.com/RubaXa/jquery.fileapi/ FileAPI options}
     */
    public $settings = [];
    /**
     * @var string Template view.
     */
    public $template;
    /**
     * @var string Title for button.
     */
    public $title = null;
    /**
     * @var array
     */
    public $files;
    /**
     * @var array FileAPI events array.
     */
    public $callbacks = [];
    /**
     * @var boolean Enable/disable files preview.
     */
    public $preview = true;
    /**
     * @var boolean Enable/disable crop.
     */
    public $crop = false;
    /**
     * @var array Default settings array for single upload.
     */
    private $defaultSettings = [
        'autoUpload' => true,
        'elements' => [
            'progress' => '[data-fileapi="progress"]',
            'active' => [
                'show' => '[data-fileapi="active.show"]',
                'hide' => '[data-fileapi="active.hide"]'
            ],
            'name' => '[data-fileapi="name"]',
            'preview' => [
                'el' => '[data-fileapi="preview"]',
                'width' => 200,
                'height' => 200,
                'keepAspectRatio' => true
            ],
            'dnd' => [
                // DropZone: selector or element
                'el' => '.fileapi-dnd',
                'hover' => '.fileapi-dnd-active'
            ]
        ]
    ];
    /**
     * @var array Default settings array for multiple upload.
     */
    private $defaultMultipleSettings = [
        'autoUpload' => true,
        'elements' => [
            'progress' => '[data-fileapi="progress"]',
            'active' => [
                'show' => '[data-fileapi="active.show"]',
                'hide' => '[data-fileapi="active.hide"]'
            ],
            'name' => '[data-fileapi="name"]',
            'dnd' => [
                // DropZone: selector or element
                'el' => '.fileapi-dnd',
                'hover' => '.fileapi-dnd-active'
            ]
        ]
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        $request = Yii::$app->getRequest();

        if ($this->title === null) {
            $this->title = Yii::t('fileapi', 'BTN_UPLOAD');
        }

        if ($request->enableCsrfValidation === true) {
            $this->settings['data'][$request->csrfParam] = $request->getCsrfToken();
        }

        if (!isset($this->settings['url'])) {
            $this->settings['url'] = $request->getUrl();
        } else {
            $this->settings['url'] = Url::to($this->settings['url']);
        }

        if ($this->crop === true) {
            $this->settings['autoUpload'] = false;
        }

        if (isset($this->settings['multiple']) && $this->settings['multiple'] === true) {
            if ($this->preview === false) {
                unset($this->defaultMultipleSettings['elements']['file']['preview']);
            }
            $this->defaultSettings = $this->defaultMultipleSettings;
        }

        $this->settings = ArrayHelper::merge($this->defaultSettings, $this->settings);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->register();

        $input = $this->hasModel()
        ? Html::activeHiddenInput($this->model, $this->attribute, $this->options)
        : Html::hiddenInput($this->name, $this->value, $this->options);

        return $this->render(
            $this->template,
            [
                'selector' => $this->getSelector(),
                'input' => $input,
                'title' => $this->title,
                'paramName' => $this->paramName,
                'value' => $this->model->{$this->attribute},
                'preview' => $this->preview,
                'crop' => $this->crop,
                'files' => $this->files,
                'model' => $this->model,
                'attribute' => $this->attribute
            ]
        );
    }

    /**
     * @return string Widget selector
     */
    public function getSelector()
    {
        return $this->selector !== null ? $this->selector : 'fileapi-' . $this->options['id'];
    }

    /**
     * Register all widget scripts and callbacks
     */
    public function register()
    {
        $this->registerFiles();

        $selector = $this->getSelector();
        $options = Json::encode($this->settings);
        $view = $this->getView();

        Asset::register($view);

        if ($this->crop === true) {
            CropAsset::register($view);
        }

        $view->registerJs("jQuery('#$selector').fileapi($options);");

        $this->registerCallbacks();
    }

    /**
     * Registering already uploaded files.
     */
    public function registerFiles()
    {
        if (!isset($this->settings['multiple']) || $this->settings['multiple'] === false) {
            if ($this->hasModel() &&
                $this->model->{$this->attribute} &&
                file_exists(Yii::getAlias('@webroot') . '/' . $this->model->{$this->attribute})
            ) {
                $this->settings['files'][] = [
                    'src'  => $this->model->{$this->attribute},
                    'name' => Yii::t('fileapi', 'FILE_LOADED'),
                ];
            }
        }
    }

    /**
     * Register widget callbacks.
     */
    protected function registerCallbacks()
    {
        if (!empty($this->callbacks)) {
            $selector = $this->getSelector();
            $view = $this->getView();
            foreach ($this->callbacks as $event => $callback) {
                if (is_array($callback)) {
                    foreach ($callback as $function) {
                        if (!$function instanceof JsExpression) {
                            $function = new JsExpression($function);
                        }
                        $view->registerJs("jQuery('#$selector').on('$event', $function);");
                    }
                } else {
                    if (!$callback instanceof JsExpression) {
                        $callback = new JsExpression($callback);
                    }
                    $view->registerJs("jQuery('#$selector').on('$event', $callback);");
                }
            }
        }
    }

    /**
     * Register widget translator.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['fileapi'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/widgets/FileApi/messages',
            'forceTranslation' => true,
            'fileMap' => [
                'widgets/FileApi/messages' => 'fileapi.php',
            ],
        ];
    }
}
