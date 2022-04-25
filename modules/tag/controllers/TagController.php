<?php

namespace app\modules\tag\controllers;

use Yii;
use app\modules\tag\models\Tag;
use app\modules\tag\models\TagSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller
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
     * Lists all Tag models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!(Yii::$app->user->isGuest)){
            if(Yii::$app->user->identity->role == 'admin'){
                $searchModel = new TagSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Displays a single Tag model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if(Yii::$app->user->identity->role == 'admin'){
                return $this->render('view', [
                    'model' => $this->findModel($id),
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!(Yii::$app->user->isGuest)){
            if(Yii::$app->user->identity->role == 'admin'){
                $model = new Tag();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->tag_id]);
                }

                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if(Yii::$app->user->identity->role == 'admin'){
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->tag_id]);
                }

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!(Yii::$app->user->isGuest)){
            if(Yii::$app->user->identity->role == 'admin'){
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
