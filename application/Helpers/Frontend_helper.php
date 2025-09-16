<?php
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}
if (!function_exists('base_url')) {
    function base_url($path = '')
    {
        global $base_url;
        if (empty($base_url)) {
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/public/') . '/' . LANG . '/' . trim($path, '/') . '/';
    }
}

if (!function_exists('public_url')) {
    function public_url($path = '')
    {
        global $public_url;
        if (empty($public_url)) {
            $app_url = config('app');
            $public_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($public_url, '/') . '/' . trim($path, '/');
    }
}
if (!function_exists('create_slug')) {
    function create_slug($string) {
        $string = strtolower($string);
        
        // Chuyển đổi ký tự có dấu thành không dấu
        $unwanted_array = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'đ' => 'd',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y'
        ];
        $string = strtr($string, $unwanted_array);

        // Loại bỏ ký tự không mong muốn
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
        $string = trim($string, '-'); // Loại bỏ dấu '-' thừa ở đầu & cuối
        return $string;
    }
}

if (!function_exists('page_url')) {
    function page_url($slug, $posttype = '', $lang = LANG)
    {
        // Kiểm tra nếu thiếu slug hoặc posttype thì trả về rỗng
        if (empty($slug) || empty($posttype)) {
            return '';
        }

        // Chuẩn hóa đầu ra URL
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        $posttype = htmlspecialchars($posttype, ENT_QUOTES, 'UTF-8');
        $slug = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');

        // Tạo URL theo cấu trúc ./lang/posttype/cat/slug
        return sprintf('/%s/%s/%s/', $lang, $posttype, $slug);
    }
}

if (!function_exists('auth_url')) {
    function auth_url($path = '')
    {
        global $auth_url;
        if (empty($auth_url)) {
            $app_url = config('app');
            $auth_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($auth_url, '/') . '/admin/' . trim($path, '/') . '/';
    }
}


// trans table name relationshop postype
if (!function_exists('table_posttype_relationship')) {
    function table_posttype_relationship($slug)
    {
        $slug = str_replace('-', '_', $slug);
        $tableName = 'fast_posts_' . $slug . '_rel';
        return  $tableName;
    }
}


// convert number to string number format
if (!function_exists("convert_to_string_number")) {
    function convert_to_string_number($number)
    {
        if ($number >= 10 ** 9) {
            return number_format($number / 10 ** 9, 2, '.') . 'B';
        } elseif ($number >= 10 ** 6) {
            return number_format($number / 10 ** 6, 2, '.') . 'M';
        } elseif ($number >= 10 ** 3) {
            return number_format($number / 10 ** 3, 2, '.') . 'K';
        } else {
            return $number;
        }
    }
}
// get url api upload files

