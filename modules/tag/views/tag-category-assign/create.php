<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\TagCategoryAssign */

$this->title = 'Create Tag Category Assign';
$this->params['breadcrumbs'][] = ['label' => 'Tag Category Assigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-category-assign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
