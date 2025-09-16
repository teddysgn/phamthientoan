<?php
// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Hàm xss_clean
 * Lọc các đầu vào để tránh XSS (Cross-Site Scripting)
 * 
 * @param string $data Dữ liệu cần lọc
 * @return string Dữ liệu đã được làm sạch
 */
function xss_clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Hàm clean_input
 * Làm sạch dữ liệu đầu vào để tránh các lỗ hổng bảo mật như XSS
 * 
 * @param mixed $data Dữ liệu cần làm sạch (chuỗi hoặc mảng)
 * @return mixed Dữ liệu đã được làm sạch
 */
function clean_input($data) {
    if (is_array($data)) {
        // Nếu $data là một mảng, áp dụng clean_input cho từng phần tử
        foreach ($data as $key => $value) {
            $data[$key] = clean_input($value);
        }
        return $data;
    } else {
        // Nếu $data là một chuỗi, làm sạch như bình thường
        // Loại bỏ khoảng trắng ở đầu và cuối
        $data = trim($data);
        // Xóa các ký tự backslashes \
        $data = stripslashes($data);
        // Loại bỏ các ký tự không mong muốn như ', "
        $data = str_replace(["'", '"'], '', $data);
        // Chuyển đổi các ký tự đặc biệt thành các thực thể HTML
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        // Loại bỏ các ký tự không phải chữ cái, số, dấu cách và dấu câu cơ bản
        $data = preg_replace('/[^\w\s\p{P}]/u', '', $data);
        //$data = preg_replace('/[^\p{L}\s_-]/u', '', $data);
        return $data;
    }
}

// Hàm kiểm tra tên bảng hợp lệ
function isTableName($name) {
    // Tên bảng phải bắt đầu bằng chữ cái hoặc dấu gạch dưới, và chỉ chứa chữ cái, số, hoặc gạch dưới
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name);
}

// Hàm kiểm tra tên cột hợp lệ
function isColumnName($name) {
    // Tên cột phải bắt đầu bằng chữ cái hoặc dấu gạch dưới, và chỉ chứa chữ cái, số, hoặc gạch dưới
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name);
}

// Hàm kiểm tra tính hợp lệ của tên khóa cho Redis key or GET or POST field .v.v.v.
function isKeyName($name) {
    // Tên khóa phải bắt đầu bằng chữ cái hoặc dấu gạch dưới và chỉ chứa chữ cái, số, dấu gạch dưới, dấu chấm, dấu gạch ngang
    return preg_match('/^[A-Za-z0-9_.-:]*$/', $name);
}

/**
 * Hàm lấy dữ liệu từ $_GET một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_GET($key, $default = null) {
    if (isset($_GET[$key])) {
        return clean_input($_GET[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_GET hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_GET($key) {
    return isset($_GET[$key]);
}

/**
 * Hàm lấy dữ liệu từ $_POST một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_POST($key, $default = null) {
    if (isset($_POST[$key])) {
        return clean_input($_POST[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_POST hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_POST($key) {
    return isset($_POST[$key]);
}

/**
 * Hàm lấy dữ liệu từ $_REQUEST một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_REQUEST($key, $default = null) {
    if (isset($_REQUEST[$key])) {
        return clean_input($_REQUEST[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_REQUEST hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_REQUEST($key) {
    return isset($_REQUEST[$key]);
}

/**
 * Hàm uri_security
 * Làm sạch và bảo vệ URI tránh các cuộc tấn công XSS, SQL Injection
 * 
 * @param string $uri Dữ liệu URI cần làm sạch
 * @return string URI đã được làm sạch
 */
// function uri_security($uri) {
//     // Loại bỏ các ký tự không hợp lệ từ URI
//     $uri = filter_var($uri, FILTER_SANITIZE_URL);
//     $uri = preg_replace('#/+#', '/', $uri); // Loại bỏ các dấu // liên tiếp
//     $uri = preg_replace('#\.\.+#', '', $uri); // Thay thế .. hoặc ... bằng index
//     // Áp dụng thêm các bước làm sạch XSS
//     return xss_clean($uri);
// }

/**
 * Làm sạch URI (path) – loại bỏ ký tự không mong muốn, giữ lại a-z, A-Z, 0-9, -, _
 * Đồng thời vẫn **bảo toàn** các dấu slash `/` để chia thư mục/bậc route.
 */
function uri_security($uri) {
    // B1: Giải mã %xx (nếu có)
    if (!empty($uri)){
        $uri = rawurldecode($uri);
        // B2: Loại bỏ các ký tự // liên tiếp -> chỉ còn 1
        $uri = preg_replace('#/+#', '/', $uri);
        // B3: Tránh '..' hoặc '...' => bảo mật directory traversal
        $uri = str_replace(['..', '...'], '', $uri);
        // B4: Tách theo slash, sanitize từng “segment”
        $parts = explode('/', $uri);
        $cleanParts = [];
        foreach ($parts as $p) {
            // Chỉ cho phép [A-Za-z0-9_-], bạn có thể mở rộng tùy ý (vd: thêm . hoặc ~)
            $p = preg_replace('/[^A-Za-z0-9_\-.]/', '', $p);
            $p = trim($p, '.');
            // Nếu segment không rỗng sau khi lọc thì ta giữ lại
            if ($p !== '') {
                $cleanParts[] = $p;
            }
        }
        // B5: Gộp lại thành URI mới
        $cleanUri = implode('/', $cleanParts);
        // B6: XSS clean (nếu có hàm xss_clean)
        $cleanUri = xss_clean($cleanUri);
        return $cleanUri;
    }
    return '';
}

function sget_security() {
    foreach ($_GET as $key => $value) {
        // Hạ key về lowercase:
        $lowerKey = mb_strtolower($key, 'UTF-8');
        // Loại bỏ ký tự không thuộc [\w\s\p{P}] (giữ lại chữ, số, gạch dưới, khoảng trắng, dấu câu)
        // Lưu ý: \w trong PHP regex nghĩa là [A-Za-z0-9_].
        // \p{P} là punctuation (bao gồm .,!? v.v.), còn \s là khoảng trắng.
        $safeKey  = preg_replace('/[^A-Za-z0-9_\-]/', '', rawurldecode($lowerKey));
        if (empty($safeKey)) {
            continue;
        }
        // Value tương tự:
        $safeValue = preg_replace('/[^A-Za-z0-9\p{L}\s\/_-]/u', '', rawurldecode($value) );
        //$safeValue = rawurlencode($safeValue);
        $safeValue = $safeValue;
        if ($safeValue === null) {
            $safeValue = '';
        }
        // Nếu key đã thay đổi, ta xóa key cũ và gán sang key mới
        if ($safeKey !== $key) {
            unset($_GET[$key]);
        }

        $_GET[$safeKey] = $safeValue;
    }
    return $_GET;
}