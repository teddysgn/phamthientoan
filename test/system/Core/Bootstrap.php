<?php
namespace System\Core;
use System\Libraries\Logger;
use Exception;

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Bootstrap {

    protected $routes;
    protected $uri;

    public function __construct() {
        $appConfig = config('app');
        if (!empty($appConfig['app_timezone'])) {
            date_default_timezone_set($appConfig['app_timezone']);
        }
        if (!empty($appConfig['debug']) && $appConfig['debug']) {
            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);
            error_reporting(-1);
        }else{
            ini_set('display_startup_errors', 0);
            ini_set('display_errors',0);
            error_reporting(E_ALL & ~E_NOTICE);
        }
        $this->init_uri();
        $this->routes = new Router(); // Tạo instance cho Router
        $this->loadRoutes();          // Load các route
    }

    private function init_uri(){
        // 1) Lấy URI gốc + Query gốc
        $originalUri = request_uri(); // giả sử bạn có hàm tự viết request_uri() -> trả về URI vd: "api/v1/auth/index/a!@..."
        $originalQ   = $_SERVER['QUERY_STRING'] ?? '';
        // 2) Làm sạch URI
        $uri = uri_security($originalUri);
        // 3) Nếu có query string, làm sạch thông qua sget_security()
        $sanitizedQueryString = '';
        if (!empty($originalQ)) {
            // Hàm sget_security() sẽ duyệt $_GET, gán sang mảng an toàn
            // Rồi build lại query string bằng http_build_query()
            $safeGet = sget_security(); 
            $sanitizedQueryString = http_build_query($safeGet); 
        }
        // 4) So sánh xem path + query đã thay đổi chưa
        //    - Lưu ý: $originalUri thường KHÔNG chứa '?' + query string. 
        //      Tùy cấu trúc, bạn có thể ghép lại và so sánh.
        $cleanFull = '/'.trim($uri, '/').'/';  // "api/v1/auth/index/aE/7CEa"
        if ($sanitizedQueryString !== '') {
            $cleanFull .= '?'.$sanitizedQueryString;
        }
        $cleanFull = '/'.ltrim($cleanFull, '/');
        // Nếu khác nhau, redirect 301
        if ($cleanFull !== $_SERVER['REQUEST_URI'] && $cleanFull !== $_SERVER['REQUEST_URI'].'/') {
            redirect(base_url($cleanFull));
        }
        unset($safeGet);
        unset($sanitizedQueryString);
        unset($originalUri);
        unset($originalFull);
        unset($cleanFull);
        // Tìm và xử lý route khớp với URI
        $this->uri = [
            'uri' => $uri,
            'split' => explode('/', $uri)
        ];
        unset($uri);
        if (!empty($this->uri['split'][0]) && in_array($this->uri['split'][0],LANG_LIST)) {
            array_shift($this->uri['split']); // Bỏ phần tử đầu tiên
            $this->uri['uri'] = implode('/', $this->uri['split']);
        }
        return $this->uri;
    }

    /**
     * Khởi động framework
     */
    public function run() {
        try {
            if (!isset($_SERVER['REQUEST_METHOD'])) $_SERVER['REQUEST_METHOD'] = 'GET';
            $method = $_SERVER['REQUEST_METHOD'];
            $this->dispatch($this->uri['uri'], $method);
        } catch (AppException $e) {
            $e->handle();
        } catch (\Throwable $e) { // Bắt tất cả các ngoại lệ và lỗi
            Logger::error($e->getMessage(), $e->getFile(), $e->getLine());
            http_response_code(500);
            if (!empty(config('app')['debug'])) {
                echo $e->getMessage(), $e->getFile(), $e->getLine();
            }else{
                echo "An unknown error has occurred. Lets check file logger.log! ";
            }
        }
    }

    /**
     * Load các routes từ file routes/web.php và routes/api.php
     */
    private function loadRoutes() {
        //Khoi tao or lay bien $routes tu global
        global $routes;
        // Khởi tạo đối tượng Router
        $routes = $this->routes;
        // Load all Routes config files.
        if (!empty($this->uri) && !empty($this->uri['split']) && $this->uri['split'][0] == 'api' &&  file_exists(ROOT_PATH . '/application/Routes/Api.php')  ) {
            require_once ROOT_PATH . '/application/Routes/Api.php';
        }else{
            if (file_exists(ROOT_PATH . '/application/Routes/Web.php')) {
                require_once ROOT_PATH . '/application/Routes/Web.php';
            }
        }
    }

    /**
     * Điều hướng URI đến controller và action tương ứng
     */
    private function dispatch($uri, $method) {
        $route = $this->routes->match($uri, $method);
        if (isset($route['action']) && $route['action'][0] == '_'){
            throw new AppException("404 - Router: /{$uri} ({$method}) can not access!", 404, null, 404);
        }
        
        if (!$route) {
            throw new AppException("404 - Router: /{$uri} ({$method}) not found!", 404, null, 404);
        }

        //xu ly Middleware truoc khi goi den Controller.
        $middleware = new Middleware();
        if (!empty($route['middleware'])) {
            // Thêm các middleware vào danh sách nếu có tồn tại Middleware
            foreach ($route['middleware'] as $mw) {
                $middleware->add($mw);
            }
        }

        // Thực thi middleware trước khi gọi controller
        unset($route['middleware']);//co the bo qua ham nay neu can dung middleware ben duoi
        $route['uri'] = $uri;
        $middleware->handle($route, function () use ($route) {
            // Lấy thông tin controller và phương thức từ route đã khớp
            $controllerClass = $route['controller'];
            $action = $route['action'];
            $params = $route['params'];
            
            // Kiểm tra controller có tồn tại không
            if (!class_exists($controllerClass)) {
                throw new AppException("Controller {$controllerClass} not found.", 404, null, 404);
            }
            // Khởi tạo đối tượng controller
            $controller = new $controllerClass();

            // Kiểm tra action có tồn tại không
            if (!method_exists($controller, $action)) {
                throw new AppException("Action {$action} not found in {$controllerClass} Controller.", 404, null, 404);
            }

            // Gọi controller và action với các tham số
            call_user_func_array([$controller, $action], $params);
        });
    }
}
