<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\TagCategory */

$this->title = 'Изменить категорию тегов: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tag-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
