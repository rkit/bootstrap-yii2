<?php
use yii\helpers\Url;
?>
<?= $form->field($model, $attribute)->widget(\vova07\imperavi\Widget::className(), [
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
        'imageUpload' => Url::to($imageUploadUrl),
        'imageUploadErrorCallback' => new yii\web\JsExpression('function(data) { alert(data.error); }')
    ]
])->hint(
    'ENTER — ' . Yii::t('app', 'New paragraph') . ', ' .
    'SHIFT + ENTER — ' . Yii::t('app', 'New line') . '<br>' . 
     $model->getFileRulesDescription($attribute)
); ?>