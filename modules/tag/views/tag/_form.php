<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\tag\models\TagCategory;

/* @var $this yii\web\View */
/* @var $model backend\modules\studserv\modules\tag\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(TagCategory::getDict()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
