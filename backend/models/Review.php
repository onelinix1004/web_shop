<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Review extends ActiveRecord
{
    public static function tableName()
    {
        return 'reviews';
    }

    public function rules()
    {
        return [
            [['product_id', 'user_id', 'rating', 'created_at'], 'required'],
            [['product_id', 'user_id', 'rating', 'created_at'], 'integer'],
            ['rating', 'in', 'range' => [1, 2, 3, 4, 5]],
            [['comment'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
 * @param int $rating Điểm đánh giá từ 1 đến 5
 * @return string Chuỗi chứa các icon sao tương ứng với điểm đánh giá
 */
function printRatingStars($rating)
{
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<i class="fa fa-star"></i> '; // in ra icon sao đầy
        } else {
            $stars .= '<i class="fa fa-star-o"></i> '; // in ra icon sao rỗng
        }
    }
    return $stars;
}

}