<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Response;

class View extends ActiveRecord
{
    
    public $last_access_time;


    public static function tableName()
    {
        return 'views';
    }

    public function rules()
    {
        return [
            [['product_id', 'count'], 'required'],
            [['product_id', 'count'], 'integer'],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function actionIncreaseViewCount($id)
{
    $product = Product::findOne($id);

    if ($product !== null) {
        $view = View::findOne(['product_id' => $product->id]);
        if ($view === null) {
            $view = new View(['product_id' => $product->id, 'count' => 1]);
        } else {
            $view->count++;
        }
        $view->save();
    }

    Yii::$app->response->format = Response::FORMAT_JSON;
    return ['success' => true];
}
}