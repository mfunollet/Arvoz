<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route = array();


// FIX: default_controller do module se define assim:
$route['pcr'] = "people"; 

$route['pcr/(:any)'] = '$1';
$route['pcr/(:any)/(:any)'] = '$1/$2';
$route['pcr/(:any)/(:any)/(:any)'] = '$1/$2/$3';