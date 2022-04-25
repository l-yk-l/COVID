<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProblemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-page">
    <?php if( Yii::$app->session->hasFlash('valid_solution') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo Yii::$app->session->getFlash('valid_solution'); ?>
        </div>
    <?php endif;?>

    <?php if( Yii::$app->session->hasFlash('invalid_solution') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo Yii::$app->session->getFlash('invalid_solution'); ?>
        </div>
    <?php endif;?>

    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <hr>

    <?= Html::tag('p', Html::encode('Тип задачи: ' . $model->problemType->title), ['class' => 'problemType', 'style' => ['font-style' => 'italic', 'text-align' => 'center']]) ?>
    <?= Html::tag('p', Html::encode('Сложность задачи: ' . $model->difficulty), ['class' => 'difficulty', 'style' => ['font-style' => 'italic', 'text-align' => 'center']]) ?>

    <hr>

    <?= Html::tag('p', Html::encode($model->description), ['class' => 'description', 'style' => ['font-size' => '13pt', 'text-align' => 'justify']]) ?>

    <hr>

    <?php
        if(Yii::$app->user->isGuest){
            echo Html::tag('p', Html::encode('Выполните вход в систему чтобы отправить свое решение'),
            ['class' => 'messege', 'style' => ['font-style' => 'italic', 'text-align' => 'center', 'color' => '#aaa']]);
        }
        else{
            $form = ActiveForm::begin();
            echo $form->field($model, 'user_solve')->textarea(['rows' => 10, 'style' => 'resize:none;', 'placeholder' => 'Введите решение'])->label('Поле для отправки решения:');
            echo Html::submitButton('Отправить решение', ['class' => 'btn btn-success']);
            $form = ActiveForm::end();
        }
    ?>

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
            echo Html::tag('p', Html::encode($model->getCatTitle($cat_id) . ": " . $retval . '.'), ['class' => 'problemType', 'style' => ['font-style' => 'italic', 'text-align' => 'right']]);
        }
    ?>

    <?php
        if(!Yii::$app->user->isGuest){
            echo '<hr>';
            echo '<b>';
            echo '<i>';
            echo Html::tag('p', Html::encode('Мои попытки:'), ['style' => ['font-size' => '13pt',]]);
            echo '</i>';
            echo '</b>';
            if(sizeof($solves) == 0){
                echo '<p><i>У вас пока нет попыток</i></p>';
            }
            else{
                echo '<div class="justify-content-around">';
                echo '<table class="table table-bordered table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th class="text-center">#</th>';
                echo '<th class="text-center">Ваш запрос</th>';
                echo '<th class="text-center">Результат</th>';
                echo '</tr>';
                echo '</thead>';
                $counter = 1;
                foreach($solves as $solve){
                    echo '<tr>';
                    echo '<td align=center>';
                    echo '<b>';
                    echo $counter;
                    echo '</b>';
                    echo '</td>';
                    echo '<td align=center>';
                    echo $solve['user_solve'];
                    echo '</td>';
                    echo '<td align=center>';
                    if($solve['solve_status'] == 1){
                        echo '<span style="color:#4cae4c">';
                    }
                    else{
                        echo '<span style="color:#d9534f">';
                    }
                    echo $solve['solve_message'];
                    echo '</span>';
                    echo '</td>';
                    echo '</tr>';
                    $counter += 1;
                    //echo $solve['user_solve'] . ' ' . $solve['solve_message'] . ' ' . $solve['solve_status'] . '<br>';
                }
                echo '</table>';
                echo '</div>';
            }
        }
    ?>

    

</div>
