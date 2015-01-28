<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Signup');
?>
<div class="site-signup">
    <p class="lead">
        <?= Html::encode($this->title) . ' '. Yii::t('app', 'through') . ' '. ucfirst($model->provider) ?>
    </p>
    <hr>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <p><?= Yii::t('app', 'To complete the registration enter your email address') ?></p>
                <?= $form->field($model, 'email') ?>
                <hr>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>  
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>