<?php
// Đếm số lượng bản ghi trong các bảng User, Orders, Product và Contact
$countUser = backend\models\User::find()->count();
$countOrders = backend\models\Orders::find()->count();
$countProduct = backend\models\Product::find()->count();
$countContact = backend\models\Contact::find()->count();
?>


<head>
    <title>Top 10 cuốn sách bán chạy nhất</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .top-heading {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>


<!-- Hiển thị thông tin thống kê -->
<div class="container-fluid">
    <div class="row">
        <!-- Khối thông tin thống kê cho bảng User -->
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Thành viên</p>
                                <?=$countUser?>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            <i class="ti-reload"></i> <a href="index.php?r=user">Quản lý thành viên</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Khối thông tin thống kê cho bảng Product -->
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-success text-center">
                                <i class="ti-wallet"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Sách</p>
                                <?=$countProduct?>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            <i class="ti-calendar"></i> <a href="index.php?r=product/create">Thêm sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Khối thông tin thống kê cho bảng Orders -->
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-danger text-center">
                                <i class="ti-list"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Đơn hàng</p>
                                <?=$countOrders?>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            <i class="ti-timer"></i> <a href="index.php?r=orders">Xem đơn hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Khối thông tin thống kê cho bảng Contact -->
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-info text-center">
                                <i class="ti-twitter-alt"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Liên Hệ</p>
                                <?=$countContact?>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            <i class="ti-reload"></i> <a href="">Quản lý</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- CSS -->
<style>
    canvas {
        max-width: 100%;
    }
</style>

<!-- Đồ thị hình chữ nhật chiếm 75% trang -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9 col-md-12">
            <canvas id="myChart"></canvas>
        </div>

        <!-- Đồ thị hình tròn chiếm 25% trang -->
        <div class="col-lg-3 col-md-12">
            <canvas id="myPieChart" width="200" height="200"></canvas>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Dữ liệu đồ thị hình chữ nhật
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?php echo json_encode($data); ?>,
                pointStyle: 'circle',
                pointRadius: 10,
                pointHoverRadius: 15,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            animations: {
                radius: {
                    duration: 400,
                    easing: 'linear',
                    loop: (context) => context.active
                }
            },
        }
    });

    // Dữ liệu đồ thị hình tròn
    var ctx2 = document.getElementById('myPieChart').getContext('2d');
    var myPieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($categoryLabels); ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?php echo json_encode(array_column($categoryData, 'total')); ?>,
                backgroundColor: <?php echo json_encode($bgColors); ?>,
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Biểu đồ doanh thu theo danh mục sản phẩm',
            },
            animation: {
                animateScale: true,
                animateRotate: true,
            },
        }
    });
</script>

<h1 class="top-heading">Top 10 cuốn sách Rating cao nhất</h1>
<table class="table">
    <thead>
    <tr>
        <th class="col-md-6">Product Name</th>
        <th class="col-md-6">Total Rating</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bestRatingProducts as $product): ?>
        <tr>
            <td class="col-md-6"><?= $product['name'] ?></td>
            <td class="col-md-6"><?= $product['avg_rating'] ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>


<h1 class="top-heading">Top 10 cuốn sách lượt xem cao nhất</h1>
<table class="table">
    <thead>
    <tr>
        <th class="col-md-6">Product Name</th>
        <th class="col-md-6">Total Rating</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bestViewProducts as $product): ?>
        <tr>
            <td class="col-md-6"><?= $product['name'] ?></td>
            <td class="col-md-6"><?= $product['total_views'] ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>