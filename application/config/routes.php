<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| API ROUTES — mobile app
| -------------------------------------------------------------------------
*/
// Auth
$route['api/auth/login']           = 'api/Auth_api/login';
$route['api/auth/me']              = 'api/Auth_api/me';
$route['api/auth/refresh']         = 'api/Auth_api/refresh';

// Orders
$route['api/orders']               = 'api/Orders_api/index';
$route['api/orders/summary']       = 'api/Orders_api/summary';
$route['api/orders/products']      = 'api/Orders_api/products';
$route['api/orders/create']        = 'api/Orders_api/create';
$route['api/orders/options/(:any)/(:any)/(:any)'] = 'api/Orders_api/options_by_kode_ukuran_kaca/$1/$2/$3';
$route['api/orders/options/(:any)/(:any)']        = 'api/Orders_api/options_by_kode_ukuran/$1/$2';
$route['api/orders/options/(:any)']               = 'api/Orders_api/options_by_kode/$1';
$route['api/orders/hpp/(:any)/(:any)/(:any)/(:any)'] = 'api/Orders_api/get_hpp/$1/$2/$3/$4';
$route['api/orders/invoice/(:any)'] = 'api/Orders_api/invoice/$1';
$route['api/orders/detail/(:any)'] = 'api/Orders_api/detail/$1';
$route['api/orders/update-status'] = 'api/Orders_api/update_status';

// Dashboard & v1 mobile routes (alias tanpa v1 prefix)
$route['api/v1/dashboard/summary'] = 'api/Orders_api/summary';
$route['api/v1/auth/login'] = 'api/Auth_api/login';
$route['api/v1/auth/me'] = 'api/Auth_api/me';
$route['api/v1/orders'] = 'api/Orders_api/index';
$route['api/v1/orders/products'] = 'api/Orders_api/products';
$route['api/v1/orders/create'] = 'api/Orders_api/create';
$route['api/v1/orders/options/(:any)/(:any)/(:any)'] = 'api/Orders_api/options_by_kode_ukuran_kaca/$1/$2/$3';
$route['api/v1/orders/options/(:any)/(:any)'] = 'api/Orders_api/options_by_kode_ukuran/$1/$2';
$route['api/v1/orders/options/(:any)'] = 'api/Orders_api/options_by_kode/$1';
$route['api/v1/orders/hpp/(:any)/(:any)/(:any)/(:any)'] = 'api/Orders_api/get_hpp/$1/$2/$3/$4';
$route['api/v1/orders/invoice/(:any)'] = 'api/Orders_api/invoice/$1';
$route['api/v1/orders/(:any)/update-status'] = 'api/Orders_api/update_status/$1';
$route['api/v1/orders/(:any)'] = 'api/Orders_api/detail/$1';

// Inventory
$route['api/v1/inventory']                    = 'api/Inventory_api/index';
$route['api/v1/inventory/(:num)/stok']        = 'api/Inventory_api/stok/$1';

// Reports
$route['api/v1/reports/profit']               = 'api/Reports_api/profit';

// Activity Log
$route['api/activity-log']                    = 'api/Activity_log_api/index';
$route['api/v1/activity-log']                 = 'api/Activity_log_api/index';
