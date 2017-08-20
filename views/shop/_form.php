<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $formModel app\models\forms\ShopForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">

        <div class="col-md-4">
            <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">

            <div class="col-md-12">
                <?= $form->field($formModel, 'file')
                    ->label(Yii::t('app', 'Csv file'))
                    ->fileInput() ?>
            </div>

            <div class="col-md-12">
                <?php if ($formModel->model->filename) : ?>
                    <?=Yii::t('app', 'Current file')?>:
                    <?= Html::a($formModel->model->filename_real, $formModel->model->getFile(), ['target' => '_blank'])?>
                <?php endif;?>
            </div>

        </div>

        <div class="col-md-2">
            <?= $form->field($formModel, 'file_csv_column_separator')
                ->label($formModel->model->getAttributeLabel('file_csv_column_separator'))
                ->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($formModel, 'status')->dropDownList($formModel->getStatusList()) ?>
        </div>

    </div>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['shop/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
