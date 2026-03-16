<?php

namespace Zhonght\AdminWatermark;

use Encore\Admin\Admin;
use Encore\Admin\Facades\Admin as AdminFacade;

class Watermark
{
    public static function inject(): void
    {
        if (!config('admin-watermark.enable')) {
            return;
        }

        Admin::style(static::style());
        Admin::script(static::script());
        Admin::html(static::html());
    }

    protected static function style(): string
    {
        $opacity = (float) config('admin-watermark.opacity', 0.1);

        return <<<STYLE
        .wm-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: {$opacity};
            background-image: var(--wm-bg);
            background-repeat: repeat;
        }
STYLE;
    }

    protected static function script(): string
    {
        $user       = AdminFacade::user();
        $adminName  = addslashes($user->name ?? '未登录');
        $extraText  = '';
        $rawText    = config('admin-watermark.text', '');
        if ($rawText) {
            $extraText = addslashes('【' . $rawText . '】');
        }

        $canvasW  = (int) config('admin-watermark.canvas_width', 400);
        $canvasH  = (int) config('admin-watermark.canvas_height', 300);
        $fontSize = (int) config('admin-watermark.font_size', 18);
        $rotate   = (float) config('admin-watermark.rotate', -0.3);

        return <<<SCRIPT
(function () {
    var adminName = '{$adminName}';
    var extraText = '{$extraText}';
    var canvasW   = {$canvasW};
    var canvasH   = {$canvasH};
    var fontSize  = {$fontSize};
    var rotate    = {$rotate};

    var container = document.querySelector('.wm-container');

    function buildDataURL(timeStr) {
        var canvas = document.createElement('canvas');
        canvas.width  = canvasW;
        canvas.height = canvasH;
        var ctx = canvas.getContext('2d');
        ctx.font      = fontSize + 'px Arial';
        ctx.fillStyle = '#000';
        ctx.rotate(rotate);
        ctx.fillText(adminName + ' - ' + timeStr, 10, 100);
        if (extraText) {
            ctx.fillText(extraText, 10, 100 + fontSize + 10);
        }
        return canvas.toDataURL('image/png');
    }

    function getTimeStr() {
        return new Date().toLocaleString('zh-CN', {
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false
        });
    }

    var lastSecond = '';

    function tick() {
        var now = getTimeStr();
        // 只在秒数变化时重绘，避免每帧都操作 canvas
        if (now !== lastSecond) {
            lastSecond = now;
            document.documentElement.style.setProperty('--wm-bg', 'url(' + buildDataURL(now) + ')');
        }
        requestAnimationFrame(tick);
    }

    // 防篡改：监听水印节点被移除时自动恢复
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            m.removedNodes.forEach(function (node) {
                if (node === container) {
                    document.body.appendChild(container);
                }
            });
        });
        // 防止 style 属性被清除
        if (!container.parentNode) {
            document.body.appendChild(container);
        }
    });

    observer.observe(document.body, { childList: true, subtree: false });

    tick();
})();
SCRIPT;
    }

    protected static function html(): string
    {
        return '<div class="wm-container"></div>';
    }
}
