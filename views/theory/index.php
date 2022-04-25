<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TheorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Теоретические блоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theory-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать теор. блок', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            [
                'label' => 'Автор',
                'attribute' => 'authorName.username',
                'value' => function($data){
                    return $data->author0->username;
                },
            ],
            //'text:ntext' ,
            [
                'label' => 'Текст',
                'attribute' => 'text',
                'value' => function($data){
                    return substr($data->text, 0, 200) . '...';
                }
            ],
            [
                'label' => 'Тэги',
                'value' => function($data){
                    $retval = "";
                    foreach($data->tags as $tag) {
                        $retval .= $tag->title . ", ";
                    }
                    return substr($retval, 0, -2);
                }
            ],
            //'category',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
