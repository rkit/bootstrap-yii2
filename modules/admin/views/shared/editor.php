<?php
use yii\helpers\Url;
use app\modules\admin\helpers\FileRulesDescription;
?>
<?= $form->field($model, $attribute)->widget(\vova07\imperavi\Widget::class, [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'maxHeight' => 400,
        'pastePlainText' => true,
        'toolbar' => true,
        'convertVideoLinks' => true,
        'convertLinksUrl' => true,
        'tabSpaces' => 1,
        'italicTag' => 'i',
        'tabAsSpaces' => 4,
        'buttons' => ['bold', 'italic', 'underline', 'link', 'orderedlist', 'unorderedlist', 'image'],
        'plugins' => [
            'video',
            'fullscreen',
        ],
        'imageUpload' => Url::toRoute([$attribute . '-upload']),
        'imageUploadCallback' => new yii\web\JsExpression('function(data) {
            var $form = $(".field-news-text").closest("form");
            $form.yiiActiveForm("updateAttribute", "news-text", "");
        }'),
        'imageUploadErrorCallback' => new yii\web\JsExpression('function(data) {
            var $form = $(".field-news-text").closest("form");
            $form.yiiActiveForm("updateAttribute", "news-text", [data.error]);
        }')
    ]
])->hint(
    'ENTER — ' . Yii::t('app', 'New paragraph') . ', ' .
    'SHIFT + ENTER — ' . Yii::t('app', 'New line') . '<br>' .
    FileRulesDescription::toText($model->fileRules($attribute))
);
