<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class ReviewSearch extends Model
{
    public $product_id;

    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
        ];
    }

    public function getReviewStats()
    {
        $reviews = Review::find()->where(['product_id' => $this->product_id])->all();
        $count = count($reviews);
        $avg_rating = $count > 0 ? round(array_sum(array_column($reviews, 'rating')) / $count, 1) : 0.0;
        return [$count, $avg_rating];
    }
}