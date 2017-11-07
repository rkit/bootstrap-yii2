<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use rkit\fileapi\Widget as FileApi;
use app\modules\admin\helpers\FileRulesDescription;

$this->title = Yii::t('app', 'Users') . ' / ' . $model->user_id;
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<ul class="nav nav-tabs">
  <li><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->user_id]) ?></li>
  <li class="active"><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->user_id]) ?></li>
</ul><br>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

    <!-- full_name -->
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <!-- photo -->
    <?= $form->field($model, 'photo', ['template' => "{label}\n{error}\n{input}\n{hint}"])
        ->widget(FileApi::class, [
            'template' => '@app/modules/admin/views/shared/files/image/template',
            'callbacks' => [
                'select' => new JsExpression('function (evt, ui) {
                if (ui && ui.other.length && ui.other[0].errors) {
                    alert("' . Yii::t('app.msg', 'Incorrect file format') . '");
                }
                }'),
                'filecomplete' => new JsExpression('function (evt, ui) {
                if (ui.result.error) {
                    alert(ui.result.error);
                    return;
                }
                $(this).find("input:hidden:last").val(ui.result.id);
                $(this).find(".fileapi-preview-wrapper").html("<img src=" + ui.result.path + ">");
                $(this).closest("form").yiiActiveForm("updateAttribute", "' . Html::getInputId($model, 'photo') . '", []);
                }'),
            ],
            'settings' => [
                'url' => yii\helpers\Url::toRoute(['photo-upload']),
                'imageSize' => $model->model()->fileRules('photo')['imageSize'],
                'accept' => implode(',', $model->model()->fileRules('photo')['mimeTypes']),
                'imageAutoOrientation' => false,
                'duplicate' => true
            ]
        ])
        ->hint(FileRulesDescription::asDescription($model->model()->fileRules('photo')), [
            'class' => 'fileapi-rules'
        ]);
    ?>

  <hr>
  <div class="form-controls">
    <div class="form-group">
      <?= Html::submitButton(Yii::t('app', 'Save'), [
          'name' => 'submit',
          'class' => 'btn btn-info',
          'data-loading-text' => Yii::t('app', 'Please wait…')
      ]) ?>
      <?= Html::a(
          Yii::t('app', 'Delete'),
          ['delete', 'id' => $model->user_id, 'reload' => true],
          [
              'title' => Yii::t('app', 'Delete'),
              'class' => 'btn btn-danger',
              'data-confirm' => Yii::t('app', 'Are you sure you want to delete this user?')
          ]
      ); ?>
    </div>
  </div>

<?php ActiveForm::end(); ?>
