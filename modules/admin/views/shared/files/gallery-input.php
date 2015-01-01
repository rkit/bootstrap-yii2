<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use app\widgets\FileApi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/gallery',
        'files' => $model->getFiles($attribute),
        'preview' => false,
        'callbacks' => [
            'filecomplete' => new JsExpression('function (evt, uiEvt) { 
                if (uiEvt.result.error) {
                    forms.showError(
                        $(this).closest(".form"), 
                        "uploader-' . Html::getInputId($model, $attribute) . '", 
                        uiEvt.result.error
                    );
                } else {
                    forms.clearError("uploader-' . Html::getInputId($model, $attribute) . '");
                    $(this).find(".uploader-files").append(uiEvt.result);
                }
            }'),
        ],
        'settings' => [
            'url' => $attribute . '-upload',
            'imageSize' => $model->getFileRules($attribute)['imageSize'],
            'multiple' => true
        ]
    ])
    ->hint($model->getFileRulesDescription($attribute), [
        'class' => 'uploader-rules'
    ]
); ?>