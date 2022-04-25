<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Problems */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="problems-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту задачу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'author',
            'description:ntext',
            'right_solution:ntext',
            'type',
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
            //'tagset' => $model->getTagNames($model->id),
        ],
    ]) ?>

</div>
