<?php
/* 
* Application Specific Custom Config Define Here and Use in Application
* $this->config->item('item_key_name');
* Load this file at autoload.php 
* $autoload['config'] = array('app_config');
*/

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Admin Controller, Site Controller and View Directory Config
*/
$config['site_view_dir'] = 'site/'; //application/views/site/
$config['admin_view_dir'] = 'admin/'; //application/views/admin/

/*
 * Email Config Application Team/Admin
 */
$config['app_admin_email'] = 'webuidevs@gmail.com';
$config['app_admin_email_cc'] = '';
$config['app_admin_email_bcc'] = '';
$config['app_admin_email_name'] = 'CI App';
$config['app_email_subject_prefix'] = 'CI App ';


/*
 * Template Config
 */
$config['app_company_product'] = 'CI App';
$config['app_logo_name_login'] = '<b>Admin</b> Dashboard';
$config['app_logo_name_admin_dashboard'] = 'Admin';
$config['app_logo_name_dashboard'] = 'App Title';
$config['app_logo_name_dashboard_xs'] = '<b>U</b>Aadmin';

$config['app_html_title'] = 'CI App';
$config['app_admin_html_title'] = 'CI App';

$config['app_meta_keywords'] = 'CI Project';
$config['app_meta_description'] = 'CI Project';
$config['app_meta_author'] = 'Saikat Mahapatra webuidevs@gmail.com';


$config['app_copy_right'] = 'Copyright '.date('Y').' &copy; CI App';
$config['app_admin_copy_right'] = 'Copyright &copy; '.date('Y').' <a href="#">CI App</a>. All rights reserved.';
$config['app_version'] = 'App. Ver. '.CI_VERSION.'.3';
