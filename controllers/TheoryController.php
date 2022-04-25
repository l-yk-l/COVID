<?php

namespace app\controllers;

use Yii;
use app\models\Theory;
use app\models\TheorySearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * TheoryController implements the CRUD actions for Theory model.
 */
class TheoryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Theory models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                $searchModel = new TheorySearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    public function actionList()
    {
        $searchModel = new TheorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTheoryPage($id)
    {
        $model = $this->findModel($id);
        return $this->render('theory-page', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Theory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                return $this->render('view', [
                    'model' => $this->findModel($id),
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Creates a new Theory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                $model = new Theory();
                $authors = User::find()->all();

                $model->author = Yii::$app->user->identity->getID();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                return $this->render('create', [
                    'model' => $model,
                    'authors' => $authors,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Updates an existing Theory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                $model = $this->findModel($id);
                $authors = User::find()->all();

                $model->author = Yii::$app->user->identity->getID();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                return $this->render('update', [
                    'model' => $model,
                    'authors' => $authors,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Deletes an existing Theory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Finds the Theory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Theory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Theory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
