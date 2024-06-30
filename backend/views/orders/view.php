<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Đơn hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

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
            
            'amount',
            'name',
            'phone',
            'address',
            'note',
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
            
        ],
    ]) ?>

</div>
