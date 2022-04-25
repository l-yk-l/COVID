<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Theory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="theory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'author')->dropDownList(
        \yii\helpers\ArrayHelper::map($authors, 'id', 'username')
    ) ?> -->

    <!-- <?= $form->field($model, 'author')->textInput() ?> -->

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php
        echo $model->getTagForm($form);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
