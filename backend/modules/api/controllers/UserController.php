<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;

/**
 * User controller for the `api` module
 */
class UserController extends ActiveController //estende o activecontroller por isso o CRUD funciona para o modelo Espacos
{
    public $modelClass = 'common\models\User';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            //only=> ['index'], //Apenas para o GET
        ];
        return $behaviors;
    }  
}
