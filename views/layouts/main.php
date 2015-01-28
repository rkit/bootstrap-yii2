<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\FrontAsset;

FrontAsset::register($this);
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
    <meta name="description" content="<?= Html::encode(Yii::$app->controller->description) ?>" />
    <meta name="keywords" content="<?= Html::encode(Yii::$app->controller->keywords) ?>" />
    <?php $this->head() ?>
    
    <link href="<?= Yii::$app->controller->getCssBundle() ?>" rel="stylesheet">
    
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
                $menuItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/signup']];
                $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/login']];
            } else {
                $menuItems[] = [
                    'label' => Yii::t('app', 'Logout'),
                    'url' => ['/logout'],
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
            <?= app\widgets\Alert::widget() ?>
            <?= $content ?>
        </div>
        
    </div>

    <script src="<?= Yii::$app->controller->getJsBundle() ?>"></script>
         
 <?php $this->endBody() ?>                         
</body>
</html>
<?php $this->endPage() ?>