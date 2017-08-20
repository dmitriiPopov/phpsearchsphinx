<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $formModel app\models\forms\ShopForm */

$this->title = Yii::t('app', 'Add Shop');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>

</div>
