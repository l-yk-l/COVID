<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\Tag */

$this->title = 'Изменить тег: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->tag_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
