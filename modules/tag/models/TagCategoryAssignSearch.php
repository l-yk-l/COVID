<?php

namespace app\modules\tag\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tag\models\TagCategoryAssign;

/**
 * TagCategoryAssignSearch represents the model behind the search form of `backend\modules\studserv\modules\tag\models\TagCategoryAssign`.
 */
class TagCategoryAssignSearch extends TagCategoryAssign
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assign_id', 'category_id'], 'integer'],
            [['class_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TagCategoryAssign::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'assign_id' => $this->assign_id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'class_name', $this->class_name]);

        return $dataProvider;
    }
}
