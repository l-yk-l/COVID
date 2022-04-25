<?php

namespace app\modules\auth;

/**
 * auth module definition class
 */
class Auth extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\auth\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //$this->layout = '@backend/modules/studserv/views/layouts/main';
        // custom initialization code goes here
    }
}
