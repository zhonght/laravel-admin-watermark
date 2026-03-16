# laravel-admin-watermark

基于 laravel-admin 的后台水印扩展包。

## 安装

```bash
composer require zhonght/laravel-admin-watermark
```

发布配置文件：

```bash
php artisan vendor:publish --tag=admin-watermark-config
```

## 配置

`config/admin-watermark.php`：

```php
return [
    'enable'        => env('ADMIN_WATERMARK_ENABLE', true),
    'text'          => env('ADMIN_WATERMARK_TEXT', ''),   // 附加文字，如系统名称
    'opacity'       => 0.1,
    'canvas_width'  => 400,
    'canvas_height' => 300,
    'font_size'     => 18,
    'rotate'        => -0.3,
];
```

或直接在 `.env` 里控制：

```
ADMIN_WATERMARK_ENABLE=true
ADMIN_WATERMARK_TEXT=我的系统
```

## 使用

安装后自动生效，无需手动调用任何方法。

水印内容：`当前登录用户名 - 当前时间` + 附加文字（可选）。

## 特性

- 基于 `requestAnimationFrame` 驱动，只在秒数变化时重绘，性能优于 `setInterval`
- `MutationObserver` 防篡改，水印 DOM 被删除时自动恢复
