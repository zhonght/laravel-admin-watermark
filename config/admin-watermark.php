<?php

return [
    /*
     * 是否启用水印
     */
    'enable' => env('ADMIN_WATERMARK_ENABLE', true),

    /*
     * 水印附加文字，例如系统名称
     * 留空则不显示
     */
    'text' => env('ADMIN_WATERMARK_TEXT', ''),

    /*
     * 水印透明度 0~1
     */
    'opacity' => 0.1,

    /*
     * canvas 宽高（水印单元格尺寸）
     */
    'canvas_width'  => 400,
    'canvas_height' => 300,

    /*
     * 字体大小
     */
    'font_size' => 18,

    /*
     * 旋转角度（弧度，负值为逆时针）
     */
    'rotate' => -0.3,
];
