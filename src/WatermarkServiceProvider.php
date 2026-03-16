<?php

namespace Zhonght\AdminWatermark;

use Encore\Admin\Admin;
use Illuminate\Support\ServiceProvider;

class WatermarkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/admin-watermark.php',
            'admin-watermark'
        );
    }

    public function boot(): void
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../config/admin-watermark.php' => config_path('admin-watermark.php'),
        ], 'admin-watermark-config');

        // laravel-admin booted 后注入水印
        Admin::booted(function () {
            Watermark::inject();
        });
    }
}
