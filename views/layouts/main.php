<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\cmenu\ContextMenu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'COVID',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => array_filter([
            ['label' => 'Теория', 'url' => ['/theory/list']],
            ['label' => 'Задачи', 'url' => ['/problems/list']],
            //['label' => 'Contact', 'url' => ['/site/contact']],

            Yii::$app->user->isGuest || Yii::$app->user->identity->role == 'student' ? false : ([
                'label' => 'Редактирование',
                'items' => array_filter([
                    Yii::$app->user->identity->role == 'teacher' ? ['label' => 'Теор. блоков', 'url' => '/covid/web/theory'] : false,
                    Yii::$app->user->identity->role == 'teacher' ? ['label' => 'Задач', 'url' => '/covid/web/problems'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Теор. блоков', 'url' => '/covid/web/theory'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Задач', 'url' => '/covid/web/problems'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Групп', 'url' => '/covid/web/groups'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Типов задач', 'url' => '/covid/web/problem-type'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Студентов', 'url' => '/covid/web/student'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Пользователей', 'url' => '/covid/web/user'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Тегов', 'url' => '/covid/web/tag/tag'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Категорий тегов', 'url' => '/covid/web/tag/tag-category'] : false,
                    Yii::$app->user->identity->role == 'admin' ? ['label' => 'Подключений тегов', 'url' => '/covid/web/tag/tag-category-assign'] : false,
                ])
            ]),

            Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/auth']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ]),
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => Yii::$app->homeUrl],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
