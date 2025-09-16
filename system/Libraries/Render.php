<?php
namespace System\Libraries;
use System\Core\AppException;
use Exception;
use MatthiasMullie\Minify;

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Render {
    //Tên của theme
    private static $themeName;
    // Đường dẫn thư mục theme
    private static $themePath;
    // Đường dẫn thư mục public/assets
    private static $assetsPath;

    /*
     * Quản lý assets theo cấu trúc:
     * assets[area][asset_type][location]
     * asset_type: 'css', 'js', 'inlineCss', 'inlineJs'
     */
    protected static $assets = [
        'frontend' => [
            'css'       => ['head' => [], 'footer' => []],
            'js'        => ['head' => [], 'footer' => []],
            'inlineCss' => ['head' => [], 'footer' => []],
            'inlineJs'  => ['head' => [], 'footer' => []],
        ],
        'backend'  => [
            'css'       => ['head' => [], 'footer' => []],
            'js'        => ['head' => [], 'footer' => []],
            'inlineCss' => ['head' => [], 'footer' => []],
            'inlineJs'  => ['head' => [], 'footer' => []],
        ],
    ];

     /**
     * Khởi tạo và load cấu hình theme một lần duy nhất
     */
    private static function init() {
        if (self::$themeName === null || self::$themePath === null) {
            // Lấy cấu hình theme từ file config
            $themeConfig = config('theme');
            // Lưu tên theme và đường dẫn theme
            self::$themeName = $themeConfig['theme_name'] ?? 'default';
            $themeRelativePath = $themeConfig['theme_path'] ?? 'application/Views';
            self::$themePath = ROOT_PATH . '/' . $themeRelativePath . '/' . self::$themeName . '/';
            if (self::$assetsPath === null) {
                self::$assetsPath = ROOT_PATH . '/';
            }
        }
    }

    /**
     * Lấy tên của theme
     * 
     * @return string Tên theme
     */
    private static function _name() {
        self::init();
        return self::$themeName;
    }

    /**
     * Lấy đường dẫn của theme
     * 
     * @return string Đường dẫn thư mục theme
     */
    private static function _path_theme() {
        self::init();
        return self::$themePath;
    }

    /**
     * Lấy path của thư mục theme theo controller
     * Ví dụ: controller Home thì thư mục theme là home/
     * 
     * @param string $controller Tên của controller
     * @return string Đường dẫn đến thư mục theme của controller
     */
    public static function _path_controller($controller) {
        return self::_path_theme() . strtolower($controller) . '/';
    }

    /**
     * Render toàn bộ layout và view với dữ liệu
     * 
     * @param string $layout Tên layout cần load (ví dụ: 'layout' hoặc 'layout2')
     * @param string $view Tên view cần load (ví dụ: 'home/home')
     * @param array $data Dữ liệu truyền vào view
     * @throws \Exception
     */
    public static function render($layout, $view = null, $data = []) {
        self::init(); // Đảm bảo cấu hình đã được load
        if($view !== null){
            $viewPath = self::_path_theme() . $view . '.php';
            if (!file_exists($viewPath)) {
                throw new AppException("View '{$view}' not found at Path: '{$viewPath}'.");
            }
             // Thêm path của view vào data để truyền vào layout
            $data['view'] = $viewPath;
        }
        $layoutPath = self::_path_theme() . $layout . '.php';
        if (!file_exists($layoutPath)) {
            throw new AppException("Layout '{$layout}' not found at Path: '{$layoutPath}'.");
        }
        // Truyền dữ liệu vào view
        extract($data);
        // Bắt đầu buffer để lưu output vào chuỗi
        ob_start();
        // Gọi layout chính và hiển thị nội dung
        require_once $layoutPath;
        return ob_get_clean();  // Trả về chuỗi
    }

    public static function html($layout, $data = []) {
        self::init(); // Đảm bảo cấu hình đã được load
        $layoutPath = self::_path_theme() . $layout . '.php';
        if (!file_exists($layoutPath)) {
            throw new AppException("Layout '{$layout}' not found at Path: '{$layoutPath}'.");
        }
        extract($data);
        ob_start();
        require_once $layoutPath;
        return ob_get_clean();
    }

    /**
     * Render một component cụ thể và trả về dưới dạng chuỗi
     * 
     * @param string $component Tên component cần render (ví dụ: 'header', 'footer')
     * @param array $data Dữ liệu truyền vào component
     * @return string Kết quả render component
     * @throws \Exception
     */
    public static function component($component, $data = []) {
        self::init(); // Đảm bảo cấu hình đã được load

        $componentPath = self::_path_theme() . $component . '.php';

        if (!file_exists($componentPath)) {
            throw new \Exception("Component '{$component}' không tồn tại tại đường dẫn '{$componentPath}'.");
        }

        // Truyền dữ liệu vào component
        extract($data);

        // Bắt đầu buffer để lưu output
        ob_start();
        require $componentPath;
        return ob_get_clean();
    }

    ////////////////////// QUẢN LÝ ASSETS (CSS, JS) //////////////////////

    /**
     * Thêm file asset (css hoặc js) theo tùy chọn.
     *
     * @param string $assetType 'css' hoặc 'js'
     * @param string $file      Tên file (đường dẫn tương đối từ thư mục Assets trong view)
     * @param array  $options   Mảng tùy chọn gồm:
     *                          - 'area': (mặc định 'frontend')
     *                          - 'location': (mặc định 'head' hoặc 'footer')
     */
    public static function asset($assetType, $file, $options = []) {
        self::init();
        $assetType = strtolower($assetType);
        if (!in_array($assetType, ['css', 'js'])) {
            throw new AppException("Invalid asset type: $assetType");
        }
        $area = $options['area'] ?? 'frontend';
        $location = $options['location'] ?? 'head';
        if (!in_array($location, ['head', 'footer'])) {
            $location = 'head';
        }
        if (!isset(self::$assets[$area])) {
            self::$assets[$area] = [
                'css'       => ['head' => [], 'footer' => []],
                'js'        => ['head' => [], 'footer' => []],
                'inlineCss' => ['head' => [], 'footer' => []],
                'inlineJs'  => ['head' => [], 'footer' => []],
            ];
        }
       
        self::$assets[$area][$assetType][$location][] = $file;
    }

    /**
     * Thêm nội dung inline cho asset (css hoặc js) theo tùy chọn.
     *
     * @param string $assetType 'css' hoặc 'js'
     * @param string $content   Nội dung inline cần thêm.
     * @param array  $options   Mảng tùy chọn gồm:
     *                          - 'area': (mặc định 'frontend')
     *                          - 'location': (mặc định 'head' hoặc 'footer')
     */
    public static function inline($assetType, $content, $options = []) {
        self::init();
        $assetType = strtolower($assetType);
        if (!in_array($assetType, ['css', 'js'])) {
            throw new AppException("Invalid inline asset type: $assetType");
        }
        $area = $options['area'] ?? 'frontend';
        $location = $options['location'] ?? 'head';
        if (!in_array($location, ['head', 'footer'])) {
            $location = 'head';
        }
        $key = ($assetType === 'css') ? 'inlineCss' : 'inlineJs';
        if (!isset(self::$assets[$area])) {
            self::$assets[$area] = [
                'css'       => ['head' => [], 'footer' => []],
                'js'        => ['head' => [], 'footer' => []],
                'inlineCss' => ['head' => [], 'footer' => []],
                'inlineJs'  => ['head' => [], 'footer' => []],
            ];
        }
        self::$assets[$area][$key][$location][] = $content;
    }

    /**
     * Render các asset (file và inline) tại vị trí đã chọn cho area.
     *
     * @param string $location 'head' hoặc 'footer'
     * @param string $area  'frontend' hoặc 'backend'
     * @return string HTML kết quả.
     */
    public static function renderAsset($location = 'head', $area = 'frontend') {
        self::init();
        $output = '';
        $cssFiles = self::$assets[$area]['css'][$location] ?? [];
       
        if (!empty($cssFiles)) {
            $url = self::combineAsset($cssFiles, 'css', $area);
            $output .= '<link rel="stylesheet" href="' . $url . '">' . PHP_EOL;
        }
        $jsFiles = self::$assets[$area]['js'][$location] ?? [];
        if (!empty($jsFiles)) {
            $url = self::combineAsset($jsFiles, 'js', $area);
            $output .= '<script src="' . $url . '"></script>' . PHP_EOL;
        }
        $inlineCss = self::$assets[$area]['inlineCss'][$location] ?? [];
        if (!empty($inlineCss)) {
            $output .= '<style>' . implode("\n", $inlineCss) . '</style>' . PHP_EOL;
        }
        $inlineJs = self::$assets[$area]['inlineJs'][$location] ?? [];
        if (!empty($inlineJs)) {
            $output .= '<script>' . implode("\n", $inlineJs) . '</script>' . PHP_EOL;
        }
        return $output;
    }

    protected static function combineAsset($files, $assetType, $area) {
        self::init();
        $hashedName = md5(implode('-', $files)) . ".$assetType";
        $publicFilePath = "public/assets/$area/$assetType/$hashedName";
        $publicUrl = public_url("public/assets/$area/$assetType/$hashedName");
        $debug = !empty(config('app')['debug']);
        if (file_exists($publicFilePath) && !$debug) {
            return $publicUrl;
        }
        //If not debug, recombine and minify assets
        $combinedContent = '';
        foreach ($files as $file) {
            $filePath = self::$themePath . ucfirst($area) . "/Assets/" . $file;
            if (file_exists($filePath)) {
                $content = @file_get_contents($filePath);
                if (!empty($content)) {
                    $combinedContent .= $content . "\n";
                }
                
            }
        }
        $minifiedContent = self::minifyAsset($combinedContent, $assetType);
        self::saveAsset($publicFilePath, $minifiedContent);
        return $publicUrl;
    }

    protected static function minifyAsset($content, $assetType) {
        if ($assetType === 'css') {
            $minifier = new Minify\CSS();
            $minifier->add($content);
            return $minifier->minify();
        } elseif ($assetType === 'js') {
            $minifier = new Minify\JS();
            $minifier->add($content);
            return $minifier->minify();
        }
        return $content;
    }

    protected static function saveAsset($filePath, $content) {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new AppException("Không thể tạo thư mục: {$dir}");
            }
        }
        if (strpos(realpath($dir), realpath(self::$assetsPath)) !== 0) {
            throw new AppException('Invalid path for asset file saving.');
        }
        if (@file_put_contents($filePath, $content) === false) {
            throw new AppException("Không thể ghi file vào: {$filePath}");
        }
    }    

    
    /**
     * Phương thức pagination: tạo phân trang dạng Previous/Next
     * 
     * @param string $base_url URL cơ bản cho phân trang
     * @param int $current_page Số trang hiện tại
     * @param bool $is_next Có trang tiếp theo không
     * @param array $query_params Các tham số query khác để giữ trên URL
     * @param array $custom_names Tên các biến tùy chỉnh trong query string (page, ...)
     * 
     * @return string HTML phân trang dạng Previous/Next
     */
    public static function pagination($base_url, $current_page, $is_next, $query_params = ['limit' =>  10], $custom_names = []) {
        self::init();
    
        // Tên biến mặc định cho phân trang
        $default_names = [
            'page' => 'page',
        ];
    
        // Kết hợp các biến tùy chỉnh với các tên biến mặc định
        $custom_names = array_merge($default_names, $custom_names);
    
        // Tạo query string cho các tham số khác (ngoài page)
        $query_string = http_build_query($query_params);
    
        // Loại bỏ ?page=1 nếu đang ở trang 1
        if ($current_page == 1) {
            $page_query_string = $query_string ? '?' . $query_string : ''; // Không có ? nếu không có query string khác
        } else {
            $page_query_string = '?' . $custom_names['page'] . '=' . $current_page;
            if ($query_string) {
                $page_query_string .= '&' . $query_string;
            }
        }
    
        // URL cho trang trước và trang sau
        $prev_page_url = $current_page > 2 ? $base_url . '?' . $custom_names['page'] . '=' . ($current_page - 1) . '&' . $query_string : ($query_string ? $base_url . '?' . $query_string : $base_url);
        $next_page_url = $base_url . '?' . $custom_names['page'] . '=' . ($current_page + 1) . '&' . $query_string;
    
        // Loại bỏ dấu & thừa
        $prev_page_url = rtrim($prev_page_url, '&');
        $next_page_url = rtrim($next_page_url, '&');
    
        $data = [
            'base_url'       => $base_url,
            'current_page'   => $current_page,
            'is_next'        => $is_next,
            'prev_page_url'  => $prev_page_url,
            'next_page_url'  => $next_page_url,
            'custom_names'   => $custom_names,
            'query_params'   => $query_string
        ];    

        // Sử dụng view pagination2.php để render HTML phân trang
        return self::component('Common/Pagination/pagination', $data);
    }


    /**
     * Render một input từ trường field
     * 
     * @param array $field Trường cần render
     * @param mixed $field_value Giá trị của field (có thể từ database hoặc request)
     * @param string|null $error_message Thông báo lỗi nếu có
     * @return string Chuỗi HTML của input
     * @throws \Exception
     */
    public static function input($field, $field_value = null, $error_message = null) {
        self::init(); // Đảm bảo cấu hình đã được load
        $html = '';
        // Lấy loại field và chuyển thành chữ thường
        $field_type = strtolower($field['type'] ?? 'text');
        // Thay thế khoảng trắng và ký tự không hợp lệ trong tên tệp tin
        $field_type = strtolower(preg_replace('/[^a-z0-9]+/', '_', $field_type));
        // Đường dẫn tới tệp input tương ứng
        $inputPath = self::_path_theme() . 'Common/Input/' . $field_type . '.php';
        if (!file_exists($inputPath)) {
            throw new \Exception("Input type '{$field_type}' không tồn tại tại đường dẫn '{$inputPath}'.");
        }
        if (!isset($field['field_name']) && isset($field['name'])) {
            $field['field_name'] = $field['name'];
        }
        // Xử lý trước các biến chung
        $inputData = [
            'id' => 'field_' . (isset($field['id']) ? xss_clean($field['id']) : uniqid()),
            'type' => $field_type,
            'label' => isset($field['label']) ? xss_clean($field['label']) : '',
            'name' => isset($field['field_name']) ? xss_clean($field['field_name']) : '',
            'default_value' => $field['default_value'] ?? '',
            'value' => isset($field_value) ? $field_value : ($field['default_value'] ?? ''),
            'description' => isset($field['description']) ? xss_clean($field['description']) : '',
            'required' => isset($field['required']) && $field['required'],
            'visibility' => isset($field['visibility']) && !$field['visibility'] ? false:true,
            'css_class' => isset($field['css_class']) ? xss_clean($field['css_class']) : '',
            'placeholder' => isset($field['placeholder']) ? xss_clean($field['placeholder']) : '',
            'order' => isset($field['order']) ? (int) $field['order'] : 0,
            'min' => isset($field['min']) ? (int) $field['min'] : null,
            'max' => isset($field['max']) ? (int) $field['max'] : null,
            'width_value' => isset($field['width_value']) ? (int) $field['width_value'] : 100,
            'width_unit' => isset($field['width_unit']) ? $field['width_unit'] : '%',
            'position' => isset($field['position']) ? $field['position'] : 'left',
            'options' => $field['options'] ?? [],
            'rows' => isset($field['rows']) ? (int) $field['rows'] : 3,
            'allow_types' => $field['allow_types'] ?? [],
            'max_file_size' => isset($field['max_file_size']) ? (float) $field['max_file_size'] : null,
            'multiple' => isset($field['multiple']) && $field['multiple'],
            'multiple_server' => isset($field['multiple_server']) && $field['multiple_server'],
            'servers' => isset($field['servers']) ? $field['servers'] : array(),
            'post_type_reference' => isset($field['post_type_reference']) ? xss_clean($field['post_type_reference']) : null,
            'post_status_filter' => isset($field['post_status_filter']) ? xss_clean($field['post_status_filter']) : null,
            'error_message' => isset($error_message) ? $error_message : '',
            'data' => !empty($field['data']) ? $field['data'] : [],
        ];

        // Lấy cấu hình max upload của máy chủ
        $uploadMaxFilesize = _bytes(ini_get('upload_max_filesize'));
        $postMaxSize = _bytes(ini_get('post_max_size'));
        $maxUploadSize = min($uploadMaxFilesize, $postMaxSize);
        if ($inputData['max_file_size'] === null || $inputData['max_file_size']*1024*1024 > $maxUploadSize) {
            $inputData['max_file_size'] = $maxUploadSize;
            $inputData['max_file_size'] = ceil($inputData['max_file_size'] / (1024 * 1024));
        }
        
        // Xử lý các loại input đặc biệt
        if (!empty($inputData['allow_types'])) {
            foreach ($inputData['allow_types'] as &$type) {
                if (strpos($type, '.') !== 0) {
                    $type = '.' . $type;
                }
            }
            unset($type);
        }
        if ($field_type == 'repeater') {
            $inputData['fields'] = $field['fields'] ?? [];
            $inputData['level'] = empty($field['level']) ? 1 : $field['level'];
        }
        // Bắt đầu buffer để lưu output
        ob_start();
        extract($inputData);
        require $inputPath;
        $html .= ob_get_clean();
        return $html;
    }

}