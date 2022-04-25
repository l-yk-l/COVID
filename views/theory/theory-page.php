<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TheorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Theories', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theory-list">

    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <hr>

    <?= Html::tag('p', Html::encode('Автор блока: ' . $model->author0->username), ['class' => 'authorName', 'style' => ['font-style' => 'italic', 'text-align' => 'center']]) ?>

    <hr>

    <?php
        foreach($model->tagCategories as $cat_id => $cat){
            $retval = "";
            foreach($model->tags as $tag) {
                if($tag->category_id == $cat_id){
                    $retval .= $tag->title . ", ";
                }
            }
            $retval = substr($retval, 0, -2);
            echo Html::tag('p', Html::encode($model->getCatTitle($cat_id) . ": " . $retval . '.'), ['class' => 'theoryTags', 'style' => ['font-style' => 'italic', 'text-align' => 'right']]);
        }
    ?>

    <hr>

    <?= Html::tag('p', Html::encode($model->text), ['class' => 'theoryText', 'style' => ['font-size' => '13pt', 'text-align' => 'justify']]) ?>

</div>
