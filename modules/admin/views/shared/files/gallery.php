<?php
use yii\helpers\Html;
?>
<div id="<?= $selector; ?>" class="uploader">
    <div class="btn btn-default btn-small uploader-fileapi-wrapper">
       <div class="uploader-browse" data-fileapi="active.hide">
           <span class="glyphicon glyphicon-picture"></span>
           <span><?= Yii::t('app', 'Upload') ?></span>
           <input type="file" name="<?= $paramName ?>">
       </div>
    </div>
    <?php $items = []; foreach ($files as $file) {
          $items[] = $this->render('gallery-item', [
            'file' => $file, 
            'model' => $model, 
            'attribute' => $attribute
        ]); 
    } ?>
    <?= yii\jui\Sortable::widget([
        'options' => ['tag' => 'ul', 'class' => 'uploader-files'],
        'itemOptions' => ['tag' => 'li'],
        'items' => $items,
        'clientOptions' => ['cursor' => 'move'],
    ]); ?>
    <?= Html::hiddenInput(Html::getInputName($model, $attribute) . '[]') ?>
</div>