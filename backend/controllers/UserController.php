<?php

namespace maddoger\user\backend\controllers;

use maddoger\core\models\MultiModel;
use maddoger\user\common\models\search\UserSearch;
use maddoger\user\common\models\User;
use maddoger\user\common\models\UserProfile;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'roles' => ['user.user.view'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create'],
                        'roles' => ['user.user.create'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update'],
                        'roles' => ['user.user.update'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['user.user.delete'],
                    ],
                    [
                        'actions' => ['profile'],
                        'roles' => ['user.user.profile'],
                        'allow' => true,
                    ],
                    //For superuser
                    [
                        'allow' => true,
                        'roles' => ['superuser'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MultiModel([
            'models' => [
                'user' => new User(),
                'profile' => new UserProfile(),
            ],
        ]);
        $model->getModel('user')->setScenario('backend');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->getModel('user')->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $model = new MultiModel([
            'models' => [
                'user' => $user,
                'profile' => $user->profile,
            ],
        ]);
        $model->getModel('user')->setScenario('backend');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            switch (Yii::$app->request->post('redirect')) {
                case 'exit':
                    return $this->redirect(['index']);
                case 'new':
                    return $this->redirect(['create']);
                default:
                    return $this->redirect(['view', 'id' => $model->getModel('user')->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
            ]);
        }
    }

    /**
     * Updates own profile.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProfile()
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->getIdentity();
        $model = new MultiModel([
            'models' => [
                'user' => $user,
                'profile' => $user->profile,
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('maddoger/user', 'Changes have been saved.'));
            return $this->refresh();
        } else {
            return $this->render('profile', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status' => User::STATUS_DELETED]);

        return $this->redirect(['index']);
    }
}
