<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use app\widgets\FileApi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{label}\n{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/image',
        'crop' => $crop,
        'callbacks' => [
            'filecomplete' => new JsExpression('function (evt, uiEvt) { 
                if (uiEvt.result.error) {
                    forms.showError(
                        $(this).closest(".form"), 
                        "' . Html::getInputId($model, $attribute) . '", 
                        uiEvt.result.error
                    );
                } else {
                    forms.clearError("' . Html::getInputId($model, $attribute) . '");
                    $(this).find("input[type=\"hidden\"]").val(uiEvt.result.id);
                    $(this).find("[data-fileapi=\"browse-text\"]").addClass("hidden");
                    $(this).find("[data-fileapi=\"delete\"]").attr("data-fileapi-uid", FileAPI.uid(uiEvt.file));
                }
            }'),
        ],
        'settings' => [
            'url' => $attribute . '-upload',
            'imageSize' => $model->getFileRules($attribute)['imageSize']
        ]
    ])
    ->hint($model->getFileRulesDescription($attribute), [
        'class' => 'uploader-rules'
    ]
); ?>