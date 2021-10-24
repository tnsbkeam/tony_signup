<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

use Xmf\Request;
use XoopsModules\Tony_signup\Tony_signup_actions;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'Tony_signup_admin.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$_SESSION['tony_signup_adm'] = true;
$_SESSION['can_add'] = true;

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');

/*-----------執行動作判斷區----------*/
switch ($op) {

    //新增表單
    case 'tony_signup_actions_create':
        Tony_signup_actions::create();
        break;

    //新增資料
    case 'tony_signup_actions_store':
        $id = Tony_signup_actions::store();
        header("location: {$_SERVER['PHP_SELF']}?id=$id");
        exit;

    //修改用表單
    case 'tony_signup_actions_edit':
        Tony_signup_actions::create($id);
        $op = 'tony_signup_actions_create';
        break;

    //更新資料
    case 'tony_signup_actions_update':
        Tony_signup_actions::update($id);
        header("location: {$_SERVER['PHP_SELF']}?id=$id");
        exit;

    //刪除資料
    case 'tony_signup_actions_destroy':
        Tony_signup_actions::destroy($id);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    default:
        if (empty($id)) {
            Tony_signup_actions::index(false);
            $op = 'tony_signup_actions_index';
        } else {
            Tony_signup_actions::show($id);
            $op = 'tony_signup_actions_show';
        }
        break;

}

/*-----------功能函數區----------*/

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm4.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tony_signup/css/module.css');
require_once __DIR__ . '/footer.php';
