<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $price
 * @property string $description
 * @property integer $category_id
 * @property integer $sales_count //Thêm cột sales_count vào đây
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OrdersItem[] $ordersItems
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;
    public $pdfFile; // Thuộc tính tạm thời để tải lên tệp PDF
    public $quantity;
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'category_id','description'], 'required','message'=>'{attribute}  không đưọc để trống'],
            ['file','file','extensions'=>'jpg,png,gif'],
            [['price'], 'number','message'=>'Sai định dạng'],
            [['category_id', 'created_at', 'updated_at', 'sales_count'], 'integer'], //Thêm sales_count vào quy tắc
            [['name', 'image', 'description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'image' => 'Hình ảnh',
            'price' => 'Giá',
            'file' => 'Hình ảnh',
            'description' => 'Mô tả',
            'category_id' => 'Category',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'sales_count' => 'Số lượng bán', //Thêm nhãn cho sales_count
            'pdfFile' => 'Tệp PDF', // Nhãn cho trường tệp PDF
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersItems()
    {
        return $this->hasMany(OrdersItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    // Ví dụ trong action của controller khi xử lý giao dịch mua sách


}
