<?php
use yii\helpers\Html;
?>
<div id="<?= $selector; ?>" class="fileapi">
  <div class="btn btn-default btn-small fileapi-fileapi-wrapper">
    <div class="fileapi-browse" data-fileapi="active.hide">
      <span class="glyphicon glyphicon-picture"></span>
      <span><?= Yii::t('app', 'Upload')?></span>
      <input type="file" name="<?= $inputName ?>">
    </div>
  </div>
  <?php $files = $model->getFiles($attribute); $items = []; foreach ($files as $file) {
      $content = $this->render('gallery-item', [
          'file' => $file,
          'model' => $model,
          'attribute' => $attribute
      ]);
      $content = str_replace(['<li>', '</li>'], '', $content);
      $items[]['content'] = $content;
  };?>
  <?= \kartik\sortable\Sortable::widget([
      'options' => [
          'class' => 'fileapi-files',
      ],
      'pluginOptions' => [
          'handle' => 'img'
      ],
    'items' => $items
  ]);
  ?>
  <?= Html::hiddenInput(Html::getInputName($model, $attribute) . '[]', null, ['id' => Html::getInputId($model, $attribute)]) ?>
</div>
