<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tony_signup\Tony_signup_actions;
use XoopsModules\Tony_signup\Tony_signup_data;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'Tony_signup_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');
$action_id = Request::getInt('action_id');

/*-----------執行動作判斷區----------*/
switch ($op) {

    //新增活動表單
    case 'tony_signup_actions_create':
        Tony_signup_actions::create();
        break;

    //儲存活動資料
    case 'tony_signup_actions_store':
        $id = Tony_signup_actions::store();
        //header("location: {$_SERVER['PHP_SELF']}?id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, "成功建立活動！");
        exit;

    //修改活動資料用表單
    case 'tony_signup_actions_edit':
        Tony_signup_actions::create($id);
        $op = 'tony_signup_actions_create';
        break;

    //更新活動資料
    case 'tony_signup_actions_update':
        Tony_signup_actions::update($id);
       // header("location: {$_SERVER['PHP_SELF']}?id=$id");
       redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, "成功修改活動！");
        exit;

    //刪除活動資料資料
    case 'tony_signup_actions_destroy':
        Tony_signup_actions::destroy($id);
        redirect_header($_SERVER['PHP_SELF'], 3, "成功刪除活動！");
        exit;
    case 'tony_signup_data_create':
        Tony_signup_data::create($action_id);
        break;

    //新增報名表單
    case 'tony_signup_data_create':
        Tony_signup_data::create($action_id);
        break;

    default:
        if (empty($id)) {
            Tony_signup_actions::index();
            $op = 'tony_signup_actions_index';
        } else {
            Tony_signup_actions::show($id);
            $op = 'tony_signup_actions_show';
        }
        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tony_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
