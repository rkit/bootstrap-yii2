<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="pull-right pagination-sizes">
  <div class="btn-group">
    <?php foreach ($dataProvider->pagination->pageSizeLimit as $size): ?>
    <a href="<?= Url::to(['', 'pageSize' => $size] + $_GET) ?>"
       class="btn btn-default <?= $size == $dataProvider->pagination->pageSize ? 'active' : '' ?>">
      <?= $size ?>
    </a>
    <?php endforeach ?>
  </div>
</div>
