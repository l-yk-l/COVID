<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\studserv\modules\tag\models\TagCategoryAssignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tag Category Assigns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-category-assign-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tag Category Assign', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'assign_id',
            'class_name',
            'category_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
