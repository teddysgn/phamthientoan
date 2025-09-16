<?php
namespace System\Core;
use System\Core\Middleware;
// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Router {

    private $routes = [];

    /**
     * Đăng ký route GET
     */
    public function get($uri, $controller, $middleware = []) {
        $this->addRoute('GET', $uri, $controller, $middleware);
    }

    /**
     * Đăng ký route POST
     */
    public function post($uri, $controller, $middleware = []) {
        $this->addRoute('POST', $uri, $controller, $middleware);
    }

    /**
     * Đăng ký route PUT
     */
    public function put($uri, $controller, $middleware = []) {
        $this->addRoute('PUT', $uri, $controller, $middleware);
    }

    /**
     * Đăng ký route DELETE
     */
    public function delete($uri, $controller, $middleware = []) {
        $this->addRoute('DELETE', $uri, $controller, $middleware);
    }

    /**
     * Thêm route vào danh sách routes
     */
    private function addRoute($method, $uri, $controller, $middleware = []) {
        $this->routes[$method][parse_uri($uri)] = [
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }

    /**
     * Khớp URI với route và trả về thông tin controller, action, params, và middleware
     */
    public function match($uri, $method) {
        $uri = parse_uri($uri);

        // Kiểm tra từng route đã đăng ký để tìm khớp
        foreach ($this->routes[$method] as $routeUri => $route) {
            if (preg_match($this->convertToRegex($routeUri), $uri, $matches)) {
                array_shift($matches); // Loại bỏ khớp toàn bộ regex
                $controllerAction = $this->getControllerAction($route['controller'], $matches); 
                $controllerAction['middleware'] = $route['middleware'];
                return [
                    'controller' => $controllerAction[0],
                    'action' => $controllerAction[1],
                    'params' => $controllerAction[2],
                    'middleware' => $route['middleware'] // Trả về middleware nếu có
                ];
            }
        }

        // Kiểm tra nếu chỉ khớp controller (ví dụ: /admin hoặc /admin/index)
        $controller = explode('::', $route['controller'])[0];
        if ($this->isControllerRoute($routeUri, $uri, $controller)) {
            $controllerAction = $this->getControllerAction($route['controller'], []);
            return [
                'controller' => $controllerAction[0],
                'action' => $controllerAction[1] ?? 'index', // Mặc định action là 'index'
                'params' => [],
                'middleware' => $route['middleware'] // Trả về middleware nếu có
            ];
        }
        // Nếu không có route nào khớp, kiểm tra cấu trúc tự động "/Controller/Function"
        return $this->autoRoute($uri);
    }

    /**
     * Kiểm tra xem URL có khớp với controller và phương thức không (wildcard)
     */
    private function isControllerRoute($routeUri, $uri, $controller) {
        $controller = str_replace('\\', '/', strtolower($controller));
        $routeUri = str_replace('\\', '/', trim(parse_uri($routeUri), '/'));
        $uri = str_replace('\\', '/', $uri);
        // Kiểm tra nếu URI bắt đầu với routeUri (controller) và không có action cụ thể
        return strpos($uri, $routeUri) === 0 && strpos($uri, $controller) !== false;
    }
    
    private function getControllerAction($controllerString, $params) {
        // Bước 1: Replace tất cả các $n trong controllerString bằng giá trị tương ứng từ $params
        preg_match_all('/\$(\d+)/', $controllerString, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $paramIndex) {
                $index = intval($paramIndex) - 1;
                if (isset($params[$index])) {
                    $value = $params[$index];
                    // Nếu giá trị chứa dấu "/" thì chỉ lấy phần đầu tiên
                    if (strpos($value, '/') !== false) {
                        $value = explode('/', $value)[0];
                    }
                    $value = str_replace('.', '', $value);
                    // Nếu đang replace trong phần controller thì uppercase ký tự đầu
                    if (strpos($controllerString, '::') > strpos($controllerString, '$' . $paramIndex)) {
                        $value = ucfirst($value);
                    }
                    $controllerString = str_replace('$' . $paramIndex, $value, $controllerString);
                }
            }
        }
    
        // Bước 2: Parse chuỗi controllerString đã được replace để lấy controller và action
        list($controller, $actionString) = explode('::', $controllerString);
        
        // Tách action và các tham số của action (nếu có)
        $actionParts = explode(':', $actionString);
        $action = array_shift($actionParts); // Lấy phần tử đầu tiên làm action name
        
        // Các phần còn lại trong $actionParts là tham số của action
        return [
            "App\\Controllers\\{$controller}", 
            $action,
            $actionParts // Trả về các tham số còn lại của action (nếu có)
        ];
    }

    /**
     * Chuyển route thành regex để khớp với URI
     */
    private function convertToRegex($routeUri) {
        // Thay thế các mẫu đặc biệt giống như CodeIgniter
        $routeUri = str_replace('(:any)', '(.+)', $routeUri);
        $routeUri = str_replace('(:segment)', '([^/]+)', $routeUri);
        $routeUri = str_replace('(:num)', '(\d+)', $routeUri);
        $routeUri = str_replace('(:alpha)', '([a-zA-Z]+)', $routeUri);
        $routeUri = str_replace('(:alphadash)', '([a-zA-Z\-]+)', $routeUri); // Khớp chữ cái và dấu gạch ngang (-)
        $routeUri = str_replace('(:alphanum)', '([a-zA-Z0-9]+)', $routeUri);
        $routeUri = str_replace('(:alphanumdash)', '([a-zA-Z0-9\-]+)', $routeUri); // Khớp chữ cái, số và dấu gạch ngang (-)
    
        // Hỗ trợ cho các biểu thức chính quy tùy chỉnh
        $routeUri = preg_replace('#\(([a-zA-Z0-9_\-\.\[\]\+\*]+)\)#', '($1)', $routeUri);
    
        // Đưa route vào dạng biểu thức chính quy hoàn chỉnh
        return "#^" . $routeUri . "$#";
    }    

    /**
     * Tự động route từ URI thành Controller và Function
     * URI dạng /TenController/TenFunction/params...
     */
    private function autoRoute($uri) {
        $segments = explode('/', trim($uri, '/'));

        $controller = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
        $action = isset($segments[1]) ? $segments[1] : 'index';
        $params = array_slice($segments, 2);

        $controllerClass = "App\\Controllers\\{$controller}";
        if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
            return [
                'controller' => $controllerClass,
                'action' => $action,
                'params' => $params,
                'middleware' => [] // Auto route không có middleware
            ];
        }
        return false; // Nếu không tìm thấy controller/action
    }
}
