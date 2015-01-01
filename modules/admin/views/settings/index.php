<?php
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Settings');
?>
<?= $this->render('/shared/forms/header', ['hideButtons' => true]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <h2><small><?= Yii::t('app', 'Email') ?></small></h2>   
     
    <div class="well">
    
        <!-- emailMain -->
        <?= $form->field($model, 'emailMain')
            ->hint(Yii::t('app', 'All notifications for users go to this address')) ?>
            
        <!-- emailName -->
        <?= $form->field($model, 'emailName') ?>
            
        <!-- emailPrefix -->
        <?= $form->field($model, 'emailPrefix')
            ->hint(Yii::t('app', 'Subject in the email: "Prefix: Subject"')) ?>
            
    </div>
    
    <?= $this->render('/shared/forms/controls', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>