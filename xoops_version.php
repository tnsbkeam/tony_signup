<?php
$modversion = [];

//---模組基本資訊---//
$modversion['name'] = '活動報名';
$modversion['version'] = 1.00;
$modversion['description'] = '活動報名模組';
$modversion['author'] = 'Tony';
$modversion['credits'] = '';
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date'] = '2021/09/22';
$modversion['module_website_url'] = 'https://github.com/tnsbkeam/tony_signup';
$modversion['module_website_name'] = 'jinchg Signup Github';
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://www.tnnag.net';
$modversion['author_website_name'] = 'Tony1102 PHP全端開發';
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5';

//---paypal資訊---//
$modversion['paypal'][] = [
    'business' => '',
    'item_name' => '',
    'amount' => 0,
    'currency_code' => 'USD',
];

//---後台使用系統選單---//
$modversion['system_menu'] = 1;

//---模組資料表架構---//

 $modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
 $modversion['tables'] = ['tony_signup_actions', 'tony_signup_data', 'tony_signup_data_center'];

//---後台管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---前台主選單設定---//
$modversion['hasMain'] = 1;
// $modversion['sub'][] = ['name' => '子選項文字', 'url' => '子選項連結位址'];

//---模組自動功能---//
// $modversion['onInstall'] = "include/onInstall.php";
// $modversion['onUpdate'] = "include/onUpdate.php";
// $modversion['onUninstall'] = "include/onUninstall.php";

//---樣板設定---//
$modversion['templates'][] = ['file' => 'tony_signup_admin.tpl', 'description' => '後台共同樣板'];
$modversion['templates'][] = ['file' => 'tony_signup_index.tpl', 'description' => '前台共同樣板'];

//---搜尋---//
$modversion['hasSearch'] = 1;
$modversion['search'] = ['file' => 'include/search.php', 'func' => 'tony_signup_search'];

//---區塊設定---//
// $modversion['blocks'][] = [
//     'file' => '區塊檔.php',
//     'name' => '區塊名稱',
//     'description' => '區塊說明',
//     'show_func' => '執行區塊函數名稱',
//     'template' => '區塊樣板.tpl',
//     'edit_func' => '編輯區塊函數名稱',
//     'options' => '設定值1|設定值2',
// ];
//---區塊設定---//
$modversion['blocks'][] = [
    'file' => 'action_list.php',
    'name' => '可報名活動一覽表',
    'description' => '列出所有可報名的活動',
    'show_func' => 'action_list',
    'template' => 'action_list.tpl',
    'edit_func' => 'action_list_edit',
    'options' => '5|,action_date desc',
];




//---偏好設定---//
$modversion['config'][] = [
    'name'  => 'show_number',
    'title' => '_MI_TONY_SIGNUP_SHOW_NUMBER',
    'description' => '_MI_TONY_SIGNUP_SHOW_NUMBER_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => '10',
 ];

 $modversion['config'][] = [
    'name' => 'only_enable',
    'title' => '_MI_TONY_SIGNUP_ONLY_ENABLE',
    'description' => '_MI_TONY_SIGNUP_ONLY_ENABLE_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => '0',
 ];



//---評論---//
// $modversion['hasComments'] = 1;
// $modversion['comments'][] = ['pageName' => '單一頁面.php', 'itemName' => '主編號'];

//---通知---//
// $modversion['hasNotification'] = 1;
