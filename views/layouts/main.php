<?php
use yii\helpers\{Html, ArrayHelper};
use yii\bootstrap\{Nav, NavBar};
use app\assets\AppAsset;

$this->title = str_replace('"', '“', $this->title);
$this->title = Html::encode($this->title);
$this->title = $this->title ? $this->title . ' / ' : '';

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
  <title><?= $this->title . Yii::$app->name ?></title>
  <?= Html::csrfMetaTags()?>
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
  <div id="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-default navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Sign in'), 'url' => ['/auth/login']];
        $menuItems[] = ['label' => Yii::t('app', 'Sign up'), 'url' => ['/auth/signup']];
    } else {
        $menuItems[] = [
            'label' => Yii::t('app', 'Logout'),
            'url' => ['/profile/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <div class="container">
      <?= app\widgets\Alert::widget(['template' => '/shared/alert']) ?>
      <?= $content ?>
    </div>
  </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
