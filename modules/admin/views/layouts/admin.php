<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\assets\AdminAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(strip_tags($this->title)) ?> / <?= Yii::t('app', 'Control Panel') ?></title>
    <?= Html::csrfMetaTags() ?>
    <meta name="description" content="<?= e(Yii::$app->controller->description) ?>">
    <meta name="keywords" content="<?= e(Yii::$app->controller->keywords) ?>">
    <?php $this->head() ?>
    
    <link href="<?= Yii::$app->controller->getCssBundle() ?>" rel="stylesheet">
    
</head>
<body>
<?php $this->beginBody() ?>

    <?php if(Yii::$app->controller->action->id == 'login'):?>
    <div class="container">
        <?= $content?>
    </div>
    <?php else:?>
      
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
                <p class="lead"><?= e(strip_tags($this->title)) ?></p><hr>
                <?= $content?>
            </div>
          
        </div>
    </div>
      
    <?php endif?>
      
    <script src="<?= Yii::$app->controller->getJsBundle() ?>"></script>
      
<?php $this->endBody() ?>
   
</body>
</html>
<?php $this->endPage() ?>