<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProblemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problems-index">

    <h1><?= Html::encode($this->title) ?></h1>

        <!-- <p>
            <?= Html::a('Create Problems', ['create'], ['class' => 'btn btn-success']) ?>
        </p> -->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'title',
            // [
            //     'value' => function($data){
            //         return Html::a($model->title, Url::to()['problems', 'id' ]);
            //     }
            // ]
            'description:ntext',
            [
                'label' => 'Тип задачи',
                'attribute' => 'problemType',
                'value' => function($data){
                    return $data->problemType->title;
                },
            ],
            //'author',
            'right_solution:ntext',
            'difficulty',
            [
                'label' => 'Тэги задачи',
                'value' => function($data){
                    $retval = "";
                    foreach($data->tags as $tag) {
                        $retval .= $tag->title . ", ";
                    }
                    return substr($retval, 0, -2);
                }
            ],

            //'filePath',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
