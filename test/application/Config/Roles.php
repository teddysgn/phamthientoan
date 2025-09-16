<?php
//Setting Default Roles
return [
    'admin' => [
        'Backend\Home' => ["index"],
        'Backend\Files' => ["index", "add", "edit", "delete", 'manage'],
        'Backend\Posttype' => ["index", 'edit', 'add', 'delete', 'copy'],
        'Backend\Posts' => ["index", 'edit', 'add', 'delete', 'clone', 'copy'],
        'Backend\Terms' => ["index", 'edit', 'add', 'delete'],
        'Backend\Users' => ["index", 'edit', 'add', 'delete', 'profile'],
        'Backend\Options' => ["index", 'add', 'edit'],
        'Backend\Languages' => ["index", 'add', 'edit', 'setdefault', 'changestatus'],
        'Backend\Crawl' => ["index", 'add', 'edit', 'setdefault', 'changestatus'],
        'Api\Files' => ["index", 'add', 'edit', 'delete', 'move', 'copy', 'rename'],
    ],
    'moderator' => [
        'Backend\Home' => ["index"],
        'Backend\Files' => ["index", "add", "edit", "delete", 'manage'],
        'Backend\Posts' => ["index", 'edit', 'add', 'clone', 'delete'],
        'Backend\Terms' => ["index", 'edit', 'add'],
        'Backend\Users' => ['profile'],
    ],
    'author' => [
        'Backend\Home' => ["index"],
        'Backend\Files' => ["index", "add", "edit", "delete", 'manage'],
        'Backend\Posts' => ["index", 'edit', 'add', 'clone', 'delete'],
        'Backend\Terms' => ["index"],
        'Backend\Users' => ['profile'],
    ],
    'member' => [
        'Backend\Home' => ["index"],
        'Backend\Users' => ['profile']
    ],
    // Thêm các roles khác tùy theo yêu cầu
];