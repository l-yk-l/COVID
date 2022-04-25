<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProblemType */

$this->title = 'Создать тип задач';
$this->params['breadcrumbs'][] = ['label' => 'Типы задач', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
