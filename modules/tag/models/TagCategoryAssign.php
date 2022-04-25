<?php

namespace app\modules\tag\models;

use Yii;

/**
 * This is the model class for table "tag_category_assign".
 *
 * @property int $assign_id
 * @property string $class_name
 * @property int $category_id
 *
 * @property TagCategory $category
 */
class TagCategoryAssign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag_category_assign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_name', 'category_id'], 'required'],
            [['category_id'], 'integer'],
            [['class_name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => TagCategory::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'assign_id' => 'Assign ID',
            'class_name' => 'Class Name',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TagCategory::className(), ['category_id' => 'category_id']);
    }
}
