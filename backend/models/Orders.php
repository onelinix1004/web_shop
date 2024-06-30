<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property OrdersItem[] $ordersItems
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'created_at', 'updated_at'], 'integer'],
            [['name', 'phone', 'address', 'created_at', 'updated_at'], 'required'],
            [['name', 'address'], 'string', 'max' => 225],
            [['phone'], 'string', 'max' => 14],
            [['status'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Username',
            'amount' => 'Tổng tiền',
            'name' => 'Khách hàng',
            'phone' => 'Liên hệ',
            'address' => 'Địa chỉ',
            'note' => 'Ghi chú thêm',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày đặt hàng',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersItems()
    {
        return $this->hasMany(OrdersItem::className(), ['orders_id' => 'id']);
    }
}
