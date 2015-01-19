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
    <title><?= app\helpers\Util::clearText($this->title) . ' / ' . Yii::$app->name ?></title>
    <?= Html::csrfMetaTags()?>
    <meta name="description" content="<?= e(Yii::$app->controller->description) ?>" />
    <meta name="keywords" content="<?= e(Yii::$app->controller->keywords) ?>" />
    <?php $this->head() ?>
    
</head>
<body>
<?php $this->beginBody() ?>

    <?= $content?>
         
 <?php $this->endBody() ?>                         
</body>
</html>
<?php $this->endPage() ?>