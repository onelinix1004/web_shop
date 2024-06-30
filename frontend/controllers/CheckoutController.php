<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\Cart;
use frontend\models\Checkout;
use backend\models\Orders;
use backend\models\OrdersItem;

class CheckoutController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', 'Bạn phải đăng nhập để thực hiện chức năng thanh toán!');
            return $this->redirect('index.php?r=site/login');
        } elseif (Yii::$app->session['cart'] == null) {
            return $this->redirect('index.php?r=cart/index');
        } else {
            $model = new Checkout;
            $cart = new Cart;
            $cartstore = $cart->cartstore;
            $cost = $cart->getCost();
            $total = $cart->getTotalItem();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $order = new Orders;
                $post = Yii::$app->request->post()['Checkout'];
                $order->user_id = Yii::$app->user->identity->id;
                $order->amount = $post['amount'];
                $order->name = $post['name'];
                $order->phone = $post['phone'];
                $order->address = $post['address'];
                $order->status = 0;
                $order->note = $post['note'];
                $order->created_at = time();
                $order->updated_at = time();

                if ($order->save(false)) {
                    $cart = new Cart;
                    $cartstore = $cart->cartstore;

                    foreach ($cartstore as $item) {
                        $orderitem = new OrdersItem;
                        $orderitem->orders_id = $order->id;
                        $orderitem->product_id = $item->id;
                        $orderitem->quantity = $item->quantity;
                        $orderitem->price = $item->price * $item->quantity;
                        $orderitem->status = 0;
                        $orderitem->created_at = time();
                        $orderitem->updated_at = time();
                        $orderitem->save(false);
                    }

                    Yii::$app->session->setFlash('success', 'Bạn đã đặt hàng thành công!');
                    $cart->clear();

                    // Redirect to the order details page with the order ID
                    return $this->redirect(['order-details', 'id' => $order->id]);
                }
            } else {
                return $this->render('index', [
                    'model' => $model,
                    'cartstore' => $cartstore,
                    'cost' => $cost,
                    'total' => $total,
                ]);
            }
        }
    }

    public function actionOrderDetails($id)
    {
        $order = Orders::findOne($id);
        if (!$order) {
            throw new \yii\web\NotFoundHttpException('Order not found.');
        }

        $orderItems = OrdersItem::find()->where(['orders_id' => $order->id])->all();

        return $this->render('order-details', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }
}