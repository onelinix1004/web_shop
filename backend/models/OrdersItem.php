<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_item".
 *
 * @property integer $id
 * @property integer $orders_id
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $price
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Orders $orders
 * @property Product $product
 */
class OrdersItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orders_id', 'product_id', 'quantity', 'price', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['status'], 'string', 'max' => 255],
            [['orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['orders_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orders_id' => 'Khách hàng',
            'product_id' => 'Thực đơn',
            'quantity' => 'Số lượng',
            'price' => 'Tổng tiền',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày đặt hàng',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['id' => 'orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
