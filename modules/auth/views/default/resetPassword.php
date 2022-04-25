<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сбросить пароль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-2">
</div>
<div class="col-lg-8">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>Шэнэ нюуса үгөө оруулна гүт:</p> -->

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Сбросить пароль', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="col-lg-2">
</div>