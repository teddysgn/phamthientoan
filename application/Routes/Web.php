<?php
$routes->get('/', 'Frontend\HomeController::index');  
$routes->get('/portfolio', 'Frontend\HomeController::portfolio'); 
$routes->get('/detail/(:any)/(:num)', 'Frontend\HomeController::detail:$1:$2');
$routes->get('/slide', 'Frontend\HomeController::slide');
$routes->get('/resume', 'Frontend\HomeController::resume');


$routes->get('/admin', 'Backend\AdminController::index', [\App\Middleware\AuthMiddleware::class]);  

$routes->get('/admin/(:any)/paged/(:num)', 'Backend\$1Controller::paged:$1:$2', [\App\Middleware\AuthMiddleware::class]); 
$routes->get('/admin/(:any)/form/(:any)', 'Backend\$1Controller::form:$1:$2', [\App\Middleware\AuthMiddleware::class]); 
$routes->get('/admin/(:any)/delete/(:any)', 'Backend\$1Controller::delete:$1:$2', [\App\Middleware\AuthMiddleware::class]); 
$routes->post('/admin/(:any)/save', 'Backend\$1Controller::save::$1', [\App\Middleware\AuthMiddleware::class]); 
$routes->get('/admin/(:any)/status/(:any)/(:num)', 'Backend\$1Controller::status:$1:$2:$3', [\App\Middleware\AuthMiddleware::class]); 
$routes->get('/admin/(:any)', 'Backend\$1Controller::$1', [\App\Middleware\AuthMiddleware::class]); 


$routes->get('user/login/', 'Frontend\AuthController::login');
$routes->post('user/login/', 'Frontend\AuthController::login');

$routes->get('user/logout/', 'Frontend\AuthController::logout');
