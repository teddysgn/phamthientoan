<?php
define('LANG_LIST', ['en', 'vi', 'cn']);
define('LANG_DF', 'en');

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_path = preg_replace('#/+#', '/', $uri_path); // Thay thế nhiều dấu / liên tiếp bằng một dấu /
$uri_segments = explode('/', trim($uri_path, '/'));

// Kiểm tra xem segment đầu tiên có nằm trong danh sách ngôn ngữ không
if (!empty($uri_segments[0]) && in_array($uri_segments[0], LANG_LIST)) {
    define('LANG', $uri_segments[0]);
} else {
    if (!empty($_REQUEST['lang']) && in_array($_REQUEST['lang'], LANG_LIST)) {
        setcookie('fast_lang', $_REQUEST['lang'], time() + 86400 * 3650, '/');
        define('LANG', $_REQUEST['lang']);
    } elseif (!empty($_COOKIE['fast_lang']) && in_array($_COOKIE['fast_lang'], LANG_LIST)) {
        define('LANG', $_COOKIE['fast_lang']);
    } else {
        define('LANG', LANG_DF);
    }
}
unset($uri_path);
unset($uri_segments);
