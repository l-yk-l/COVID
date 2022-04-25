<?php

namespace app\modules\tag\models;

use app\modules\tag\models\TagCategory;
use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $tag_id
 * @property string $title
 * @property int $category_id
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'category_id'], 'required'],
            [['category_id'], 'integer'],
            ['frequency', 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'title' => 'Title',
            'category_id' => 'Category ID',
        ];
    }

    public function getTagCategory(){
        return $this->hasOne(TagCategory::className(), ['category_id' => 'category_id']);
    }
}
