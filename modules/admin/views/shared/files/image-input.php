<?php
use yii\helpers\Html;
use yii\web\JsExpression;
use app\widgets\FileApi\Widget as FileApi;
?>
<?= $form->field($model, $attribute, ['template' => "{label}\n{error}\n{input}\n{hint}"])
    ->widget(FileApi::className(), [
        'template' => '@app/modules/admin/views/shared/files/image-template',
        'crop' => $crop,
        'callbacks' => [
            'select' => new JsExpression('function (evt, ui) {
                var ufile = ui.files[0],
                $el = $(this);

                if (ui && ui.other.length && ui.other[0].errors) {
                    alert("'.Yii::t('app', 'Incorrect file format').': " + ui.other[0].name);
                }

                if (ufile && $el.find(".crop-area").length) {
                    $el.find(".modal").modal("show");

                    setTimeout(function () {
                        $el.find(".crop-area").cropper({
                            "file": ufile,
                            "aspectRatio": 1,
                            "bgColor": "#ffffff",
                            "maxSize": [570],
                            "minSize": '.($crop ? json_encode($cropMinSize) : "[]").',
                            "keySupport": false,
                            "selection": "100%",
                            "onSelect": function (coordinates) {
                                $el.fileapi("crop", ufile, coordinates);
                            }
                        });
                    }, 700);

                    $el.on("click", ".crop-save",
                        function() {
                            $el.fileapi("upload");
                            $(this).closest(".modal").modal("hide");
                        }
                    );
                }
            }'),
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
            'imageSize' => $model->getFileRules($attribute)['imageSize'],
            'accept' => implode(',', $model->getFileRules($attribute)['mimeTypes']),
        ]
    ])
    ->hint($model->getFileRulesDescription($attribute), [
        'class' => 'fileapi-rules'
    ]
); ?>
