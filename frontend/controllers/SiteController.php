<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use backend\models\Category;
use backend\models\Product;
use backend\models\Contact;
use frontend\components\Cart;
use backend\models\Comment;
use backend\models\Review;
use backend\models\View;
use frontend\modes\CommentForm;
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
            "access" => [
                "class" => AccessControl::className(),
                "only" => ["logout", "signup"],
                "rules" => [
                    [
                        "actions" => ["signup"],
                        "allow" => true,
                        "roles" => ["?"],
                    ],
                    [
                        "actions" => ["logout"],
                        "allow" => true,
                        "roles" => ["@"],
                    ],
                ],
            ],
            "verbs" => [
                "class" => VerbFilter::className(),
                "actions" => [
                    "logout" => ["post", "get"],
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
            "error" => [
                "class" => "yii\web\ErrorAction",
            ],
            "captcha" => [
                "class" => "yii\captcha\CaptchaAction",
                "fixedVerifyCode" => YII_ENV_TEST ? "testme" : null,
            ],
        ];
    }

    public function actionCategory($id)
    {
        $cart = new Cart();
        $total = $cart->getTotalItem();
        $products = Product::find()
            ->where(["category_id" => $id])
            ->all();
        $count = Product::find()
            ->where(["category_id" => $id])
            ->count();

        return $this->render("category", [
            "products" => $products,
            "total" => $total,
            "count" => $count,
        ]);
    }

    public function actionView($id)
    {
        $cart = new Cart();
        $total = $cart->getTotalItem();
        $product = Product::findOne($id);
        $category = Product::find()
            ->where(["category_id" => $product->category_id])
            ->limit(3)
            ->all();
        $product = Product::findOne($id);
        $comments = Comment::find()
            ->where(["product_id" => $id])
            ->all();
        $reviews = Review::find()
            ->where(['product_id' => $id])
            ->all();

        $view = View::find()
            ->where(['product_id' => $id])
            ->all();

        if ($view == null) {
            # code...
            $view = new View();
            $view->product_id = $id;
            $view->count = 0;
        }

        $model = new Comment();

        if ($model->load(Yii::$app->request->post())) {
            $model->product_id = $id;
            $model->user_id = Yii::$app->user->id;
            $model->created_at = time();
            $model->save();


            return $this->refresh();
        }

        // Tính toán giá trị trung bình của các đánh giá
        $averageRating = 0;
        if (!empty($reviews)) {
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }
            $averageRating = $totalRating / count($reviews);
        }

        $reviewModel = new Review();
        $userId = Yii::$app->user->id;

// Kiểm tra xem người dùng đã có đánh giá cho sản phẩm chưa
        $existingReview = Review::findOne(['user_id' => $userId, 'product_id' => $id]);

        if ($existingReview) {
            if ($reviewModel->load(Yii::$app->request->post())) {
                $existingReview->rating = $reviewModel->rating;
                $existingReview->comment = $reviewModel->comment;
                $existingReview->created_at = time();
                $existingReview->save();

            }
        } else {
            if ($reviewModel->load(Yii::$app->request->post())) {
                $reviewModel->product_id = $id;
                $reviewModel->user_id = $userId;
                $reviewModel->created_at = time();
                $reviewModel->save();

            }
        }

        return $this->render("view", [
            "product" => $product,
            "category" => $category,
            "total" => $total,
            "comments" => $comments,
            "reviews" => $reviews,
            "view" => $view,
            "averageRating" => $averageRating,
            "model" => $model,
            "reviewModel" => $reviewModel,
        ]);
    }

    public function actionFlipbook($id)
    {
        $product = Product::findOne($id);
        $view = View::find()
            ->where(['product_id' => $id])
            ->one();

        if (!$view) {
            $view = new View();
            $view->product_id = $id;
            $view->count = 0;
            $view->save(false, ['product_id', 'count']);
        }

        // Tăng giá trị count nếu đã truy cập trang flipbook trên 5 giây
        $currentTime = time();
        $lastAccessTime = strtotime($view['last_access_time']);
        $timeDiff = $currentTime - $lastAccessTime;
        if ($timeDiff >= 5) {
            $view['count'] += 1;
            $view['last_access_time'] = date('Y-m-d H:i:s');
            $view->save();
        }

        return $this->render('flipbook',[
            'product' => $product,
            'view' => $view,
        ]);
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $products = Product::find()
            ->limit(40)
            ->all();

        return $this->render("index", [
            "products" => $products,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render("login", [
                "model" => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $cart = new Cart();
        $model = new ContactForm();
        $total = $cart->getTotalItem();
        $contact = new Contact();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = Yii::$app->request->post()["ContactForm"];
            $contact->name = $post["name"];
            $contact->email = $post["email"];
            $contact->phone = $post["phone"];
            $contact->body = $post["body"];
            $contact->created_at = time();
            $contact->updated_at = time();

            if ($contact->save(false)) {
                Yii::$app->session->setFlash("success", "Yêu cầu của bạn đã được gửi đi thành công....");
            } else {
                Yii::$app->session->setFlash("error", "There was an error sending your message.");
            }

            return $this->refresh();
        } else {
            return $this->render("contact", [
                "model" => $model,
                "total" => $total,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $cart = new Cart();
        $total = $cart->getTotalItem();
        return $this->render("about", ["total" => $total]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render("signup", [
            "model" => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash("success", "Check your email for further instructions.");

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash("error", "Sorry, we are unable to reset password for the provided email address.");
            }
        }

        return $this->render("requestPasswordResetToken", [
            "model" => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash("success", "New password saved.");

            return $this->goHome();
        }

        return $this->render("resetPassword", [
            "model" => $model,
        ]);
    }
}