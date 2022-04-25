<?php

namespace app\modules\tag\models;

use Yii;

/**
 * This is the model class for table "tagset".
 *
 * @property int $tagset_id
 * @property int $item_id
 * @property string $class_name
 */
class Tagset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tagset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'class_name'], 'required'],
            [['item_id'], 'integer'],
            [['class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tagset_id' => 'Tagset ID',
            'item_id' => 'Item ID',
            'class_name' => 'Class Name',
        ];
    }
}
