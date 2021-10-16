<?php

use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tony_signup\Tony_signup_actions;
use XoopsModules\Tony_signup\Tony_signup_data;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'Tony_signup_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op  = Request::getString('op');
$uid = Request::getInt('uid');
/*-----------執行動作判斷區----------*/
switch ($op) {

    default:
        Tony_signup_data::my($uid);
        $op = 'tony_signup_data_my';
        break;

}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tony_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
