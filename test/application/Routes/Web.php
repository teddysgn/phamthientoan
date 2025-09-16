<?php





$routes->get('/', 'Frontend\HomeController::index');  
$routes->get('/portfolio', 'Frontend\HomeController::portfolio'); 
$routes->get('/detail/(:any)/(:num)', 'Frontend\HomeController::detail:$1:$2');
$routes->get('/slide', 'Frontend\HomeController::slide');
$routes->get('/resume', 'Frontend\HomeController::resume');
