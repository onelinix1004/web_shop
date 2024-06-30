<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(
    	[
    		0 => 'Chưa xử lý',
    		1 => 'Đã xử lý'

    	],
    	['prompt'=>'Chọn trạng thái']



    	) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
