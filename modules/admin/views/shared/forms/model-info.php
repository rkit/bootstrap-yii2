<?php if (isset($model->date_create) && isset($model->date_update)): ?>
<div class="form-inline pull-right text-right text-muted small">
  <div class="form-group">
    <?= Yii::t('app', 'Date create') ?><br>
    <?= Yii::$app->formatter->asDatetime($model->date_create, 'short') ?>
  </div>&nbsp;&nbsp;&nbsp;
  <div class="form-group">
    <?= Yii::t('app', 'Date update') ?><br>
    <?= Yii::$app->formatter->asDatetime($model->date_update, 'short') ?>
  </div>
</div>
<?php endif?>
