<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use rkit\fileapi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/gallery-template',
        'preview' => false,
        'callbacks' => [
            'select' => new JsExpression('function (evt, ui) {
               if (ui && ui.other.length && ui.other[0].errors) {
                 alert("'.Yii::t('app', 'Incorrect file format').': " + ui.other.map(function(v) { return v.name; }));
               }
            }'),
            'filecomplete' => new JsExpression('function (evt, uiEvt) {
               if (uiEvt.result.error) {
                 alert(uiEvt.result.error);
               } else {
                 $(".field-' . Html::getInputId($model, $attribute) . '").find(".help-block").empty();
                 $(".field-' . Html::getInputId($model, $attribute) . '").removeClass("has-error");
                 $(this).find(".fileapi-files").append(uiEvt.result);
                 $(this).find(".sortable").sortable();
               }
            }'),
        ],
        'settings' => [
            'url' => yii\helpers\Url::toRoute([$attribute . '-upload']),
            'imageSize' => $model->getFileRules($attribute)['imageSize'],
            'multiple' => true,
            'duplicate' => true
        ]
    ])
    ->hint($model->getFileRulesDescription($attribute), [
        'class' => 'fileapi-rules'
    ]
); ?>
