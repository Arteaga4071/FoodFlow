<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth
$route['login'] = 'auth/index';
$route['logout'] = 'auth/logout';

// Menú
$route['menu'] = 'menu/index';
$route['menu/nuevo'] = 'menu/nuevo';
$route['menu/editar/(:num)'] = 'menu/editar/$1';
$route['menu/eliminar/(:num)'] = 'menu/eliminar/$1';

// Mesas
$route['mesas'] = 'mesas/index';
$route['mesas/cambiar_estado/(:num)'] = 'mesas/cambiar_estado/$1';

// Pedidos
$route['pedidos'] = 'pedidos/index';
$route['pedidos/nuevo/(:num)'] = 'pedidos/nuevo/$1';
$route['pedidos/historial'] = 'pedidos/historial';

// Cocina
$route['cocina'] = 'cocina/index';

// Reportes
$route['reportes'] = 'reportes/index';
