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
 * Uploader.
 * Based on https://github.com/vova07/yii2-fileapi-widget
 * 
 * Usage:
 * ~~~
 * <?= $form->field($model, 'preview', ['template' => "{label}\n{error}\n{input}\n{hint}"])
 *     ->widget(FileApi::className(), [
 *         'template' => '@app/modules/admin/views/shared/files/image',
 *         'crop' => true,
 *         'callbacks' => [
 *             'filecomplete' => new JsExpression('function (evt, uiEvt) { 
 *                 if (uiEvt.result.error) {
 *                     forms.showError(
 *                         $(this).closest(".form"), 
 *                         "' . Html::getInputId($model, 'preview') . '", 
 *                         uiEvt.result.error
 *                     );
 *                 } else {
 *                     forms.clearError("' . Html::getInputId($model, 'preview') . '");
 *                     $(this).find("input[type=\"hidden\"]").val(uiEvt.result.id);
 *                     $(this).find("[data-fileapi=\"browse-text\"]").addClass("hidden");
 *                     $(this).find("[data-fileapi=\"delete\"]").attr("data-fileapi-uid", FileAPI.uid(uiEvt.file));
 *                 }
 *             }'),
 *         ],
 *         'settings' => [
 *             'url' => 'preview-upload',
 *             'imageSize' => $model->getFileRules('preview')['imageSize']
 *         ]
 *     ])
 *     ->hint($model->getFileRulesDescription('preview'), ['class' => 'uploader-rules']
 * ); ?>
 * ~~~
 *
 * Multiple:
 * ~~~
 * <?= $form->field($model, 'gallery', ['template' => "{error}\n{input}\n{hint}"])
 *     ->widget(FileApi::className(), [
 *         'template' => '@app/modules/admin/views/shared/files/gallery',
 *         'files' => $model->getFiles('gallery'),
 *         'preview' => false,
 *         'callbacks' => [
 *             'filecomplete' => new JsExpression('function (evt, uiEvt) { 
 *                 if (uiEvt.result.error) {
 *                     forms.showError(
 *                         $(this).closest(".form"), 
 *                         "uploader-' . Html::getInputId($model, 'gallery') . '", 
 *                         uiEvt.result.error
 *                     );
 *                 } else {
 *                     forms.clearError("uploader-' . Html::getInputId($model, 'gallery') . '");
 *                     $(this).find(".uploader-files").append(uiEvt.result);
 *                 }
 *             }'),
 *         ],
 *         'settings' => [
 *             'url' => 'gallery-upload',
 *             'imageSize' => $model->getFileRules('gallery')['imageSize'],
 *             'multiple' => true
 *         ]
 *     ])
 *     ->hint($model->getFileRulesDescription('gallery'), [
 *         'tag' => 'blockquote', 'class' => 'uploader-rules text-muted small'
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
     * @var array JCrop Default settings.
     */
    public $jcropSettings = [
        'aspectRatio' => 1,
        'bgColor' => '#ffffff',
        'maxSize' => [570],
        'minSize' => [300, 300],
        'keySupport' => false, // Important param to hide jCrop radio button.
        'selection' => '100%'
    ];
    /**
     * @var integer|null Crop resize width.
     */
    public $cropResizeWidth;
    /**
     * @var integer|null Crop resize height.
     */
    public $cropResizeHeight;
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
                'el' => '.uploader-dnd',
                'hover' => '.uploader-dnd-active'
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
                'el' => '.uploader-dnd',
                'hover' => '.uploader-dnd-active'
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
        return $this->selector !== null ? $this->selector : 'uploader-' . $this->options['id'];
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
        
        if ($this->crop === true) {
            $jcropSettings = Json::encode($this->jcropSettings);
            if ($this->cropResizeWidth !== null && $this->cropResizeHeight !== null) {
                $cropResize = "el.fileapi('resize', ufile, $this->cropResizeWidth, $this->cropResizeHeight);";
            } else {
                $cropResize = '';
            }
            
            $cropResizeJs = '
            var ufile = ui.files[0],
            jcropSettings = ' . $jcropSettings . ',
            el = jQuery(this);
            if (ufile) {
                jcropSettings.file = ufile;
                jcropSettings.onSelect = function (coordinates) {
                    jQuery("#' . $selector . '").fileapi("crop", ufile, coordinates);
                    ' . $cropResize . '
                }
                jQuery(document).trigger("' . $selector . '-initialize", [el, jcropSettings]);
            }';
        } else {
            $cropResizeJs = '';
        }
        
        // Crop event handler
        $this->callbacks['select'] = new JsExpression('function (evt, ui) {
            if (ui === undefined) {
                return;
            }
            if (ui.other.length && ui.other[0].errors) {
                $(this)
                    .closest(".form-group")
                    .find(".uploader-rules")
                    .addClass("animated shake")
                    .one(
                        "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", 
                        function () {
                            $(this).removeClass("animated shake");
                        }
                    );
            }' . $cropResizeJs . '
        }');
        
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
