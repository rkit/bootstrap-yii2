<?php
use yii\helpers\Html;
?>
<div id="<?= $selector; ?>" class="fileapi">
  <div class="btn btn-default btn-small">
    <div class="fileapi-browse" data-fileapi="active.hide">
      <span class="glyphicon glyphicon-picture"></span>
      <span><?= Yii::t('app', 'Upload')?></span>
      <input type="file" name="<?= $inputName ?>">
    </div>
  </div>
  <?= Html::activeHiddenInput($model, $attribute.'[]', [
      'id' => Html::getInputId($model, $attribute)
  ]) ?>
</div>
