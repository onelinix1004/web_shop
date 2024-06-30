<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'attribute'=>'orders_id',
            'value' => 'orders.name',
            'headerOptions'=>['style'=>'text-align:center;width:150px'],
            ],
            [
            'attribute'=>'product_id',
            'value' => 'product.name',
            'headerOptions'=>['style'=>'text-align:center;width:150px'],
            ],
            [
            'attribute'=>'quantity',
            'headerOptions'=>['style'=>'text-align:center;width:100px'],
            ],
            [
            'attribute'=>'price',
            'value' => function($model){
                return number_format($model->price).' VNĐ';
            },
            'headerOptions'=>['style'=>'text-align:center;width:150px'],
            ],
           

            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Tùy chọn',
            'headerOptions'=>['style'=>'text-align:center'],
            'contentOptions'=>['style'=>'text-align:center'],

            'buttons'=>[
                'view' =>function ($url,$model) {
                    return Html::a('Xem',$url,['class'=>'btn btn-sm btn-primary']);
                },
                'update' =>function ($url,$model) {
                    return Html::a('Sửa',$url,['class'=>'btn btn-sm btn-success']);
                },
                
                'delete' =>function ($url,$model) {
                    return Html::a('Xóa',$url,
                        ['class'=>'btn btn-sm btn-danger',
                        'data-confirm'=>'Bạn có chắc chắn muốn xóa không?',
                        'data-method'=>'post'
                    ]
                );
                },
            ]
        ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
