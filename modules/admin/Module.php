<?php

namespace app\modules\admin;

use yii\helpers\Html;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = 'index/index';
    public $layout = 'admin';
    
    public function init()
    {
        parent::init();

        user()->loginUrl = ['admin/index/login'];
        
        Yii::$app->timeZone = Yii::$app->params['mainTimeZone'];
        
        \Yii::$container->set('yii\widgets\LinkPager', [
            'maxButtonCount' => 5,
            'nextPageLabel'  => '&rarr;',
            'prevPageLabel'  => '&larr;',
            'firstPageLabel' => '&lArr;',
            'lastPageLabel'  => '&rArr;',       
        ]);
    }
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $action->controller->cssBundle = 'admin.css';
            $action->controller->jsBundle = 'admin.js';
            
            return $this->checkAccess($action);
        } else {
            return false;
        }
    }
    
    public function checkAccess($action)
    {
        $access = 'ACTION_Admin' . ucfirst($action->controller->id);
        if ($action->controller->id == 'index') {
            return true;
        }
        
        if (!\Yii::$app->user->can('AdminModule') || !\Yii::$app->user->can($access)) {
            return Yii::$app->controller->accessDenied();
        }
        
        return true;
    }
    
    public function defaultGridTemplate($dataProvider, $operations)
    {
        return '
        <div class="panel panel-default">
            <div class="panel-body panel-table">
                <div class="table-responsive">{items}</div>
            </div>
            <div class="panel-footer">{summary}</div>
        </div>
        <div class="pull-left operations">
            ' . Yii::$app->getView()->render('/shared/gridview/operations', ['operations' => $operations]) . '
        </div>  
        <div class="pull-right">
            ' . Yii::$app->getView()->render('/shared/gridview/pagination-sizes', ['dataProvider' => $dataProvider]) . '
        </div>
        <div class="clearfix"></div>
        {pager}
        ';
    }
    
    public function defaultGridButtons($buttons = ['status', 'delete'])
    {
        $defaultButtons = [  
            'status' => function($url, $model) {
                if ($model->status == $model::STATUS_BLOCKED) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-play"></span>',
                        ['activate', 'id' => $model->primaryKey],
                        [
                            'title' => Yii::t('app', 'Enable'),
                            'class' => 'submit btn btn-xs btn-success',
                            'data-pjax' => '0'
                        ]
                    );
                } else {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pause"></span>',
                        ['block', 'id' => $model->primaryKey],
                        [
                            'title' => Yii::t('app', 'Disable'),
                            'class' => 'submit btn btn-xs btn-warning',
                            'data-pjax' => '0'
                        ]
                    );
                }
            },
            
            'delete' => function($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-remove"></span>',
                    ['delete', 'id' => $model->primaryKey],
                    [
                        'title' => Yii::t('app', 'Delete'),
                        'class' => 'confirmation submit btn btn-xs btn-danger',
                        'data-pjax' => '0',
                        'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this record?')
                    ]
                );
            }
        ];
        
        foreach ($defaultButtons as $name => $button) {
            if (!in_array($name, $buttons)) {
                unset($defaultButtons[$name]);
            }
        }
        
        return $defaultButtons;
    }
}
