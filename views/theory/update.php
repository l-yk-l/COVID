<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Theory */

$this->title = 'Изменить теор. блок: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Теоретические блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="theory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'authors' => $authors,
    ]) ?>

</div>
