<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Theory;

/**
 * TheorySearch represents the model behind the search form of `app\models\Theory`.
 */
class TheorySearch extends Theory
{
    /**
     * {@inheritdoc}
     */

    public function attributes()
    {
        return array_merge(parent::attributes(), ['authorName.username']);
    }

    public function rules()
    {
        return [
            [['id', 'author'], 'integer'],
            [['title', 'text', 'authorName.username'], 'safe'],
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
        $query = Theory::find();

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
            'id' => $this->id,
            'author' => $this->author,
            //'category' => $this->category,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->joinWith(['author0' => function($query) { $query->from(['authorName' => 'user']); }])
            ->andFilterWhere(['like', 'authorName.username', $this->getAttribute('authorName.username')]);

        $dataProvider->sort->attributes['authorName.username'] = [
            'asc' => ['authorName.username' => SORT_ASC],
            'desc' => ['authorName.username' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
