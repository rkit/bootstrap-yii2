<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'Users');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->user->username ?: $model->user->email, 'model' => $model]) ?>

<ul class="nav nav-tabs">
    <li><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->user_id]) ?></li>
    <li class="active"><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->user_id]) ?></li>
</ul><br>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <div class="row">
        <div class="col-md-8">
            <!-- full_name -->
            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

            <!-- birth_day -->
            <?php $model->birth_day = $model->birth_day > 0 ? $model->birth_day : '' ?>
            <?= $form->field($model, 'birth_day')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]); ?>
        </div>
        <div class="col-md-4">
            <!-- photo -->
            <?= $this->render('/shared/files/image-input', [
                'form' => $form,
                'model' => $model,
                'attribute' => 'photo',
                'crop' => true,
                'cropMinSize' => [300, 300]
            ]) ?>
        </div>
    </div>

    <?= $this->render('/shared/forms/controls', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>
