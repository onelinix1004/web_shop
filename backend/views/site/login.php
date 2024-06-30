<?php


use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-offset-3 col-md-8">
    <hr style="text-align: center; width: 75%">
</div>
<div class="row vertical" style="margin-top: -50px">
    <div class="col-md-5 col-md-offset-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-log-in"></i> Vui lòng đăng nhập với tài khoản của người quản trị</h3>
            </div>
            <div class="panel-body">

                <div class="messages">

                </div>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <fieldset>
                    <div class="form-group">
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Tên đăng nhập') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'password')->passwordInput()->label('Mật khẩu') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'rememberMe')->checkbox()->label('Ghi nhớ') ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Đăng Nhập', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </fieldset>
                <?php ActiveForm::end(); ?>

            </div>
            <!-- panel-body -->
        </div>
        <!-- /panel -->
    </div>
    <br>
    <br>
    <br>
