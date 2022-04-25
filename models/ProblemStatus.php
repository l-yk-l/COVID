<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "problem_status".
 *
 * @property int $status_id
 * @property int $prob_id
 * @property int $user_id
 * @property int $status
 *
 * @property Problems $prob
 */
class ProblemStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'problem_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prob_id', 'user_id', 'status'], 'required'],
            [['prob_id', 'user_id', 'status'], 'integer'],
            [['prob_id'], 'exist', 'skipOnError' => true, 'targetClass' => Problems::className(), 'targetAttribute' => ['prob_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'prob_id' => 'Prob ID',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Prob]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProb()
    {
        return $this->hasOne(Problems::className(), ['id' => 'prob_id']);
    }
}
