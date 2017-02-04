<?php

namespace app\controllers;

use himiklab\yii2\recaptcha\ReCaptcha;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ContactForm;


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

        $numEnd = rand(300, 1000);

        $data = array();

        for ($i = 1; $i <= $numEnd; $i++) {

            $parent_id = rand(1, $i);

            $data[$i] = array('id' => $i, 'parent' => $parent_id, 'name' => 'Ссылка #'.$i.'');
        }


        $dataList = $this->actionMakeTree($data, 1);


        return $this->render('index', [
            'data' => $dataList,

        ]);

    }

    /**
     *
     */
    public function actionGiphy()
    {

        if (Yii::$app->request->isAjax) {


            $data = Yii::$app->request->post();

            $validator = new ReCaptchaValidator();
            $validator->secret = '6LdrThQUAAAAAC6NWqC0JhKRhFRgZRJ9Sa0iblbp';

            if ($validator->validate($data['g-recaptcha-response'], $error)) {

                $giphy = new \rfreebern\Giphy();
                $result = $giphy->random();
                $gif = $result->data->image_original_url;

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['gif' => $gif];



            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => $error];
            }

        }else{
            throw new \yii\web\ForbiddenHttpException('ajaxexpoected');
        }
    }

    /**
     * @param $items
     * @param int $root
     * @return array
     */

    public function actionMakeTree($items, $root = 0)
    {
        // будем строить новый массив-дерево
        $nitems = array();
        foreach ($items as $ki => $item) {
            /* проверяем, относится ли родитель элемента к самому
            верхнему уровню и не ссылается ли на самого себя */
            if ($item['parent'] == $root && $ki != $item['parent']) {
                // удаляем этот элемент из общего массива
                unset($items[$ki]);
                $nitems[$ki] = array(
                    // однако сохраним его в дереве
                    $ki => $item,
                    /* пробежим еще раз, но с уже
                    меньшим числом элементов */
                    'children' => $this->actionMakeTree($items, $ki)
                );
            }
        }
        return $nitems;
    }


    /**
     * Login action.
     *
     * @return string
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
     * @return string
     *
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     *
     *
     *
     * @return string
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
}
