<?php

namespace app\controllers;

use Yii;
use app\models\Problems;
use app\models\ProblemsSearch;
use app\models\ProblemType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * ProblemsController implements the CRUD actions for Problems model.
 */
class ProblemsController extends Controller
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
     * Lists all Problems models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!(Yii::$app->user->isGuest)){
            if((Yii::$app->user->identity->role == 'admin') || (Yii::$app->user->identity->role == 'teacher')){
                $searchModel = new ProblemsSearch();
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
        $searchModel = new ProblemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProblemPage($id)
    {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            $user_query = str_replace("\n", " ", $model->user_solve);
            $user_id = Yii::$app->user->identity->getID();
            $problem_id = $model->id;
            $problem_type = $model->problemType->title;
            
            if($problem_type == "SQL"){
                $right_solution = str_replace("\n", " ", $model->right_solution);
                $command = escapeshellcmd('python C:\xampp\htdocs\covid\\checkers\check_SQL\SQL.py');
                $output = shell_exec($command . ' "'. $user_query .'" "' . $problem_id . '" "' . $user_id . '" "' . $right_solution . '"');
            }
            elseif($problem_type == "DML"){
                $command = escapeshellcmd('python C:\xampp\htdocs\covid\\checkers\check_DML\DML.py');
                $output = shell_exec($command . ' "'. $user_query .'" "' . $problem_id . '" "' . $user_id . '"');
            }

            $output = str_replace("\n", "", $output);
            
            if($output == "0"){
                Yii::$app->session->setFlash('valid_solution', "Полное решение");
                $model->setstatus(1);
                $model->writeSolve($user_query, "Полное решение", 1);
            }
            elseif(is_numeric($output)){
                Yii::$app->session->setFlash('invalid_solution', "Неправильное решение на тесте №" . $output);
                $model->setstatus(0);
                $model->writeSolve($user_query, "Неправильное решение на тесте №" . $output, 0);
            }
            else{
                Yii::$app->session->setFlash('invalid_solution', $output);
                $model->setstatus(0);
                $model->writeSolve($user_query, $output, 0);
            }
        }

        if(!Yii::$app->user->isGuest){
            $user_id = Yii::$app->user->identity->getID();
            $solves = $model->getSolves($user_id);
            return $this->render('problem-page', [
                'model' => $model,
                'solves' => $solves,
            ]);
        }

        return $this->render('problem-page', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Problems model.
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
     * Creates a new Problems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // Пока что кок
    // public function actionCreate()
    // {
    //     $model = new Problems();
    //     $problemType = ProblemType::find()->all();
        
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    
    //     return $this->render('create', [
    //         'model' => $model,
    //         'problemType' => $problemType,
    //     ]);
    // }

    /**
     * Updates an existing Problems model.
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
                $problemType = ProblemType::find()->all();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                return $this->render('update', [
                    'model' => $model,
                    'problemType' => $problemType,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    /**
     * Deletes an existing Problems model.
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
     * Finds the Problems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Problems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Problems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
