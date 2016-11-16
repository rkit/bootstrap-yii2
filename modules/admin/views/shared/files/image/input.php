<?php
use yii\web\JsExpression;
use rkit\fileapi\Widget as FileApi;
use app\modules\admin\helpers\FileRulesDescription;
?>
<?= $form->field($model, $attribute, ['template' => "{label}\n{error}\n{input}\n{hint}"])
    ->widget(FileApi::class, [
        'template' => '@app/modules/admin/views/shared/files/image/template',
        'callbacks' => [
            'select' => new JsExpression('function (evt, ui) {
               var $form = $(this).closest("form");
               var field = $(this).find("input:hidden:last").attr("id");

               if (!$form.yiiActiveForm("find", field)) {
                 $form.yiiActiveForm("add", {
                   "id": field,
                   "container": ".field-" + field,
                   "input": "#" + field,
                   "encodeError": false,
                 });
               }
               if (ui && ui.other.length && ui.other[0].errors) {
                 var errors = ["'.Yii::t('app.validators', 'Incorrect file format').': " + ui.other[0].name];
                 $form.yiiActiveForm("updateAttribute", field, errors);
               }
            }'),
            'filecomplete' => new JsExpression('function (evt, ui) {
               var $form = $(this).closest("form");
               var field = $(this).find("input:hidden:last").attr("id");
               var errors = "";
               if (ui.result.error) {
                 errors = [ui.result.error];
               } else {
                 $("#" + field).val(ui.result.id);
                 $(this).find(".fileapi-preview-wrapper").html("<img src=" + ui.result.path + ">");
                 $(this).find(".fileapi-browse-text").text("' . Yii::t('app', 'Uploaded') . '");
               }
               $form.yiiActiveForm("updateAttribute", field, errors);
            }'),
        ],
        'settings' => [
            'url' => yii\helpers\Url::toRoute([$attribute . '-upload']),
            'imageSize' => $model->fileRules($attribute)['imageSize'],
            'accept' => implode(',', $model->fileRules($attribute)['mimeTypes']),
            'duplicate' => true
        ]
    ])
    ->hint(FileRulesDescription::toText($model->fileRules($attribute)), [
        'class' => 'fileapi-rules'
    ]);
