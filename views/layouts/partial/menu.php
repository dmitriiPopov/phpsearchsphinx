<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Html;

NavBar::begin([
    'brandLabel' => '',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$items = [];

if (Yii::$app->user->isGuest) {
    $items[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
} else {
    $items[] = ['label' => Yii::t('app', 'Search url'), 'url' => ['/site/search', 'q' => 'тюнер']];
    $items[] = ['label' => Yii::t('app', 'Shops'), 'url' => ['/site/index']];
    $items[] = (
        Html::beginTag('li')
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                Yii::t('app', 'Logout ({username})', ['username' => Yii::$app->user->identity->username]),
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
        . Html::endTag('li')
    );
}



    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'   => $items,
    ]);

NavBar::end();
