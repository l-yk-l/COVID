<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use app\modules\tag\behaviors\TagBehavior;
use app\models\Solves;

/**
 * This is the model class for table "problems".
 *
 * @property int $id
 * @property string $title Название задачи
 * @property int $author Автор задачи
 * @property string $description Описание задачи
 * @property string $right_solution Эталонный запрос
 * @property int $type Тип задачи
 * @property int $difficulty Сложность задачи
 *
 * @property DmlProblemDiff[] $dmlProblemDiffs
 * @property ProblemStatus[] $problemStatuses
 * @property SqlProblemDb[] $sqlProblemDbs
 */
class Problems extends \yii\db\ActiveRecord
{
//     public $tagBahavior;
//     public function __construct(){
//         $this->tagBehavior = $this->getBehavior('tagBehavior');
//     }

    public $user_solve;

    public function behaviors(){
        return [
            TagBehavior::className(),
        ];
    }


    public $filePath;
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'problems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author', 'description', 'right_solution', 'type', 'difficulty'], 'required'],
            //[['filePath'], 'required'],
            [['author', 'type', 'difficulty'], 'integer'],
            [['description', 'right_solution', 'user_solve'], 'string'],
            //[['filePath'], 'string'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название задачи',
            'author' => 'Автор задачи',
            'description' => 'Описание задачи',
            'right_solution' => 'Эталонный запрос',
            'type' => 'Тип задачи',
            'difficulty' => 'Сложность задачи',
        ];
    }

    /**
     * Gets query for [[DmlProblemDiffs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDmlProblemDiffs()
    {
        return $this->hasMany(DmlProblemDiff::className(), ['prob_id' => 'id']);
    }

    public function getProblemType()
    {
        return $this->hasOne(ProblemType::className(), ['id' => 'type']);
    }

    /**
     * Gets query for [[ProblemStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProblemStatuses()
    {
        return $this->hasMany(ProblemStatus::className(), ['prob_id' => 'id']);
    }

    /**
     * Gets query for [[SqlProblemDbs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSqlProblemDbs()
    {
        return $this->hasMany(SqlProblemDb::className(), ['prob_id' => 'id']);
    }

    //Возвращает статус задачи для текущего пользователя
    //  1 - решено
    //  0 - пытался решить, но не решил
    // -1 - не пытался решать
    public function getStatus($user_id)
    {
        // $result = $this->hasOne(ProblemStatus::className(), ['id' => 'prob_id'])->where('user_id' => $user_id);
        $prob_stat = ProblemStatus::find()->where(['AND', ['user_id' => $user_id, 'prob_id' => $this->id]])->one();
        if(!isset($prob_stat)){
            return -1;
        }
        $res = $prob_stat->status;
        return $res;
    }

    public function setStatus($new_status) // $new_status либо 0, либо 1 (не решил / решил)
    {
        $user_id = Yii::$app->user->identity->id;
        $status = $this->getStatus($user_id);
        
        if($status == -1) {
            $model = new ProblemStatus();
            $model->user_id = $user_id;
            $model->prob_id = $this->id;
            $model->status = $new_status;
            $model->save();
        } elseif($status == 0 && $new_status == 1) {
            $model = ProblemStatus::find()->where(['AND', ['user_id' => $user_id, 'prob_id' => $this->id]])->one();
            $model->status = $new_status;
            $model->save();
        }
    }

    # Записывает решение пользователя в БД
    # user_id, problem_id, usr_solve, solve_msg, solve_status
    public function writeSolve($user_solve, $solve_msg, $solve_status){
        $model = new Solves();

        $model->user_id = Yii::$app->user->identity->id;
        $model->prob_id = $this->id;
        $model->user_solve = $user_solve;
        $model->solve_message = $solve_msg;
        $model->solve_status = $solve_status;
        
        $model->save();
    }

    public function getSolves($user_id){
        $solves = new Query();
        $solves = $solves->select([
            Solves::tableName().'.user_solve',
            Solves::tableName().'.solve_message',
            Solves::tableName().'.solve_status',
        ])
        ->from(Solves::tableName())
        ->where([Solves::tableName().'.prob_id' => $this->id, Solves::tableName().'.user_id' => $user_id])
        ->all();

        return $solves;
    }
}
