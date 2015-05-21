<?php
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= ($this->title ? app\helpers\Util::clearText($this->title) . ' / ' : '') . Yii::$app->name ?></title>
  <?= Html::csrfMetaTags()?>
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
  <?= $content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
