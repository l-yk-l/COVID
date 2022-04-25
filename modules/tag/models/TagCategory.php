<?php

namespace app\modules\tag\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use app\modules\tag\behaviors\SlugSpecialCharsBehavior;

/**
 * This is the model class for table "tag_category".
 *
 * @property int $category_id
 * @property string $title
 */
class TagCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['visible', 'visible_user'], 'safe'],
        ];
    }

    public function behaviors(){
        return [
            [
                'class' => SlugSpecialCharsBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                //'delimiter' => '__',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'visible' => 'Видимость для всех', 
            'visible_user' => 'Видимость для пользователя',
        ];
    }

    public static function getDict(){
        $arr = self::find()->all();
        $res = array();
        if($arr){
            foreach ($arr as $key => $value) {
                $res[$value->category_id] = $value->title;
            }
        }
        return $res;
    }
}
