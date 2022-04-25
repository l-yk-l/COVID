<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solves".
 *
 * @property int $solve_id
 * @property int $user_id Пользователь
 * @property int $prob_id Задача
 * @property string $user_solve Решение
 * @property string $solve_message Сообщение о решении
 * @property int $solve_status Статус решения
 */
class Solves extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solves';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'prob_id', 'user_solve', 'solve_message', 'solve_status'], 'required'],
            [['user_id', 'prob_id', 'solve_status'], 'integer'],
            [['user_solve', 'solve_message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'solve_id' => 'Solve ID',
            'user_id' => 'Пользователь',
            'prob_id' => 'Задача',
            'user_solve' => 'Решение',
            'solve_message' => 'Сообщение о решении',
            'solve_status' => 'Статус решения',
        ];
    }
}
