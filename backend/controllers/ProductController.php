<?php

namespace backend\controllers;

use Yii;
use backend\controllers\Comment;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * Lists all Product models.
     * @return mixed
     */



    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */



     public function actionCreate()
     {
         $model = new Product();
 
         if ($model->load(Yii::$app->request->post())) {
             $model->file = UploadedFile::getInstance($model, 'file');
             $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile'); // Thêm phần này để xử lý tệp PDF
 
             if ($model->file) {
                 $imageName = 'product' . rand(1, 100000);
                 $model->file->saveAs('upload/' . $imageName . '.' . $model->file->extension);
                 $model->image = 'upload/' . $imageName . '.' . $model->file->extension;
             }
 
             if ($model->pdfFile) {
                $pdfName = 'pdf' . rand(1, 100000);
                $model->pdfFile->saveAs(Yii::getAlias('@frontend/web/pdf/') . $pdfName . '.' . $model->pdfFile->extension);
                $model->pdf = '/pdf/' . $pdfName . '.' . $model->pdfFile->extension;
            }
 
             $model->created_at = time();
             $model->updated_at = time();
 
             if ($model->save(false)) {
                 Yii::$app->session->setFlash('success', 'Đã thêm thành công ' . $model->name . '!');
                 return $this->redirect(['index']);
             } else {
                 Yii::$app->session->setFlash('error', 'Lỗi khi lưu sản phẩm!');
             }
         }
 
         return $this->render('create', ['model' => $model]);
     }
 
     public function actionUpdate($id)
     {
         $model = $this->findModel($id);
 
         if ($model->load(Yii::$app->request->post())) {
             $model->file = UploadedFile::getInstance($model, 'file');
             $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile'); // Thêm phần này để xử lý tệp PDF
 
             if ($model->file) {
                 $imageName = 'product' . rand(1, 100000);
                 $model->file->saveAs('upload/' . $imageName . '.' . $model->file->extension);
                 $model->image = 'upload/' . $imageName . '.' . $model->file->extension;
             }
 
             if ($model->pdfFile) {
                $pdfName = 'pdf' . rand(1, 100000);
                $model->pdfFile->saveAs(Yii::getAlias('@frontend/web/pdf/') . $pdfName . '.' . $model->pdfFile->extension);
                $model->pdf = '/pdf/' . $pdfName . '.' . $model->pdfFile->extension;
            }
 
             $model->updated_at = time();
 
             if ($model->save(false)) {
                 Yii::$app->session->setFlash('success', 'Đã cập nhật thành công ' . $model->name . '!');
                 return $this->redirect(['view', 'id' => $model->id]);
             } else {
                 Yii::$app->session->setFlash('error', 'Lỗi khi cập nhật sản phẩm!');
             }
         }
 
         return $this->render('update', ['model' => $model]);
     }








    // Ví dụ trong ProductController
// Ví dụ trong action BuyBookController
public function actionBuybook()
{
    // Đoạn mã để lấy danh sách top 10 sản phẩm được mua nhiều nhất từ cơ sở dữ liệu
    $top10Products = Product::find()
        ->orderBy(['sales_count' => SORT_DESC]) // Sắp xếp giảm dần theo sales_count
        ->limit(10) // Giới hạn trả về 10 kết quả
        ->all();

    return $this->render('buybook', ['top10Products' => $top10Products]);
}









    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Đã xóa thành công !');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
