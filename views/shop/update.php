<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel app\models\forms\ShopForm */

$this->title = Yii::t('app', 'Update Shop: {nameAttribute}', [
    'nameAttribute' => $formModel->getModel()->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>

</div>
