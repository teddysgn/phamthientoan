<?php
//echo '<h1>Demo is turn off. Contact me if want seen demo!</h1>';
define('DEBUG_TIME', false); //bat la true neu muon DEBUG
if (DEBUG_TIME) {
    // Đo lường bắt đầu ngay từ khi framework bắt đầu khởi chạy
    define('START_TIME', microtime(true));
    define('START_MEMORY', memory_get_usage());
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
// Đường dẫn đến thư mục root của ứng dụng
define('ROOT_PATH', realpath(__DIR__ . '/'));
//Khi dung ROOT_PATH noi path can co dau /, con cac DEFINE ben duoi khong can do co san / roi.
define('APP_PATH', realpath(ROOT_PATH . '/application/') . '/');
define('SYS_PATH', realpath(ROOT_PATH . '/system/')) . '/';
define('WRITE_PATH', realpath(ROOT_PATH . '/writeable/') . '/');


// Load Core_helper.php để có thể sử dụng hàm load_helpers
require_once ROOT_PATH . '/system/Helpers/Core_helper.php';
load_helpers(['uri', 'security']); // Load các helper như Uri_helper, Security_helper hoac cac helper moi nguoi muon Autoload san.
// Load init Languages
require_once ROOT_PATH . '/application/Config/Languages.php';
// Load autoload từ Composer
require_once ROOT_PATH . '/vendor/autoload.php';
// Load file Bootstrap để khởi động hệ thống
require_once ROOT_PATH . '/system/Core/Bootstrap.php';
// Khởi động Bootstrap của framework
$application = new \System\Core\Bootstrap();
$application->run();

if (DEBUG_TIME) {
    $performance = \System\Libraries\Monitor::endFramework();
    echo "Time Run Total: " . $performance['execution_time'] * 1000 . " ms!";
    echo "Total Mem: " . \System\Libraries\Monitor::formatMemorySize($performance['memory_used']);
}
