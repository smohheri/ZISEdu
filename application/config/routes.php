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
$route['default_controller'] = 'auth';
$route['auth'] = 'auth/index';
$route['login'] = 'auth/index';
$route['login/process'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['auth/login'] = 'auth/login';
$route['auth/logout'] = 'auth/logout';
$route['laporan'] = 'laporan/index';
$route['laporan/export_pdf'] = 'laporan/export_pdf';
$route['pengaturan_zakat'] = 'pengaturan_zakat/index';
$route['pengaturan_zakat/create'] = 'pengaturan_zakat/create';
$route['pengaturan_zakat/store'] = 'pengaturan_zakat/store';
$route['pengaturan_zakat/edit/(:num)'] = 'pengaturan_zakat/edit/$1';
$route['pengaturan_zakat/update/(:num)'] = 'pengaturan_zakat/update/$1';
$route['pengaturan_aplikasi'] = 'pengaturan_aplikasi/index';
$route['pengaturan_aplikasi/edit'] = 'pengaturan_aplikasi/edit';
$route['pengaturan_aplikasi/update'] = 'pengaturan_aplikasi/update';
$route['muzakki'] = 'muzakki/index';
$route['muzakki/create'] = 'muzakki/create';
$route['muzakki/store'] = 'muzakki/store';
$route['muzakki/edit/(:num)'] = 'muzakki/edit/$1';
$route['muzakki/update/(:num)'] = 'muzakki/update/$1';
$route['muzakki/delete/(:num)'] = 'muzakki/delete/$1';
$route['tanggungan_fitrah'] = 'tanggungan_fitrah/index';
$route['tanggungan_fitrah/create'] = 'tanggungan_fitrah/create';
$route['tanggungan_fitrah/store'] = 'tanggungan_fitrah/store';
$route['tanggungan_fitrah/edit/(:num)'] = 'tanggungan_fitrah/edit/$1';
$route['tanggungan_fitrah/update/(:num)'] = 'tanggungan_fitrah/update/$1';
$route['tanggungan_fitrah/delete/(:num)'] = 'tanggungan_fitrah/delete/$1';
$route['mustahik'] = 'mustahik/index';
$route['mustahik/create'] = 'mustahik/create';
$route['mustahik/store'] = 'mustahik/store';
$route['mustahik/edit/(:num)'] = 'mustahik/edit/$1';
$route['mustahik/update/(:num)'] = 'mustahik/update/$1';
$route['mustahik/delete/(:num)'] = 'mustahik/delete/$1';
$route['jenis_harta_mal'] = 'jenis_harta_mal/index';
$route['jenis_harta_mal/create'] = 'jenis_harta_mal/create';
$route['jenis_harta_mal/store'] = 'jenis_harta_mal/store';
$route['jenis_harta_mal/edit/(:num)'] = 'jenis_harta_mal/edit/$1';
$route['jenis_harta_mal/update/(:num)'] = 'jenis_harta_mal/update/$1';
$route['jenis_harta_mal/delete/(:num)'] = 'jenis_harta_mal/delete/$1';
$route['zakat_fitrah'] = 'zakat_fitrah/index';
$route['zakat_fitrah/create'] = 'zakat_fitrah/create';
$route['zakat_fitrah/store'] = 'zakat_fitrah/store';
$route['zakat_fitrah/muzakki_info/(:num)'] = 'zakat_fitrah/muzakki_info/$1';
$route['zakat_fitrah/kwitansi/(:num)'] = 'zakat_fitrah/kwitansi/$1';
$route['zakat_fitrah/edit/(:num)'] = 'zakat_fitrah/edit/$1';
$route['zakat_fitrah/update/(:num)'] = 'zakat_fitrah/update/$1';
$route['zakat_fitrah/delete/(:num)'] = 'zakat_fitrah/delete/$1';
$route['zakat_mal'] = 'zakat_mal/index';
$route['zakat_mal/create'] = 'zakat_mal/create';
$route['zakat_mal/store'] = 'zakat_mal/store';
$route['zakat_mal/detail/(:num)'] = 'zakat_mal/detail/$1';
$route['zakat_mal/kwitansi/(:num)'] = 'zakat_mal/kwitansi/$1';
$route['zakat_mal/edit/(:num)'] = 'zakat_mal/edit/$1';
$route['zakat_mal/update/(:num)'] = 'zakat_mal/update/$1';
$route['zakat_mal/delete/(:num)'] = 'zakat_mal/delete/$1';
$route['penyaluran'] = 'penyaluran/index';
$route['penyaluran/create'] = 'penyaluran/create';
$route['penyaluran/store'] = 'penyaluran/store';
$route['penyaluran/detail/(:num)'] = 'penyaluran/detail/$1';
$route['penyaluran/edit/(:num)'] = 'penyaluran/edit/$1';
$route['penyaluran/update/(:num)'] = 'penyaluran/update/$1';
$route['penyaluran/delete/(:num)'] = 'penyaluran/delete/$1';
$route['infaq_shodaqoh'] = 'infaq_shodaqoh/index';
$route['infaq_shodaqoh/create'] = 'infaq_shodaqoh/create';
$route['infaq_shodaqoh/store'] = 'infaq_shodaqoh/store';
$route['infaq_shodaqoh/edit/(:num)'] = 'infaq_shodaqoh/edit/$1';
$route['infaq_shodaqoh/update/(:num)'] = 'infaq_shodaqoh/update/$1';
$route['infaq_shodaqoh/delete/(:num)'] = 'infaq_shodaqoh/delete/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
