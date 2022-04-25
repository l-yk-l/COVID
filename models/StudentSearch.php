<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Student;

/**
 * StudentSearch represents the model behind the search form of `app\models\Student`.
 */
class StudentSearch extends Student
{
    /**
     * {@inheritdoc}
     */

    public function attributes()
    {
        return array_merge(parent::attributes(), ['groupTitle.title', 'userName.username']);
    }

    public function rules()
    {
        return [
            [['id', 'group_id', 'user_id'], 'integer'],
            [['name', 'surname', 'patronymic', 'groupTitle.title', 'userName.username'], 'safe'],
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
        $query = Student::find();

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
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'patronymic', $this->patronymic])
            ->joinWith(['group' => function($query) { $query->from(['groupTitle' => 'groups']); }])
            ->andFilterWhere(['like', 'groupTitle.title', $this->getAttribute('groupTitle.title')])
            ->joinWith(['user' => function($query) { $query->from(['userName' => 'user']); }])
            ->andFilterWhere(['like', 'userName.username', $this->getAttribute('userName.username')]);
        
            $dataProvider->sort->attributes['groupTitle.title'] = [
                'asc' => ['groupTitle.title' => SORT_ASC],
                'desc' => ['groupTitle.title' => SORT_DESC],
            ];

            $dataProvider->sort->attributes['userName.username'] = [
                'asc' => ['userName.username' => SORT_ASC],
                'desc' => ['userName.username' => SORT_DESC],
            ];

        return $dataProvider;
    }
}
