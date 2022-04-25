<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'COVID';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Control Of Variations In Databases</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4" style="width:50%; text-align:center">
                <h2>Теоретические блоки</h2>

                <p>Вы можете перейти по кнопке ниже для изучения теоретического материала.</p>

                <?= Html::a('Теоретические блоки', ['/theory/list'], ['class' => 'btn btn-default']) ?>
                <!-- <p><a class="btn btn-default" href="http://localhost/covid/web/theory/list">Теоретические блоки &raquo;</a></p> -->
            </div>
            <div class="col-lg-4" style="width:50%; text-align:center">
                <h2>Задачи</h2>

                <p>Вы можете перейти по кнопке ниже для решения задач.</p>

                <?= Html::a('Задачи', ['/problems/list'], ['class' => 'btn btn-default']) ?>
                <!-- <p><a class="btn btn-default" href="http://localhost/covid/web/problems/list">Задачи &raquo;</a></p> -->
            </div>
        </div>

    </div>
</div>
