<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "problem_type".
 *
 * @property int $id
 * @property string $title Название типа задачи
 */
class ProblemType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'problem_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название типа задач',
        ];
    }
}
