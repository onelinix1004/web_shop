<?php

namespace backend\controllers;//Điều này có nghĩa là class này sẽ nằm trong thư mục "backend\controllers" 

use Yii;// sử dụng các tính năng và framework của YII2
use backend\models\Category;//import các lớp "Category" từ thư mục "backend\models", cho phép sử dụng chúng trong controller này.
use backend\models\CategorySearch;//import các lớp "CategorySearch" từ thư mục "backend\models", cho phép sử dụng chúng trong controller này.
use yii\web\Controller;//import các lớp liên quan đến Controller của Yii2.
use yii\web\NotFoundHttpException;//import các lớp liên quan đến NotFoundHttpException của Yii2.
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CategoryController implements the CRUD actions for Category model.
 * Các phương thức (actions):
 *     + Đoạn mã chứa các phương thức để thực hiện các hành động CRUD (Create, Read, Update, Delete)
 *       với thực thể "Category".
 * 
 * Behavior (Hành vi):
 * Phương thức behaviors() định nghĩa các hành vi (behaviors) được áp dụng cho controller. Ở đây, có hai hành vi chính:
 * + Hành vi "access": Áp dụng kiểm soát truy cập để quản lý quyền truy cập vào các hành động trong controller.
 * Người dùng cần phải đăng nhập để thực hiện các hành động, và chỉ người dùng có quyền "admin" mới được phép truy cập.
 * + Hành vi "verbs": Xác định các phương thức HTTP (GET, POST) cho từng hành động trong controller. 
 * Trong trường hợp này, phương thức "logout" có thể được gọi bằng cả POST và GET.
 * 
 * Các hành động chính:
 * actionIndex(): Hiển thị danh sách các mô hình "Category" thông qua giao diện "index" và hỗ trợ tìm kiếm.
 * actionView($id): Hiển thị thông tin chi tiết của một mô hình "Category" cụ thể thông qua giao diện "view".
 * actionCreate(): Tạo một mô hình "Category" mới và lưu vào cơ sở dữ liệu. Nếu việc tạo thành công, sẽ chuyển hướng đến trang hiển thị thông tin chi tiết của mô hình vừa tạo.
 * actionUpdate($id): Cập nhật thông tin của mô hình "Category" hiện có với dữ liệu được gửi từ form. Nếu cập nhật thành công, sẽ chuyển hướng đến trang hiển thị thông tin chi tiết của mô hình vừa cập nhật.
 * actionDelete($id): Xóa một mô hình "Category" cụ thể khỏi cơ sở dữ liệu.
 * 
 * 
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /**
             * Hành vi "access" (Kiểm soát truy cập):
             * Đoạn mã sử dụng hành vi "AccessControl" để kiểm soát quyền truy cập vào các hành động trong controller.
             * Có hai tập luật (rules) được định nghĩa:
             *  +Tập luật thứ nhất cho phép truy cập vào các hành động "login" và "error" mà không cần xác thực (allow = true).
             *  +Tập luật thứ hai cho phép truy cập vào các hành động còn lại (nằm trong mảng 'actions') 
             * chỉ khi người dùng đã đăng nhập (roles=['@']). Điều này đảm bảo rằng chỉ có người dùng đã đăng nhập mới có thể truy cập vào các hành động CRUD.
             * 
             * Đặc biệt, có một "matchCallback" được sử dụng trong tập luật thứ hai. Nó cho phép định nghĩa
             *  một hàm callback (hàm được đưa vào như một closure) để kiểm tra điều kiện tùy 
             * chỉnh cho việc cho phép truy cập. Trong trường hợp này, nếu người dùng có quyền "admin" 
             * (được xác định bởi Yii::$app->user->can('admin')), thì hành động sẽ được cho phép truy cập (return true)
             * . Ngược lại, nếu người dùng không có quyền "admin", thì hành động sẽ bị từ chối truy cập (return false).
             */
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['@'],
                        'matchCallback' => function ($rule,$action){
                            if (Yii::$app->user->can('admin')){
                                return true;
                            }

                        }
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
    
    
    // khi có một yêu cầu gửi đến controller và hành động được yêu cầu là 'error',
    // thì hệ thống sẽ chạy lớp yii\web\ErrorAction để xử lý yêu cầu và trả về trang lỗi tương ứng.
    
    
     public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */




     //hàm actionIndex() này sẽ thực hiện việc tạo một đối tượng CategorySearch, thực hiện tìm kiếm 
     //các danh mục dựa trên các yêu cầu từ người dùng
     //, và sau đó hiển thị danh sách các danh mục này trên trang chủ của ứng dụng.
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */

     //phương thức "actionView" là hiển thị giao diện (view) của mục được yêu cầu cùng với thông tin 
     //chi tiết của nó, bằng cách sử dụng dữ liệu được truyền vào trong mảng 'model'.
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

     //phương thức actionCreate() này được sử dụng để hiển thị trang tạo mới 
     //và lưu thông tin được nhập vào form để tạo mới một bản ghi mới của mô hình Category.
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    //hàm actionUpdate này được sử dụng để xử lý quá trình cập nhật thông tin của một mô hình trong ứng dụng 
    //web Yii Framework khi người dùng gửi dữ liệu thông qua một form chỉnh sửa. Nếu cập nhật thành công, 
    //người dùng sẽ được chuyển hướng đến trang hiển thị thông tin chi tiết của mô hình,
    // ngược lại, họ sẽ được hiển thị lại trang chỉnh sửa với thông tin hiện tại của mô hình
    // và thông báo lỗi (nếu có).
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

     //Phương thức actionDelete($id) là một hàm trong ứng dụng PHP dùng để xóa một bản ghi dựa trên $id. 
     //Nó tìm và xóa đối tượng tương ứng, sau đó chuyển hướng người dùng đến trang danh sách.
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    //hàm này sử dụng để tìm kiếm đối tượng "Category" với ID cho trước và đảm bảo rằng 
    //trang yêu cầu tồn tại trước khi tiếp tục xử lý dữ liệu.
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
