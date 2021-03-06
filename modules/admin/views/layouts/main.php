<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\modules\admin\assets\AppAsset;

$pageTitle = $this->title;
$pageTitle = str_replace('"', '“', $pageTitle);
$pageTitle = Html::encode($pageTitle);
$pageTitle = $pageTitle ? $pageTitle . ' / ' : '';

AppAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $pageTitle . Yii::t('app', 'Control Panel') ?></title>
  <?= Html::csrfMetaTags() ?>
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="64x64" href="/img/favicon/favicon-64x64.png">
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
  <?php if (Yii::$app->controller->action->id == 'login') :?>
  <div class="container">
    <?= $content?>
  </div>
  <?php else : ?>
  <div class="container-fluid">
    <div class="row">
      <div class="sidebar">
        <div class="logo">
          <?= Html::a(Yii::$app->name, '/admin') ?>
        </div>

        <?= $this->render('/shared/menu') ?><hr>
        <div class="sidebar-footer">
          <?= Nav::widget([
              'items' => [
                  [
                      'label' => Yii::t('app', 'Go to site'),
                      'url' => ['/'],
                  ],
                  [
                      'label' => Yii::t('app', 'Exit'),
                      'url' => ['index/logout'],
                      'linkOptions' => ['data-method' => 'post'],
                  ],
              ],
          ]); ?>
        </div>
      </div>
      <div class="content col-sm-offset-3 col-md-offset-2">
        <p class="lead"><?= Html::encode(strip_tags($this->title)) ?></p><hr>
        <?= $content?>
      </div>
    </div>
  </div>
  <?php endif?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
