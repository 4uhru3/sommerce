<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Orders;
use Yii;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        //Устанавливаем язык приложения и пишем его в сессию
        $session = Yii::$app->session;
        $session->open();
        $lang = Yii::$app->request->get('lang');
        if(isset($lang)){
            $session->set('lang',$lang);
        }
        Yii::$app->language = $session->get('lang');

        //Получаем запрос из формы поиска
        $searchParams = Yii::$app->request->get();
        if (isset($searchParams['searchValue'])) {
            $searchParams['Orders'] = [
                $searchParams['searchColumn'] => $searchParams['searchValue']
            ];
        }

        $searchModel = new Orders();

        $dataProvider = $searchModel->search($searchParams);
        $dataProvider->setPagination(['pageSize' => 100]);
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        $orderModel = $dataProvider->getModels();

        $uniqueServices = [];
        /** @var Orders $order */
        foreach ($orderModel as $order) {
            if (isset($uniqueServices[$order->services->id])) {
                $uniqueServices[$order->services->id]++;
            } else {
                $uniqueServices[$order->services->id] = 1;
            }
        }

        $serviceCount = $searchModel->getUniqueServiceCountList();
        $serviceTotal = $searchModel->getCount();
        foreach ($serviceTotal as $key => $value) {
            $serviceTotal = ($value['serviceCount']);
        }

        //Получаем таблицы Статус и Мод из модели
        $status = $searchModel->getStatusTable();
        $mode = $searchModel->getModeTable();

        //Получаем значения фильтров
        $statusID = yii::$app->request->get('statusID');
        $serviceID = yii::$app->request->get('serviceID');
        $modeID = yii::$app->request->get('modeID');

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'status' => $status,
                'statusID' => $statusID,
                'serviceID' => $serviceID,
                'modeID' => $modeID,
                'orderModel' => $orderModel,
                'serviceCount' => $serviceCount,
                'mode' => $mode,
                'serviceTotal' => $serviceTotal,
                'uniqueServices' => $uniqueServices
            ]
        );
    }


}














//
//namespace app\controllers;
//
//use Yii;
//use yii\filters\AccessControl;
//use yii\web\Controller;
//use yii\web\Response;
//use yii\filters\VerbFilter;
//use app\models\LoginForm;
//use app\models\ContactForm;
//use app\models\EntryForm;
//
//class SiteController extends Controller
//{
//    /**
//     * {@inheritdoc}
//     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
//        ];
//    }
//
//    /**
//     * Displays homepage.
//     *
//     * @return string
//     */
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }
//
//    /**
//     * Login action.
//     *
//     * @return Response|string
//     */
//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        }
//
//        $model->password = '';
//        return $this->render('login', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Logout action.
//     *
//     * @return Response
//     */
//    public function actionLogout()
//    {
//        Yii::$app->user->logout();
//
//        return $this->goHome();
//    }
//
//    /**
//     * Displays contact page.
//     *
//     * @return Response|string
//     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Displays about page.
//     *
//     * @return string
//     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
//
//    public function actionSay($message = 'Привет')
//    {
//        return $this->render('say', ['message' => $message]);
//    }
//
//    public function actionEntry()
//    {
//        $model = new EntryForm();
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            // данные в $model удачно проверены
//
//            // делаем что-то полезное с $model ...
//
//            return $this->render('entry-confirm', ['model' => $model]);
//        } else {
//            // либо страница отображается первый раз, либо есть ошибка в данных
//            return $this->render('entry', ['model' => $model]);
//        }
//    }
//}
