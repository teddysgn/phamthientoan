<?php

return [
    // Cấu hình ứng dụng
    'app' => [
        'debug' => false, //Khai bao true neu muon dua ve che do development
        'app_url' => 'https://phamthientoan.com/',
        'app_name' => 'Portfolio Pham Thien Toan',
        'app_timezone' => 'Asia/Ho_Chi_Minh'
    ],
    'files' => [
        'path' => 'writeable/uploads',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'txt', 'rar', 'zip', 'iso', 'mp3', 'wav', 'mkv', 'mp4', 'srt'], // Loại tệp cho phép
        'max_file_size' => 10485760, // Giới hạn kích thước tệp tối đa: 10MB (tính theo bytes)
        'images_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico', 'svg'], // Các loại tệp được hỗ trợ hiển thị thumbnail
        'max_file_count' => 10, // Giới hạn số lượng tệp được tải lên trong một lần
        'limit' => 40, // Giới hạn số lượng tệp tin trên mỗi trang phân trang
    ],
    'security' => [
        'app_id' => '09038383081',
        'app_secret' => 'phamthientoan@1981'
    ],
    'db' => [
        // Cấu hình cơ sở dữ liệu
        'db_driver' => 'mysql',
        'db_host' => '',
        'db_port' => 3306,
        'db_username' => '',
        'db_password' => '',
        'db_database' => '',
        'db_charset'  => 'utf8mb4',
        'db_collate'  => 'utf8mb4_unicode_ci',
    ],
    'email' => [
        'mail_mailer' => 'smtp',
        'mail_host' => 'smtp.gmail.com',
        'mail_port' => 587,
        'mail_username' => '',
        'mail_password' => '',
        'mail_encryption' => 'tls',
        'mail_charset'  =>  'UTF-8',
        'mail_from_address' => '',
        'mail_from_name' => 'MovieCenter',
    ],
    'cache' => [
        'cache_driver' => 'redis',
        'cache_host' => '127.0.0.1',
        'cache_port' => 6379,
        'cache_username' => '',
        'cache_password' => '',
        'cache_database' => 0,
    ],
    'theme' => [
        'theme_path' => 'application/Views',
        'theme_name' => 'default'
    ]

];
