<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use rkit\fileapi\Widget as FileApi;
?>
<?php $model->$attribute = null;?>
<?= $form->field($model, $attribute, ['template' => "{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/gallery/template',
        'preview' => false,
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
                  var errors = ui.other.map(function(v) {
                    return v.name + ": '.Yii::t('app.messages', 'Incorrect file format').'";
                  });

                  if ($(".field-" + field).hasClass("has-error")) {
                      errors.push($(".field-" + field + " .help-block").html());
                  }

                  errors = errors.length ? [errors.join("<br>")] : "";
                  $form.yiiActiveForm("updateAttribute", field, errors);
                }
            }'),
            'filecomplete' => new JsExpression('function (evt, ui) {
               var $form = $(this).closest("form");
               var field = $(this).find("input:hidden:last").attr("id");
               var errors = [];

               if (ui.result.error) {
                 errors.push(ui.file.name + ": " + ui.result.error);
               } else {
                 $("#" + field).val(ui.result.id);
                 $(this).find(".fileapi-files").append(ui.result);
                 $(this).find(".sortable").sortable();
               }
               if ($(".field-" + field).hasClass("has-error")) {
                   errors.push($(".field-" + field + " .help-block").html());
               }
               errors = errors.length ? [errors.join("<br>")] : "";
               $form.yiiActiveForm("updateAttribute", field, errors);
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
    ]);
