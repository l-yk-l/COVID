<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Theory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Теоретические блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="theory-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот теоретический блок?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label' => 'Автор',
                'attribute' => 'authorName.username',
                'value' => function($data){
                    return $data->author0->username;
                },
            ],
            'text:ntext',
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
        ],
    ]) ?>

</div>
