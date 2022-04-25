<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\TagCategory */

$this->title = 'Создать категорию тегов';
$this->params['breadcrumbs'][] = ['label' => 'Категории тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
