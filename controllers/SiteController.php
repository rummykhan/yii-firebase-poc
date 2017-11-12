<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\User;
use Kerox\Fcm\Fcm;
use Kerox\Fcm\Message\NotificationBuilder;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionToken()
    {
        $id = Yii::$app->request->post('user_id');
        $token = Yii::$app->request->post('token');

        $user = User::findOne(['id' => $id]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->realtime_token = $token;
        $user->save();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return ['user_id' => $id, 'token' => $token];
    }

    public function actionUsers()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('users', compact('dataProvider'));
    }

    public function actionPush($id)
    {
        $user = User::findOne(['id' => $id]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $fcm = new Fcm('AAAA9c8Bmxk:APA91bFN0SHOxdQ7PBOgTDAgj__LNwrPQzLCm9vL9LFxNUT7Be3uz4Y0as0FrNU-4zjVcSk_ZlphuzIpsRI4ezoIJTN9fYemBxLtV_0BiJqisebbwubh0MqDVGMGsXTY7uoQd2Gb3rno');
        $builder = new NotificationBuilder('YII 2.0');

        $notification = $builder->setIcon('https://avatars3.githubusercontent.com/u/12664104')
            ->setBody('rummykhan was here!!')
            ->setClickAction('<a href="http://www.google.com">Google</a>')
            ->build();

        $fcm->setOptions(['priority' => 'normal'])
            ->setNotification($notification)
            ->sendTo($user->realtime_token);

        return $this->redirect('/site/users');
    }
}
