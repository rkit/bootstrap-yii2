<?php
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Settings');
?>
<?= $this->render('/shared/forms/header', ['hideButtons' => true]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <h2><small><?= Yii::t('app', 'Email') ?></small></h2>

    <div class="well">
        <!-- emailMain -->
        <?= $form->field($model, 'emailMain') ?>
        <!-- emailName -->
        <?= $form->field($model, 'emailName') ?>
        <!-- emailPrefix -->
        <?= $form->field($model, 'emailPrefix') ?>
    </div>

<?= $this->render('/shared/forms/bottom', ['model' => $model]) ?>
