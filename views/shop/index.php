<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shops');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


        <?php if (Yii::$app->getSession()->has('created')) : ?>
            <div class="alert alert-success">
                <?= Yii::$app->getSession()->getFlash('created')?>
            </div>
        <?php endif;?>


    <p>
        <?= Html::a(Yii::t('app', 'Add Shop'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'filename_real',
                'format' => 'raw',
                'value' => function (\app\models\Shop $data) {
                    return Html::a($data->filename_real, $data->getFile(), ['target' => '_blank']);
                },
                'filter' => false,
            ],
            //'file_csv_column_separator',
            [
                'attribute' => 'status',
                'value' => function (\app\models\Shop $data) {
                    return $data->getStatusLabel();
                },
                'filter'    => \app\models\Shop::getStatusesLabels(),
            ],
            [
                'attribute' => 'created_at',
                'filter'    => false,
            ],
            [
                'attribute' => 'updated_at',
                'filter'    => false,
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'header' => Html::a('Reset', ['shop/index'], [
                    'class' => ['btn btn-reset btn-default'],
                ]),
                'template' => "{update}\t{delete}",
            ],
        ],
    ]); ?>
</div>
