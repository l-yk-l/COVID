<?php

namespace app\modules\auth\controllers;

use yii\web\Controller;
use app\modules\auth\models\Auth;
use app\modules\auth\models\LoginForm;
use app\modules\auth\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use app\modules\auth\models\PasswordResetRequestForm;
use app\modules\auth\models\ResetPasswordForm;
use app\modules\auth\models\SignupForm;
/**
 * Default controller for the `auth` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'sign-up'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'account'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAuth(){
    	
    }

    public function actionLogout(){
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAccount(){
        $user = Yii::$app->user->identity;
        if(Yii::$app->request->post()){
            if($user->password_hash !== Yii::$app->request->post()['User']['password']){
                $user->setPassword(Yii::$app->request->post()['User']['password']);
                $user->save();
            }
        }
        return $this->render('account', [
            'model' => $user,
        ]);
    }

    public function actionSignup(){
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset(){
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token){
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function onAuthSuccess($client){
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'username' => $attributes['login'],
                        'email' => $attributes['email'],
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $user->generateUsername();
                    $default_email = 'null@null.null';
                    switch ($client->getTitle()) {
                    	case 'Google':
                            if(isset($attributes['emails'])){
                                if(isset($attributes['emails'][0])){
                                    if(isset($attributes['emails'][0]['value'])){
                                        $user->email = $attributes['emails'][0]['value'];            
                                    }
                                    else{
                                        $user->email = $default_email;            
                                    }
                                }
                                else{
                                    $user->email = $default_email;
                                }
                            }
                            else{
                                $user->email = $default_email;
                            }
                    		break;
                    	
                    	case 'VKontakte':
                            if(isset($attributes['email'])){
                                $user->email = $attributes['email'];
                            }
                            else{
                                $user->email = $default_email;
                            }
                    		break;

                    	case 'Yandex':
                            if(isset($attributes['default_email'])){
                                $user->email = $attributes['default_email'];
                            }
                            else{
                                $user->email = $default_email;
                            }
                    		break;

                    	case 'Facebook':
                            if(isset($attributes['email'])){
                                $user->email = $attributes['email'];
                            }
                            else{
                                $user->email = $default_email;
                            }
                    		break;

                    	default:
                    		$user->email = $default_email;
                    		break;
                    }
                    //print_r($attributes); echo '<br>'; echo $client->getTitle(); exit(0);
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }
}
