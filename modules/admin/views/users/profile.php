<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;

$this->title = Yii::t('app', 'Users');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->user->username ?: $model->user->email, 'model' => $model]) ?>

<ul class="nav nav-tabs">
    <li><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->userId]) ?></li>
    <li class="active"><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->userId]) ?></li>
</ul><br>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <div class="row">
        <div class="col-md-8">
            <!-- fullName -->
            <?= $form->field($model, 'fullName')->textInput() ?>
            
            <!-- birthDay -->
            <?php $model->birthDay = $model->birthDay > 0 ? $model->birthDay : '' ?>
            <?= $form->field($model, 'birthDay')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]); ?>
        </div>
        <div class="col-md-4">
            <!-- photo -->
            <?= $this->render('/shared/files/image-input', [
                'form' => $form, 'model' => $model, 'attribute' => 'photo', 'crop' => true
            ]) ?>
        </div>
    </div>
        
    <?= $this->render('/shared/forms/controls', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>