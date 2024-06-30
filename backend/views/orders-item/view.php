<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersItem */

$this->title = $model->product->name;
$this->params['breadcrumbs'][] = ['label' => 'Orders Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'orders_id',
                'value'=>function($model){
                    return $model->orders->name;
                }
            ],
            
            [
                'attribute'=>'product_id',
                'value'=>function($model){
                    return $model->product->name;
                }
            ],
            [
                'attribute'=>'price',
                'value'=>function($model){
                    return number_format($model->price).' VNĐ';
                }
            ],
           
            [
                'attribute'=>'status',
                'value'=>function($model){
                    if($model->status==0){
                        return 'Chưa xử lý';
                    }else{
                        return 'Đã xử lý';
                    }
                    
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
