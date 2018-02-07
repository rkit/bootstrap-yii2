<?php

namespace app\modules\auth\controllers;

use Yii;
use app\modules\auth\services\SocialAuth;
use app\modules\auth\models\forms\SignupProviderForm;

class SocialController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'signup',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => '/auth/social/signup'
            ],
        ];
    }

    public function successCallback($client)
    {
        Yii::$app->session['authClient'] = $client;
    }

    public function redirect($url, $statusCode = 302)
    {
        Yii::$app->session['authClient'] = null;
        return parent::redirect($url, $statusCode);
    }

    private function parsers()
    {
        return [
            'facebook' => '\app\modules\auth\oauth\parsers\Facebook',
        ];
    }

    public function actionSignup()
    {
        if (Yii::$app->session['authClient'] === null) {
            return $this->redirect(['/']);
        }

        $client = Yii::$app->session['authClient'];

        $parserClass = $this->parsers()[$client->id];
        $parser = new $parserClass($client);

        $socialAuth = Yii::$container->get(SocialAuth::class, [$parser, $client]);
        $user = $socialAuth->getUser();
        $model = Yii::$container->get(SignupProviderForm::class, [$user]);

        if (!$user->isNewRecord) {
            if ($user->isActive()) {
                $model->login();
            } else {
                Yii::$app->session->setFlash('error', $user->getStatusDescription());
            }
            return $this->redirect(['/']);
        }

        if ($model->validate() || ($model->load(Yii::$app->request->post()) && $model->validate())) {
            $model->signup();
            $model->sendEmail();

            Yii::$app->session->setFlash(
                'success',
                Yii::t('app', 'Please activate your account') . '. ' .
                Yii::t('app', 'A letter for activation was sent to {email}', ['email' => $model->email])
            );
            return $this->redirect(['/']);
        }

        return $this->render('signup', [
            'model' => $model
        ]);
    }
}
