<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\Cors;

class MainController extends \yii\rest\Controller
{
    public function beforeAction($action): bool
    {
        $lang = $this->request->headers->get('Accept-Language');
        if($lang == 'ar')
            Yii::$app->language = $lang;

        return parent::beforeAction($action);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => [
                    'http://localhost:3000',
                    'http://localhost:3000/',
                ],
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['*'],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpHeaderAuth::class,
            'except' => ['login']
        ];
        return $behaviors;
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ]
        ];
    }
}