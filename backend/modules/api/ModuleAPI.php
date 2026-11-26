<?php

namespace backend\modules\api;

/**
 * api module definition class
 */
class ModuleAPI extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::$app->user->enableSession = false; // 1 - desativa a sessão para a API

        \Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function ($event) { //força o formato JSON
            $event->sender->format = \yii\web\Response::FORMAT_JSON;
        });
    }         
      
}
