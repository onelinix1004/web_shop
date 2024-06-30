<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách đơn hàng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
       
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            'attribute'=>'user_id',
            'value'=>'user.username',
            'headerOptions'=>['style'=>'text-align:center;width:100px'],
            ],
            [
            'attribute'=>'amount',
            'value' => function($model){
                return number_format($model->amount).' VNĐ';
            },
            'headerOptions'=>['style'=>'text-align:center;width:150px'],
            ],
           
            [
            'attribute'=>'phone',
            'headerOptions'=>['style'=>'text-align:center;width:100px'],
            ],
             [
            'attribute'=>'address',
            'headerOptions'=>['style'=>'text-align:center;width:300px'],
            ],
             
            [
            'attribute'=>'status',
            'value' => function($model){
                if($model->status==0){return 'Chưa xử lý';
                    
                }else{return 'Đã xử lý';}
            },
            'headerOptions'=>['style'=>'text-align:center;width:150px'],
            ],
            // 'created_at',
            // 'updated_at',

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
