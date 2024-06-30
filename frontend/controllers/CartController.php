<?php
namespace frontend\controllers;
use yii\web\Controller;
use Yii;
use frontend\components\Cart;
use backend\models\Product;
/**
 *
 */
class CartController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex(){

        $cart = new Cart;
        $cartstore = $cart->cartstore;
        $cost = $cart->getCost();
        $total = $cart->getTotalItem();
        return $this->render('index', [
            'cartstore' => $cartstore,
            'cost'=>$cost,
            'total' => $total
        ]);
    }

    public function actionAddCart($id){

        $cart = new Cart;
        $data = Product::findOne(['id'=>$id]);
        $post = Yii::$app->request->post();
        $quantity = $_POST['quantity'];
        $cart->add($data,$quantity);
        return $this->redirect(['index']);

    }

    public function actionRemove($id){

        $cart = new Cart;
        // $data = Product::findOne(['id'=>$id]);
        $cart->remove($id);
        return $this->redirect(['index']);

    }

    public function actionUpdate($id){

        $cart = new Cart;
        $data = Product::findOne(['id'=>$id]);
        $post = Yii::$app->request->post();
        $quantity = $_POST['quantity'];
        $cart->update($data,$quantity);
        return $this->redirect(['index']);

    }

    public function actionClear(){
        $cart = new Cart;
        $cart->clear();
        return $this->redirect(['index']);

    }


}


?>