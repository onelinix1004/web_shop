<?php
namespace frontend\components;

use Yii;
/**
 *
 */
class Cart
{
    public $cartstore;

    public function __construct()
    {
        $this->cartstore = Yii::$app->session['cart'];

    }

    public function add($data,$quantity)
    {
        if (isset($data->id)) {
            if(isset($this->cartstore[$data->id])){

                $this->cartstore[$data->id]->quantity = $quantity + $this->cartstore[$data->id]->quantity;
                Yii::$app->session['cart'] = $this->cartstore;
            }
            else{
                $this->cartstore[$data->id] = $data;
                $this->cartstore[$data->id]->quantity = $quantity;
                Yii::$app->session['cart'] = $this->cartstore;

            }
        }else{

        }

    }






    public function remove($product_id) {
        if (isset($product_id) && isset($this->cartstore[$product_id])) {
            unset($this->cartstore[$product_id]);
            Yii::$app->session['cart'] = $this->cartstore;
        } else {
            // Xử lý trường hợp $data không phải là đối tượng hợp lệ hoặc không có trong giỏ hàng (có thể thông báo lỗi hoặc làm gì đó tùy ý)
        }
    }


    public function clear(){
        $this->cartstore=[];
        Yii::$app->session['cart'] = $this->cartstore;

    }

    public function update($data, $quantity) {
        if (isset($data->id)) {
            $this->cartstore[$data->id]->quantity = $quantity;
            Yii::$app->session['cart'] = $this->cartstore;
        } else {
            // Xử lý trường hợp $data không phải là đối tượng hợp lệ (có thể thông báo lỗi hoặc làm gì đó tùy ý)
        }
    }


    public function getCost(){
        $cost = 0;
        if($this->cartstore){
            foreach ($this->cartstore as $item) {
                $cost += $item->quantity * $item->price;
            }
        }
        return $cost;
    }

    public function getTotalItem(){
        $total = 0;
        if($this->cartstore){
            foreach ($this->cartstore as $item) {
                $total += $item->quantity;
            }
        }
        return $total;
    }
}



?>