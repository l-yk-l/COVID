<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\studserv\modules\tag\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать тег', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'tag_id',
            'title',
            [
                'label' => 'Категория',
                'attribute' => 'category_id',
                'value' => function($data){
                    //if($data->tagCategory){
                        return $data->tagCategory->title;
                    //}
                    //return '';
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>