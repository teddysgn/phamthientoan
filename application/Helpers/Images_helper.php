<?php
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}
if (!function_exists('addSizeToPath')) {
    // Hàm thêm kích thước vào trước phần đuôi file
    function addSizeToPath($path, $size)
    {
        $dotIndex = strrpos($path, '.'); //strrpos # strpos . No find dau . cuoi cung
        if ($dotIndex !== false) {
            $filePathWithoutExt = substr($path, 0, $dotIndex);
            $fileExt = substr($path, $dotIndex);
            return $filePathWithoutExt . '_' . $size . $fileExt;
        }
        return $path; // Trả về đường dẫn gốc nếu không tìm thấy đuôi file
    }
}
if (!function_exists('img_square')) {
    // Hàm để xử lý đường dẫn ảnh vuông
    function img_square($item)
    {
        if (is_object($item)) {
            $item = (array)$item;
        }
        if (isset($item['path']) && !empty($item['path'])) {
            if (isset($item['resize']) && strpos($item['resize'], '150x150') !== false) {
                return addSizeToPath($item['path'], '150x150');
            }
            return $item['path']; // Trả về đường dẫn gốc nếu không cần thêm
        }
        return '/uploads/assets/150x150.webp'; // Đường dẫn mặc định
    }
}
if (!function_exists('img_vertical')) {
    // Hàm để xử lý đường dẫn ảnh dọc
    function img_vertical($item)
    {
        if (is_object($item)) {
            $item = (array)$item;
        }
        if (isset($item['path']) && !empty($item['path'])) {
            if (isset($item['resize']) && strpos($item['resize'], '333x500') !== false) {
                return addSizeToPath($item['path'], '333x500');
            }
            return $item['path']; // Trả về đường dẫn gốc nếu không cần thêm
        }
        return '/uploads/assets/333x500.webp'; // Đường dẫn mặc định
    }
}

// Ví dụ sử dụng
//$itemVuong = [ 'path' => '/uploads/images/sample.jpg', 'resize' => '150x150' ];
//echo linkImgVuong($itemVuong); // Kết quả: '/uploads/images/sample_150x150.jpg'

//$itemDoc = (object) [ 'path' => '/uploads/images/sample.jpg', 'resize' => '333x500' ];
//echo linkImgDoc($itemDoc); // Kết quả: '/uploads/images/sample_333x500.jpg'

//$itemInvalid = [ 'path' => '', 'resize' => '' ];
//echo linkImgVuong($itemInvalid); // Kết quả: '/uploads/assets/150x150.webp'
//echo linkImgDoc($itemInvalid); // Kết quả: '/uploads/assets/333x500.webp'
