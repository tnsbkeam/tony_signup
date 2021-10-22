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
$accept = Request::getInt('accept');

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

    //新增報名表單
    case 'tony_signup_data_create':
        Tony_signup_data::create($action_id);
        break;

    //新增報名資料之儲存
    case 'tony_signup_data_store':
        $id = Tony_signup_data::store();
        Tony_signup_data::mail($id, 'store');
        redirect_header("{$_SERVER['PHP_SELF']}?op=tony_signup_data_show&id=$id", 3, "成功報名活動！");
        exit;

    //顯示報名表單
    case 'tony_signup_data_show':
        Tony_signup_data::show($id);
        break;

    //修改報名表單
    case 'tony_signup_data_edit':
        Tony_signup_data::create($action_id, $id);
        $op = 'tony_signup_data_create';
        break;

     //更新報名資料
    case 'tony_signup_data_update':
        Tony_signup_data::update($id);
        Tony_signup_data::mail($id, 'update');
        redirect_header($_SERVER['PHP_SELF'] . "?op=tony_signup_data_show&id=$id", 3, "成功修改報名資料！");
        exit;
    //刪除報名資料
    case 'tony_signup_data_destroy':
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();
        $signup = Tony_signup_data::get($id, $uid);
        Tony_signup_data::destroy($id);
        Tony_signup_data::mail($id, 'destroy', $signup);
        redirect_header($_SERVER['PHP_SELF'] . "?id=$action_id", 3, "成功刪除報名資料！");
        exit;

    //更改錄取狀態
    case 'tony_signup_data_accept':
        Tony_signup_data::accept($id, $accept);
        Tony_signup_data::mail($id, 'accept');
        redirect_header($_SERVER['PHP_SELF'] . "?id=$action_id", 3, "成功設定錄取狀態！");
        exit;

    // 複製活動
    case 'tony_signup_actions_copy':
        $new_id = Tony_signup_actions::copy($id);
        header("location: {$_SERVER['PHP_SELF']}?op=tony_signup_actions_edit&id=$new_id");
        exit;

    default:
        if (empty($id)) {
            Tony_signup_actions::index($xoopsModuleConfig['only_enable']);
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
