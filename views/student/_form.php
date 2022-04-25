<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'group_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($groups, 'id', 'title')
    ) ?>
    <!-- <?= $form->field($model, 'group_id')->textInput() ?> -->

    <?= $form->field($model, 'user_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($users, 'id', 'username')
    ) ?>
    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
