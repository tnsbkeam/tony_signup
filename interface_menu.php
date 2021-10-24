<?php
use XoopsModules\Tadtools\Utility;
//判斷是否對該模組有管理權限
$is_admin = basename(__DIR__) . '_adm';
if (!isset($_SESSION[$is_admin])) {
    $_SESSION[$is_admin] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}
// 判斷有無開設活動的權限(假如$_SESSION['can_add']不存在就去執行；苛存在就不執行)
if (!isset($_SESSION['can_add'])) {
    $_SESSION['can_add'] = Utility::power_chk('tony_signup', '1');
}

//回模組首頁
$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

$interface_menu['我的報名紀錄'] = "my_signup.php";
$interface_icon['我的報名紀錄'] = "fa-chevron-right";



//模組後台
if ($_SESSION[$is_admin]) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
}
