<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use backend\modules\api\components\CustomAuth;


class LocalController extends ActiveController
{
    public $modelClass = 'common\models\Local';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'delete') {
            if (Yii::$app->params['id'] != 1) {
                throw new \yii\web\ForbiddenHttpException('Proibido');
            }
        }
    }

}

