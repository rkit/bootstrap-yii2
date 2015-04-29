<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use app\widgets\FileApi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/gallery-template',
        'files' => $model->getFiles($attribute),
        'preview' => false,
        'callbacks' => [
            'select' => new JsExpression('function (evt, ui) {
                if (ui && ui.other.length && ui.other[0].errors) {
                    alert("'.Yii::t('app', 'Incorrect file format').': " + ui.other.map(function(v) { return v.name; }));
                }
            }'),
            'filecomplete' => new JsExpression('function (evt, uiEvt) {
                if (uiEvt.result.error) {
                    forms.showError(
                        $(this).closest(".form"),
                        "fileapi-' . Html::getInputId($model, $attribute) . '",
                        uiEvt.result.error
                    );
                } else {
                    forms.clearError("fileapi-' . Html::getInputId($model, $attribute) . '");
                    $(this).find(".fileapi-files").append(uiEvt.result);
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
        'class' => 'fileapi-rules'
    ]
); ?>
