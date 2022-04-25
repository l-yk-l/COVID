<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Problems;

/**
 * ProblemsSearch represents the model behind the search form of `app\models\Problems`.
 */
class ProblemsSearch extends Problems
{
    /**
     * {@inheritdoc}
     */

    public function attributes()
    {
        return array_merge(parent::attributes(), ['problemType']);
    }

    public function rules()
    {
        return [
            [['id', 'author', 'type', 'difficulty'], 'integer'],
            [['title', 'description', 'right_solution', 'problemType', 'filePath'], 'safe'],
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
        $query = Problems::find();

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
            //'type' => $this->type,
            'difficulty' => $this->difficulty,
        ]);

        $query->andFilterWhere(['like', 'problems.title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'right_solution', $this->right_solution])
            ->joinWith(['problemType' => function($query) { $query->from(['type' => 'problem_type']); }])
            ->andFilterWhere(['like', 'type.title', $this->getAttribute('problemType')]);

        $dataProvider->sort->attributes['problemType'] = [
            'asc' => ['type.title' => SORT_ASC],
            'desc' => ['type.title' => SORT_DESC],
        ];
        
        return $dataProvider;
    }
}
