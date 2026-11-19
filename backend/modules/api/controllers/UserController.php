<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

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
}
