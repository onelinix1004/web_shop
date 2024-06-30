<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Product[] $products
 */
//Lớp Category kế thừa từ lớp yii\db\ActiveRecord,
// cho phép chúng ta tương tác dễ dàng với cơ sở dữ liệu thông qua các đối tượng Category.
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


     //Phương thức tableName() được định nghĩa để xác định tên bảng cơ sở
     // dữ liệu tương ứng với lớp Category. Trong ví dụ này, bảng có tên là "category".
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */

     //Phương thức rules() chứa các quy tắc hợp lệ (validation rules) được áp dụng cho thuộc 
     //tính name của đối tượng Category. Đoạn mã này quy định rằng thuộc tính name là bắt buộc (required),
     //không vượt quá 255 ký tự (string, max 255) và duy nhất (unique).
    public function rules()
    {
        return [
            [['name'], 'required','message'=>'Không được để trống !'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */

     //Phương thức attributeLabels() xác định các nhãn
     // cho các thuộc tính của đối tượng, nhằm mục đích hiển thị cho người dùng.
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

     //Phương thức getProducts() là một phương thức truy vấn liên kết (Active Query) được định nghĩa 
     //để thiết lập mối quan hệ giữa đối tượng Category và Product 
     //thông qua khóa ngoại category_id của bảng Product.
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }
}
