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
    $items[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $items[] = ['label' => 'Search url', 'url' => ['/site/search', 'q' => 'принтер']];
    $items[] = ['label' => 'Shops', 'url' => ['/site/index']];
    $items[] = (
        '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>'
    );
}



    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'   => $items,
    ]);

NavBar::end();
