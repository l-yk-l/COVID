<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\TagCategoryAssign */

$this->title = 'Update Tag Category Assign: ' . $model->assign_id;
$this->params['breadcrumbs'][] = ['label' => 'Tag Category Assigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->assign_id, 'url' => ['view', 'id' => $model->assign_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tag-category-assign-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
