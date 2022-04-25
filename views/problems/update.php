<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Problems */

$this->title = 'Изменить задачу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="problems-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'problemType' => $problemType,
    ]) ?>

</div>
