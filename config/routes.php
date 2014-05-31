<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
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
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */
$route['default_controller'] = "powerdt/site"; // Aqui é o único local onde temos que escrever o nome do modulo
$route['404_override'] = '';

// FIX: Assim renomeamos o modulo pcr na URL
$modules_path = APPPATH . 'modules/';
foreach (scandir($modules_path) as $module) {
    if ($module != '.' && $module != '..' && $module != '.svn' && substr($module,0,1) != '#' && $module != 'welcome' && is_dir($modules_path . $module)) {
        foreach (scandir($modules_path . $module . '/controllers/') as $controller) {
            if (!is_dir($modules_path . $module . '/controllers/' . $controller)) {
                $controller = substr($controller, 0, -4);
                if($controller == 'home'){
                    $route[$controller] = $route['default_controller'];
                    $route[$controller . '/(:any)'] = $route['default_controller'] . "/$1";
                    $route[$controller . '/(:any)/(:any)'] = $route['default_controller'] . "/$1/$2";
                    $route[$controller . '/(:any)/(:any)/(:any)'] = $route['default_controller'] . "/$1/$2/$3";
                }else{
                    $route[$controller] = $module . "/" . $controller;
                    $route[$controller . '/(:any)'] = $module . "/" . $controller . "/$1";
                    $route[$controller . '/(:any)/(:any)'] = $module . "/$1/$2";
                    $route[$controller . '/(:any)/(:any)/(:any)'] = $module . "/$1/$2/$3";
                }
            }
        }
    }
}

//print_r($route);exit;

/*
    [home] => pcr/home
    [home/(:any)] => pcr/home/$1
    [home/(:any)/(:any)] => pcr/$1/$2
    [home/(:any)/(:any)/(:any)] => pcr/$1/$2/$3*/

/*
$route['default_controller'] = "home";
$route['404_override'] = '';

//Person
$route['person/(:any)'] = "pcr/person/$1";
$route['people'] = 'pcr/person/show';
$route['person/create'] = "pcr/person/create";
$route['person/create_success'] = "pcr/person/create_success";
$route['person/view/(\d+)'] = "pcr/person/view/$1";
$route['person/edit/(\d+)'] = "pcr/person/edit/$1";
$route['person/update'] = "pcr/person/update";
$route['person/delete/(\d+)'] = "pcr/person/delete/$1";
$route['person/show'] = "pcr/person/show";
$route['person/auto_complete'] = "pcr/person/auto_complete";

$route['person/admin_address/(\d+)'] = "pcr/person/admin_address/$1";
$route['person/admin_emails/(\d+)'] = "pcr/person/admin_emails/$1";
$route['person/admin_phones/(\d+)'] = "pcr/person/admin_phones/$1";
$route['person/admin_sites/(\d+)'] = "pcr/person/admin_sites/$1";

$route['person/add_address/(\d+)'] = "pcr/person/add_address/$1";
$route['person/add_email/(\d+)'] = "pcr/person/add_email/$1";
$route['person/add_phone/(\d+)'] = "pcr/person/add_phone/$1";
$route['person/add_site/(\d+)'] = "pcr/person/add_site/$1";

$route['person/edit_address/(\d+)'] = "pcr/person/edit_address/$1";
$route['person/edit_emails/(\d+)'] = "pcr/person/edit_emails/$1";
$route['person/edit_phones/(\d+)'] = "pcr/person/edit_phone/$1";
$route['person/edit_sites/(\d+)'] = "pcr/person/edit_sites/$1";

//Company
$route['company/(:any)'] = "pcr/company/$1";
$route['companies'] = 'pcr/company/show';
$route['company/create'] = "pcr/company/create";
$route['company/view/(\d+)'] = "pcr/company/view/$1";
$route['company/edit/(\d+)'] = "pcr/company/edit/$1";
$route['company/edit_sites/(\d+)'] = "pcr/company/edit_sites/$1";
$route['company/update'] = "pcr/company/update";
$route['company/delete/(\d+)'] = "pcr/company/delete/$1";
$route['company/show'] = "pcr/company/show";

$route['company/admin_address/(\d+)'] = "pcr/company/admin_address/$1";
$route['company/admin_emails/(\d+)'] = "pcr/company/admin_emails/$1";
$route['company/admin_phones/(\d+)'] = "pcr/company/admin_phones/$1";
$route['company/admin_sites/(\d+)'] = "pcr/company/admin_sites/$1";

$route['company/add_address/(\d+)'] = "pcr/company/add_address/$1";
$route['company/add_email/(\d+)'] = "pcr/company/add_email/$1";
$route['company/add_phone/(\d+)'] = "pcr/company/add_phone/$1";
$route['company/add_site/(\d+)'] = "pcr/company/add_site/$1";

$route['company/edit_address/(\d+)'] = "pcr/company/edit_address/$1";
$route['company/edit_emails/(\d+)'] = "pcr/company/edit_emails/$1";
$route['company/edit_phones/(\d+)'] = "pcr/company/edit_phone/$1";
$route['company/edit_sites/(\d+)'] = "pcr/company/edit_sites/$1";

//Site
$route['sites'] = "pcr/site";
$route['site/show'] = "pcr/site/show";
$route['site/create'] = "pcr/site/create";
$route['site/edit/(\d+)'] = "pcr/site/edit/$1";
$route['site/update'] = "pcr/site/update";
$route['site/delete/(\d+)'] = "pcr/site/delete/$1";
$route['site/ajax_load_edit'] = "pcr/site/ajax_load_edit";
$route['site/edit_profile/(\d+)'] = "pcr/site/edit_profile/$1";
$route['site/ajax_delete'] = "pcr/site/ajax_delete";

//Address
$route['addresses'] = "pcr/address";
$route['address/show'] = "pcr/address/show";
$route['address/create'] = "pcr/address/create";
$route['address/edit/(\d+)'] = "pcr/address/edit/$1";
$route['address/update'] = "pcr/address/update";
$route['address/delete/(\d+)'] = "pcr/address/delete/$1";
$route['address/ajax_load_edit'] = "pcr/address/ajax_load_edit";
$route['address/ajax_delete'] = "pcr/address/ajax_delete";
$route['address/edit_profile/(\d+)'] = "pcr/address/edit_profile/$1";
$route['address/get_city'] = "pcr/address/get_city";

//Email
$route['emails'] = 'pcr/email/show';
$route['email/create'] = "pcr/email/create";
$route['email/view/(\d+)'] = "pcr/email/view/$1";
$route['email/edit/(\d+)'] = "pcr/email/edit/$1";
$route['email/update'] = "pcr/email/update";
$route['email/delete/(\d+)'] = "pcr/email/delete/$1";
$route['email/show'] = "pcr/email/show";
$route['email/edit_profile/(\d+)'] = "pcr/email/edit_profile/$1";
$route['email/ajax_delete'] = "pcr/email/ajax_delete";
$route['email/ajax_load_edit'] = "pcr/email/ajax_load_edit";

//Type_email
$route['type_email/create'] = "pcr/type_email/create";
$route['type_email/view/(\d+)'] = "pcr/type_email/view/$1";
$route['type_email/edit/(\d+)'] = "pcr/type_email/edit/$1";
$route['type_email/update'] = "pcr/type_email/update";
$route['type_email/delete/(\d+)'] = "pcr/type_email/delete/$1";
$route['type_emails'] = 'pcr/type_email/show';
$route['type_email/show'] = "pcr/type_email/show";

//Phone
$route['phones'] = 'pcr/phone/show';
$route['phone/create'] = "pcr/phone/create";
$route['phone/view/(\d+)'] = "pcr/phone/view/$1";
$route['phone/edit/(\d+)'] = "pcr/phone/edit/$1";
$route['phone/update'] = "pcr/phone/update";
$route['phone/delete/(\d+)'] = "pcr/phone/delete/$1";
$route['phone/show'] = "pcr/phone/show";
$route['phone/ajax_load_edit'] = "pcr/phone/ajax_load_edit";
$route['phone/ajax_delete'] = "pcr/phone/ajax_delete";
$route['phone/edit_profile/(\d+)'] = "pcr/phone/edit_profile/$1";

//Type_phone
$route['type_phone/create'] = "pcr/type_phone/create";
$route['type_phone/view/(\d+)'] = "pcr/type_phone/view/$1";
$route['type_phone/edit/(\d+)'] = "pcr/type_phone/edit/$1";
$route['type_phone/update'] = "pcr/type_phone/update";
$route['type_phone/delete/(\d+)'] = "pcr/type_phone/delete/$1";
$route['type_phones'] = 'pcr/type_phone/show';
$route['type_phone/show'] = "pcr/type_phone/show";

//P_and_p
$route['p_and_ps'] = "pcr/p_and_p";
$route['p_and_p/show'] = "pcr/p_and_p/show";
$route['p_and_p/create'] = "pcr/p_and_p/create";
$route['p_and_p/edit/(\d+)'] = "pcr/p_and_p/edit/$1";
$route['p_and_p/update'] = "pcr/p_and_p/update";
$route['p_and_p/delete/(\d+)'] = "pcr/p_and_p/delete/$1";

//auth
$route['auth/login'] = "pcr/auth/login";
$route['auth/logout'] = "pcr/auth/logout";

//role
$route['roles'] = "pcr/role/show";
$route['role/create'] = "pcr/role/create";
$route['role/edit/(\d+)'] = "pcr/role/edit/$1";
$route['role/delete/(\d+)'] = "pcr/role/delete/$1";
$route['role/view/(\d+)'] = "pcr/role/view/$1";

//resource
$route['resource/init_resource'] = "pcr/resource/init_resource";

//role_has_resource
$route['role_has_resource/change_permissions'] = "pcr/role_has_resource/change_permissions";
$route['role_has_resource/change_permissions/(\d+)'] = "pcr/role_has_resource/change_permissions/$1";
$route['role_has_resource/save_permissions'] = "pcr/role_has_resource/save_permissions";


//////////
// ARVOZ//
//////////

//selection_process
$route['selection_processes'] = "selection_process/show";

//user
$route['users'] = "user/show";

//step_type
$route['step_types'] = "step_type/show";
*/

/* End of file routes.php */
///* Location: ./application/config/routes.php */