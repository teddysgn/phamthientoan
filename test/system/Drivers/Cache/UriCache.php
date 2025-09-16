<?php
namespace System\Drivers\Cache;

class UriCache {

    protected $cacheDir;
    protected $whitelist = ['page','paged','limit','sortby','sort','sc','order','orderby','id'];
    protected $compression; // 0 = no gzip, 1-9 = gzip level
    protected $headerType;
    protected $cacheLogin;

    public function __construct($compression = 0, $type = 'html') {
        // Đặt đường dẫn cache (giả sử ROOT_PATH đã define)
        $this->cacheDir = WRITE_PATH . 'cache/';
        $this->compression = $compression;
        $this->headerType = $type;
        $this->cacheLogin = false;
    }

    /**
     * Hàm get ty le nen gzip compression
     */

    public function gzip_level(){
        return $this->compression;
    }

    /**
     * Hàm gửi header phù hợp
     */
    public function headers($compress = -1) {
        $contentTypeMap = [
            'html' => 'text/html; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'text' => 'text/plain; charset=UTF-8',
            'xml' => 'application/xml; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            'js'  => 'application/javascript; charset=UTF-8',
        ];
        if ($compress == -1){
            $compress = $this->compression;
        }
        if ($compress > 0) {
            header('Content-Encoding: gzip');
        }
        $ctype = isset($contentTypeMap[$this->headerType]) ? $contentTypeMap[$this->headerType] : 'text/html; charset=UTF-8';
        header('Content-Type: ' . $ctype);
    }

    protected function getCacheFolderPath()
    {
        // 1. Lấy host và uri đã được làm sạch trong run()
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri  = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        // 2. Lấy query string đã được làm sạch trong run()
        $query_str = $_SERVER['QUERY_STRING'] ?? '';
        $args_path = '';
        if (!empty($query_str)) {
            parse_str($query_str, $params);
            $filtered = [];
            foreach ($params as $k => $v) {
                $k_lower = strtolower($k);
                // Kiểm tra trong whitelist
                if (in_array($k_lower, $this->whitelist)) {
                    // Vì đã làm sạch ở run(), chúng ta chỉ cần gán trực tiếp
                    $filtered[$k_lower] = $v;
                }
            }
            // Sắp xếp mảng theo key
            ksort($filtered);
            // Chuyển sang dạng /key/value/key2/value2...
            if (!empty($filtered)) {
                $pairs = [];
                foreach ($filtered as $fk => $fv) {
                    $fv = rawurlencode(rawurldecode($fv));
                    $fv = str_replace('%20', '+', $fv);
                    $pairs[] = $fk . '/' . $fv;
                }
                $args_path = '/' . implode('/', $pairs);
            }
        }

        // 3. Xây dựng path cache
        //    - Giữ nguyên / để chia folder (host, uri, args, ...)
        //    - Giả định các segment đã được làm sạch ở run()
        $fullPath = $this->cacheDir 
        . $host       // Ví dụ: "cms.vn"
        . '/'         // chia folder
        . trim($uri, '/') // "api/v1/auth/index/aE/7CEa"
        . $args_path  // "/sortby/username__desc"
        . '/';
        return $fullPath;
    }

    protected function getCacheFilePath()
    {
        // 1. Lay Path cua Folder chua cache
        $fullPath = $this->getCacheFolderPath();
        // 2. Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // 3. Kiểm tra HTTPS/Mobile
        $is_https  = $this->isHttps();
        $is_mobile = $this->isMobile();

        // 4. Suffix nén gzip
        $gzip_suffix = '';
        if ($this->compression > 0) {
            $gzip_suffix = '_gzip';
        }

        // 5. Tạo filename
        //    index[-mobile][-https].html[_gzip]
        $filename = 'index';
        if ($is_mobile) {
            $filename .= '-mobile';
        }
        if ($is_https) {
            $filename .= '-https';
        }
        $filename .= '.html' . $gzip_suffix;

        return $fullPath . $filename;
    }

    /**
     * set cache state (1 la alway cache ke ca co dang nhap, 0 thi: neu co dang nhap se ko cache, ko dang nhap van cu cache)
     */
    public function cacheLogin($state = 1){
        $this->cacheLogin = $state;
    }

    /**
     * Bật/tắt cache riêng cho mobile
     * 
     * @param int $state 1 = bật (tạo file .mobile-active), 0 = tắt (xóa file .mobile-active)
     * @return bool true nếu thao tác thành công, false nếu thất bại
     */
    public function cacheMobile($state = 1) {
        // 1. Get Folder Patch chua cache & mobile file.
        $fullPath = $this->getCacheFolderPath();

        // 2. Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $mobileActivePath = $fullPath . '/.mobile-active';

        if ($state) {
            // Tạo file .mobile-active
            return touch($mobileActivePath);
        } else {
            // Xóa file .mobile-active nếu tồn tại
            return !file_exists($mobileActivePath) || unlink($mobileActivePath);
        }
    }

    /**
     * Lưu cache
     */
    public function set($content) {
        if ($this->isUserLoggedIn()){
            if (!$this->cacheLogin){
                return false;
            }
        }
        $file = $this->getCacheFilePath();
        if ($this->compression > 0) {
            $content = gzencode($content, $this->compression);
        }
        @file_put_contents($file, $content) !== false;
        return $content;
    }

    /**
     * Lấy cache
     * Lưu ý: nếu file là gzip, trả về dữ liệu gzip. 
     * Người dùng cần tự set header Content-Encoding: gzip nếu muốn hiển thị đúng.
     */
    public function get() {
        if ($this->isUserLoggedIn()){
            if (!$this->cacheLogin){
                return false;
            }
        }
        $file = $this->getCacheFilePath();
        if (!file_exists($file)) {
            return null;
        }
        $data = file_get_contents($file);
        return ($data === false) ? null : $data;
    }

    /**
     * Xoá cache
     */
    public function delete() {
        $file = $this->getCacheFilePath();
        return file_exists($file) ? @unlink($file) : false;
    }

    /**
     * Kiểm tra cache tồn tại
     */
    public function has() {
        $file = $this->getCacheFilePath();
        return file_exists($file);
    }

    protected function isHttps() {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
            return true;
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        }
        return false;
    }

    // protected function isMobile() {
    //     $ua = $_SERVER['HTTP_USER_AGENT'] ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    //     $pattern = '/mobile|android|iphone|ipad|ipod|blackberry|opera mini|opera mobi|kindle|silk/i';
    //     return (bool)preg_match($pattern, $ua);
    // }

    protected function isMobile() {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $pattern = '/phone|windows\s+phone|ipod|ipad|blackberry|(?:android|bb\d+|meego|silk|googlebot).+?mobile|palm|windows\s+ce|opera\ mini|avantgo|mobilesafari|docomo|kaios/i';
        return (bool)preg_match($pattern, $ua);
    }

    
    protected function isUserLoggedIn() {
        return isset($_COOKIE['fastcms_logged']);
    }

    public function render($content) {
        $this->headers();
        echo $content;
        exit();
    }

    /**
     * Xoá toàn bộ cache
     */
    public function clear() {
        $this->rrmdir($this->cacheDir);
        return true;
    }

    protected function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    $objPath = $dir.'/'.$object;
                    if (is_dir($objPath)) {
                        $this->rrmdir($objPath);
                    } else {
                        @unlink($objPath);
                    }
                }
            }
            @rmdir($dir);
        }
    }
}
