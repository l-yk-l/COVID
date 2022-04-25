<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\tag\behaviors\TagBehavior;

/**
 * This is the model class for table "theory".
 *
 * @property int $id
 * @property string $title Название теоретического блока
 * @property int $author Автор блока
 * @property string $text Текст блока
 * @property int $category ID категории
 *
 * @property Users $author0
 * @property Categories $category0
 */
class Theory extends \yii\db\ActiveRecord
{
    public function behaviors(){
        return [
            TagBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'theory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author', 'text'], 'required'],
            [['author'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'id']],
            //[['category'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название теоретического блока',
            'author' => 'Автор блока',
            'text' => 'Текст блока',
            //'category' => 'ID категории',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    /**
     * Gets query for [[Category0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory0()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category']);
    }
}
