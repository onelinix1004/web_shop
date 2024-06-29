<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap.min.css',
        'css/animate.min.css',
        'css/owl.carousel.css',
        'css/style.css',
        'css/jquery-ui.css',
        'css/aos.css',
        'css/bootstrap-datepicker.css',
        'css/flaticon.css',
        'css/icomoon.css',
        'css/icoicons-popup.css',
        'css/magnific-popup.css',
        'css/open-iconic-bootstrap.min.css',
        'css/owl.theme.default.min.css',
    ];
    public $js = [
        'js/aos.js',
        'js/bootstrap-datepicker.js',
        'js/bootstrap.min.js',
        'js/jquery.animateNumber.min.js',
        'js/jquery.easing.1.3.js',
        'js/jquery.magnific-popup.min.js',
        'js/jquery.min.js',
        'js/jquery.stellar.min.js',
        'js/jquery.timepicker.min.js',
        'js/waypoints.min.js',
        'js/jquery-3.2.1.min.js',
        'js/jquery-migrate-3.0.1.min.js',
        'js/main.js',
        'js/owl.carousel.min.js',
        'js/popper.min.js',
        'js/range.js',
        'js/scrollax.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
