<?php 
namespace System\Libraries;

use System\Core\AppException;

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Template {
    // Biến lưu trữ meta (key => value)
    protected static $metas = [];
    protected static $scripts = [];
    protected static $styles = [];

    // private static $name;
    // private static $path;
    // private static function init() {
    //     if (self::$name === null || self::$path === null) {
    //         $themeConfig = config('theme');
    //         self::$name = isset($themeConfig['theme_name']) ? $themeConfig['theme_name'] : 'default';
    //         self::$path = APP_PATH . 'Views/'.self::$name.'/';
    //     }
    // }

    // load block
    public static function block($blockName, $data = []) {
        $blockName = ucfirst($blockName);

        if($blockName === 'Head') {
            if(empty($data['meta'])) $data['meta'] = [];
            $data['meta'] = array_merge($data['meta'], self::$metas);
        } elseif($blockName === 'Foot') {
            if(empty($data['script'])) $data['script'] = [];
            if(empty($data['style'])) $data['style'] = [];
            $data['script'] = array_merge($data['script'], self::$scripts);
            $data['style'] = array_merge($data['style'], self::$styles);
        }
        $blockClass = "\App\Blocks\\".$blockName."\\".$blockName."Block";
        echo $blockClass;
        if (class_exists($blockClass)) {
            $block = new $blockClass();
            $block->setProps($data);
            $block->render();
        } else {
            throw new AppException("Block class $blockClass không tồn tại.");
        }
    }

    /**
     * Hàm cập nhật meta.
     * Mỗi lần gọi sẽ lưu các cặp key=>value.
     * Nếu key đã tồn tại thì cập nhật lại.
     *
     * @param array $data Mảng meta (vd: ['title'=>'Tiêu đề', 'description'=>'Mô tả'])
     */
    public static function meta($data = []) {
        foreach ($data as $key => $value) {
            self::$metas[$key] = $value;
        }
    }

    public static function script($data = []) {
        foreach ($data as $key => $value) {
            self::$scripts[$key] = $value;
        }
    }

    public static function style($data = []) {
        foreach ($data as $key => $value) {
            self::$styles[$key] = $value;
        }
    }
    
}
