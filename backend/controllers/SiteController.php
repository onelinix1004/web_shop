<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Orders;
use backend\models\OrdersItem;
use yii\helpers\ArrayHelper;
use backend\models\Category;
use backend\models\Product;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Line chart
        // Line chart
        $orders = OrdersItem::findBySql("
  SELECT
    date,
    total_price
FROM (
    SELECT
        CAST(DATE_FORMAT(FROM_UNIXTIME(o.created_at), '%Y-%m-%d %H:%i:%s') AS DATE) AS date,
        SUM(o.price) AS total_price
    FROM orders_item o
    GROUP BY CAST(DATE_FORMAT(FROM_UNIXTIME(o.created_at), '%Y-%m-%d %H:%i:%s') AS DATE)
) AS subquery
ORDER BY date;
")
            ->asArray()
            ->all();

        $earningsData = [];
        $labels = [];
        foreach ($orders as $order) {
            $date = date('d/m/Y', strtotime($order['date']));
            $labels[] = $date;
            $earningsData[] = $order['total_price'];
        }


        $categoryData = OrdersItem::find()
            ->select(['category.name', 'SUM(orders_item.price) AS total'])
            ->innerJoin('product', 'orders_item.product_id = product.id')
            ->innerJoin('category', 'product.category_id = category.id')
            ->innerJoin('orders', 'orders_item.orders_id = orders.id')
            ->groupBy(['category.name'])
            ->asArray()
            ->all();

        $categoryLabels = Category::find()->select('name')->column(); // Trích xuất mảng tên category từ đối tượng Category


        $colorCount = count($categoryLabels);
        $bgColors = [];
        for ($i = 0; $i < $colorCount; $i++) {
            $bgColors[] = 'hsl(' . (360 / $colorCount * $i) . ', 70%, 50%)';
        }

        /**
         * $bestSellingProducts = Product::find()
         *  ->select(['product.name', 'SUM(orders_item.quantity) AS total_sales'])
         *   ->leftJoin('orders_item', 'product.id = orders_item.product_id')
         *  ->groupBy('product.id')
         *   ->orderBy(['total_sales' => SORT_DESC])
         *   ->limit(10)
         *   ->asArray() // Chuyển kết quả truy vấn thành mảng
         *   ->all();
         */

        $bestRatingProducts = Product::find()
            ->select(['p.name', 'AVG(r.rating) AS avg_rating'])
            ->from('product p')
            ->innerJoin('reviews r', 'p.id = r.product_id')
            ->groupBy('p.id')
            ->orderBy(['avg_rating' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        $bestViewProducts = Product::find()
            ->select(['p.id', 'p.name', 'SUM(v.count) AS total_views'])
            ->from('product p')
            ->innerJoin('views v', 'p.id = v.product_id')
            ->groupBy('p.id')
            ->orderBy(['total_views' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();


        return $this->render('index',[
            'data' => $earningsData,
            'labels' => $labels,
            'categoryLabels' => $categoryLabels,
            'bgColors' => $bgColors,
            'categoryData' => $categoryData,
            'bestRatingProducts' => $bestRatingProducts,
            'bestViewProducts' => $bestViewProducts,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {

        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}