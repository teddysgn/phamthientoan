<?php

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Hàm base_url
 * Trả về URL gốc của ứng dụng
 * 
 * @param string $path Đường dẫn tương đối muốn gắn vào URL gốc
 * @return string URL đầy đủ
 */
function base_url($path = '') {
	global $base_url;
	if (empty($base_url)){
		$app_url = config('app');
    	$base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
		unset($app_url);
	}
    // return rtrim($base_url, '/') . '/' . trim($path, '/').'/';
	// Tách query string ra khỏi path (nếu có)
    $parts = explode('?', trim($path, '/'), 2);
    $clean_path = $parts[0];
    $query = isset($parts[1]) ? '?' . $parts[1] : '';
    // Chỉ thêm dấu / vào cuối nếu không có query string
    if (empty($query)) {
        return rtrim($base_url, '/') . '/' . $clean_path . '/';
    } else {
        return rtrim($base_url, '/') . '/' . $clean_path . $query;
    }
}

/**
 * Hàm redirect
 * Điều hướng tới một URL khác
 * 
 * @param string $url URL cần điều hướng tới
 */
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

/**
 * Hàm sanitize_url
 * Xử lý và loại bỏ các URL có chứa đường dẫn không an toàn như '../../'
 * 
 * @param string $url URL cần kiểm tra
 * @return string URL đã được xử lý
 */
function sanitize_url($url) {
    return filter_var($url, FILTER_SANITIZE_URL);
}

/**
 * Chuyển chuỗi UTF-8 sang dạng slug (không dấu)
 * 
 * @param string $string Chuỗi UTF-8 cần chuyển
 * @return string Chuỗi đã được chuyển sang dạng slug không dấu
 */
function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false,
	);
	
	// Merge options
	$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'p', 
		'ß' => 'B', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'p', 
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'P', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'Y', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'Y', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 'c',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'R', 'Д' => 'A', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'X',
		'З' => '3', 'И' => 'I', 'Й' => 'N', 'К' => 'K', 'Л' => 'N', 'М' => 'M', 'Н' => 'H', 'О' => 'O',
		'П' => 'P', 'Р' => 'P', 'С' => 'C', 'Т' => 'T', 'У' => 'y', 'Ф' => 'F', 'Х' => 'X', 'Ц' => 'U',
		'Ч' => 'Ch', 'Ш' => 'W', 'Щ' => 'W', 'Ъ' => '', 'Ы' => 'bl', 'Ь' => 'b', 'Э' => 'e', 'Ю' => 'o',
		'Я' => 'R',
		'а' => 'a', 'б' => 'b', 'в' => 'b', 'г' => 'r', 'д' => 'A', 'е' => 'e', 'ё' => 'e', 'ж' => 'x',
		'з' => '3', 'и' => 'n', 'й' => 'n', 'к' => 'k', 'л' => 'n', 'м' => 'm', 'н' => 'h', 'о' => 'o',
		'п' => 'n', 'р' => 'p', 'с' => 'c', 'т' => 't', 'у' => 'y', 'ф' => 'o', 'х' => 'x', 'ц' => 'u',
		'ч' => 'y', 'ш' => 'w', 'щ' => 'w', 'ъ' => 'b', 'ы' => 'u', 'ь' => 'b', 'э' => 'e', 'ю' => 'o',
		'я' => 'r',
		// Ukrainian
		'Є' => 'e', 'І' => 'I', 'Ї' => 'i', 'Ґ' => 'R',
		'є' => 'e', 'і' => 'i', 'ї' => 'i', 'ґ' => 'r',
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

/**
 * Hàm parse_uri
 * Chuyển URI thành dạng phù hợp để xử lý trong router
 * 
 * @param string $uri URI cần phân tích
 * @return string URI đã được làm sạch và chuẩn hóa
 */
function parse_uri($uri) {
	if (!empty($uri)){
        return trim($uri, '/');
    }
    return $uri;
}

/**
 * Lấy URI từ request
 * 
 * @return string URI đã được xử lý
 */
function request_uri() {
    if (!isset($_SERVER['REQUEST_URI'])){
        $_SERVER['REQUEST_URI'] = '/';
    }
    $app_url = config('app');
    $app_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
    $base_path = parse_url($app_url, PHP_URL_PATH); // Lấy phần đường dẫn từ app_url
    $request_uri = $_SERVER['REQUEST_URI'];
	$request_uri = preg_replace('/(\/+)/', '/', $request_uri);
	if ($request_uri != $_SERVER['REQUEST_URI']){
		redirect($request_uri);
	}
	// Nếu request URI bắt đầu bằng base_path, loại bỏ nó
	if (strpos($request_uri, $base_path) === 0) {
		$request_uri = substr($request_uri, strlen($base_path));
	}
    // Làm sạch URI còn lại
    return parse_url($request_uri, PHP_URL_PATH);
}

	