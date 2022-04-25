<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\tag\models\Tag;

/* @var $this yii\web\View */
/* @var $model app\models\Problems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="problems-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'filePath')->textInput() ?> -->

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'right_solution')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList(
        \yii\helpers\ArrayHelper::map($problemType, 'id', 'title')
    ) ?> 

    <?= $form->field($model, 'difficulty')->textInput() ?>

    <?php
        echo $model->getTagForm($form);
    ?>


    <!-- <?= Html::a('Import', ['problems/index', 'filePath' => $model->filePath], ['class' => 'btn btn-success']) ?> -->

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
