<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use rkit\fileapi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{label}\n{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/image-template',
        'callbacks' => [
            'select' => new JsExpression('function (evt, ui) {
               var ufile = ui.files[0],
               $el = $(this);

               if (ui && ui.other.length && ui.other[0].errors) {
                 alert("'.Yii::t('app', 'Incorrect file format').': " + ui.other[0].name);
               }
            }'),
            'filecomplete' => [new JsExpression('function (evt, uiEvt) {
               if (uiEvt.result.error) {
                 alert(uiEvt.result.error);
               } else {
                 $(".field-' . Html::getInputId($model, $attribute) . '").find(".help-block").empty();
                 $(".field-' . Html::getInputId($model, $attribute) . '").removeClass("has-error");
                 $(this).find("input[type=\"hidden\"]").val(uiEvt.result.id);
                 $(this).find(".fileapi-preview-wrapper").html("<img src=" + uiEvt.result.path + ">");
                 $(this).find(".fileapi-browse-text").text("' . Yii::t('app', 'Uploaded') . '");
               }
            }'),
        ]],
        'settings' => [
            'url' => yii\helpers\Url::toRoute([$attribute . '-upload']),
            'imageSize' => $model->getFileRules($attribute)['imageSize'],
            'accept' => implode(',', $model->getFileRules($attribute)['mimeTypes']),
            'duplicate' => true
        ]
    ])
    ->hint($model->getFileRulesDescription($attribute), [
        'class' => 'fileapi-rules'
    ]); ?>
