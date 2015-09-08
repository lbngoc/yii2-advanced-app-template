<?php
namespace api\versions\v1\controllers;

use api\models\LoginForm;
use common\models\RegisterForm;
use yii\rest\Controller;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // $behaviors['authenticator'] = [
        //     'class' => \yii\filters\auth\QueryParamAuth::className(),
        // ];
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'login'  => ['post'],
                // 'view'   => ['get'],
                // 'create' => ['get', 'post'],
                // 'update' => ['get', 'put', 'post'],
                // 'delete' => ['post', 'delete'],
            ],
        ];
        return $behaviors;
    }

    /**
     * This method implemented to demonstrate the receipt of the token.
     * Do not use it on production systems.
     * @return string AuthKey or model with errors
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return [
                'success' => true,
                'data' => $model->getUser()
            ];
        } else {
            return [
                'success' => false,
                'data' => $model->errors
            ];
        }
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->register()) {
            return $model->getUser();
        } else {
            return $model;
        }
    }
}
