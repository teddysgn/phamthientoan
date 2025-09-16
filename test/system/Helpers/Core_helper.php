<?php

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Hàm load_helpers
 * Load danh sách các helpers được chỉ định
 * 
 * @param array $helpers Danh sách các helper cần load
 */
function load_helpers(array $helpers = []) {
    // Biến global để theo dõi các helper đã được nạp
    global $fast_helpers;
    // Nếu biến chưa được khởi tạo, tạo mảng để chứa các helper đã nạp
    if (!isset($fast_helpers)) {
        $fast_helpers = [];
    }

    foreach ($helpers as $helper) {
        // Kiểm tra xem helper đã được nạp trước đó hay chưa
        if (!in_array($helper, $fast_helpers)) {
            $helperPath = ROOT_PATH . '/system/Helpers/' . ucfirst($helper) . '_helper.php';
            if (file_exists($helperPath)) {
                $fast_helpers[] = $helper;
                require_once $helperPath;
            } else {
                $helperPath = ROOT_PATH . '/application/Helpers/' . ucfirst($helper) . '_helper.php';
                if (file_exists($helperPath)) {
                    $fast_helpers[] = $helper;
                    require_once $helperPath;
                } else {
                    throw new \System\Core\AppException("Helper not found: " . $helper." - ". $helperPath);
                }
            }
        }
    }
}

function DateTime() {
    return date('Y-m-d H:i:s');
}

/**
 * Hàm version_php
 * Lấy ra phiên bản PHP hiện tại
 * 
 * @return string Phiên bản PHP hiện tại
 */
function version_php() {
    return PHP_VERSION;
}

/**
 * Hàm dir_writable
 * Kiểm tra đường dẫn có phải là thư mục và có quyền ghi hay không
 * 
 * @param string $path Đường dẫn cần kiểm tra
 * @return bool True nếu là thư mục và có quyền ghi, ngược lại False
 */
function dir_writable($path) {
    return is_dir($path) && is_writable($path);
}

/**
 * Hàm server_info
 * Trả về thông tin về máy chủ hiện tại (bao gồm PHP version, server software, etc.)
 * 
 * @return array Mảng chứa thông tin về máy chủ
 */
function server_info() {
    return [
        'php_version' => version_php(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
        'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
    ];
}  

/**
 * Hàm random_string
 * Tạo ra một chuỗi ngẫu nhiên với độ dài mong muốn
 * 
 * @param int $length Độ dài của chuỗi ngẫu nhiên cần tạo
 * @return string Chuỗi ngẫu nhiên đã được tạo
 */
function random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/**
 * Hàm lấy cấu hình từ file config.php
 * 
 * @param string $key Tên của cấu hình cần lấy
 * @param mixed $default Giá trị mặc định nếu không tìm thấy cấu hình
 * @return mixed Giá trị của cấu hình hoặc giá trị mặc định
 */
function config($key = '', $file = 'Config') {
    static $config;
    $file = ucfirst($file);
    if (!$config) {
        $config = array();
    }
    if (!isset($config[$file])) {
        $config[$file] = require ROOT_PATH . '/application/Config/'.ucfirst($file).'.php';
    }
    return $config[$file][$key] ?? null;
}

function option($key, $lang = LANG) {
    static $options;
    if (!$options) {
        $options = array();
    }
    if (empty($options)) {
        $options = require ROOT_PATH . '/application/Config/Options.php';
    }
    $option = $options[$key] ?? null;
    if ($option === null){
        //Query Sql for Get Option at table fast_options
        $optionsModel = new \App\Models\OptionsModel();
        $option =  $optionsModel->getByName($key);
    }
    if(!empty($option) && isset($option['value'])) {
        if($lang !== LANG_DF && !empty($option['valuelang'])){
            $valuelang =  json_decode($option['valuelang'], true);
            if (!empty($valuelang) && !empty($valuelang[$lang])) {
                return $valuelang[$lang];
            }
        }
        return $option['value'];
    }
    return null;
}


if (!function_exists('option_set')) {
    function option_set($key, $value, $lang = '') {
        static $config;
        $file = ucfirst('options');
    
        // Nếu biến tĩnh $config chưa có, gán nó là mảng rỗng
        if (!$config) {
            $config = [];
        }
    
        // Đường dẫn file config
        $filepath = ROOT_PATH . '/application/Config/' . $file . '.php';
    
        // Nếu chưa có sẵn trong $config, ta load file config (nếu có)
        if (!isset($config[$file])) {
            if (is_file($filepath)) {
                $configData = require $filepath;
                if (is_array($configData)) {
                    $config[$file] = $configData;
                } else {
                    $config[$file] = [];
                }
            } else {
                $config[$file] = [];
            }
        }
        
        // Gán giá trị mới trong bộ nhớ dựa trên $lang
        if (in_array($lang, LANG_LIST)) {
            // Nếu có $lang, lưu vào [$key]['valuelang'][$lang]
            if (!isset($config[$file][$key])) {
                $config[$file][$key] = [];
            }
            if (!isset($config[$file][$key]['valuelang'])) {
                $config[$file][$key]['valuelang'] = [];
            }
            $config[$file][$key]['valuelang'][$lang] = $value;
        } else {
            // Nếu không có $lang, lưu vào [$key]['value']
            if (!isset($config[$file][$key])) {
                $config[$file][$key] = [];
            }
            $config[$file][$key]['value'] = $value;
        }
    
        // Hàm nội bộ (anonymous function) để chuyển mảng thành short array syntax
        $shortArrayExport = function ($data, $indentLevel = 1) use (&$shortArrayExport) {
            // Nếu không phải mảng, ta dùng var_export để xuất giá trị
            if (!is_array($data)) {
                return var_export($data, true);
            }
    
            // Chuỗi thụt lề và đóng thụt lề
            $indentStr        = str_repeat('    ', $indentLevel);
            $closingIndentStr = str_repeat('    ', $indentLevel - 1);
    
            // Xác định mảng liên kết hay không
            $isAssoc = array_keys($data) !== range(0, count($data) - 1);
    
            $lines = [];
            foreach ($data as $k => $v) {
                // Gọi đệ quy để xuất giá trị con
                $exportedValue = $shortArrayExport($v, $indentLevel + 1);
    
                // Nếu mảng liên kết, ta in cả key => value
                if ($isAssoc) {
                    $k       = var_export($k, true); // biến thành 'key'
                    $lines[] = "{$indentStr}{$k} => {$exportedValue},";
                } else {
                    // Nếu mảng thường, chỉ in value
                    $lines[] = "{$indentStr}{$exportedValue},";
                }
            }
    
            // Gộp lại thành short array syntax
            return "[\n" . implode("\n", $lines) . "\n{$closingIndentStr}]";
        };
    
        // Chuyển mảng cấu hình thành code PHP (short array)
        $arrayCode = $shortArrayExport($config[$file], 1);
    
        // Tạo nội dung file
        $content = "<?php\n\nreturn " . $arrayCode . ";\n";
    
        // Ghi vào file
        file_put_contents($filepath, $content);
    
        // Trả về giá trị vừa set
        if (in_array($lang, LANG_LIST)) {
            return $config[$file][$key]['valuelang'][$lang];
        } else {
            return $config[$file][$key]['value'];
        }
    }
    
}


/**
 * Hàm env
 * Lấy giá trị biến môi trường từ bộ nhớ cache hoặc đọc từ file .env (nếu chưa tồn tại trong cache)
 * 
 * @param string $key Tên biến môi trường cần lấy
 * @param mixed $default Giá trị mặc định nếu biến không tồn tại
 * @return mixed Giá trị của biến môi trường hoặc giá trị mặc định
 */
if(!function_exists('env')) {
    function env($key, $default = null) {
        // Sử dụng một mảng tĩnh để lưu trữ các giá trị đã được load
        static $env_cache = [];

        // Nếu giá trị đã tồn tại trong cache, trả về giá trị từ cache
        if (isset($env_cache[$key])) {
            return $env_cache[$key];
        }

        // Lấy giá trị từ biến môi trường
        $value = getenv($key);

        // Nếu không tìm thấy biến môi trường, sử dụng giá trị mặc định
        if ($value === false) {
            $env_cache[$key] = $default;
            return $default;
        }

        // Loại bỏ các ký tự không an toàn và lưu vào cache
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Xử lý các giá trị đặc biệt: true, false, null
        switch (strtolower($value)) {
            case 'true':
                $env_cache[$key] = true;
                break;
            case 'false':
                $env_cache[$key] = false;
                break;
            case 'null':
                $env_cache[$key] = null;
                break;
            default:
                $env_cache[$key] = $value;
        }

        return $env_cache[$key];
    }
}


function _bytes($size) {
    $unit = strtolower(substr($size, -1));
    $bytes = (int) $size;
    switch ($unit) {
        case 'g':
            $bytes *= 1024 * 1024 * 1024;
            break;
        case 'm':
            $bytes *= 1024 * 1024;
            break;
        case 'k':
            $bytes *= 1024;
            break;
    }
    return $bytes;
}