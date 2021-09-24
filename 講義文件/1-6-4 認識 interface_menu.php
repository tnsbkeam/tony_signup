1-6-4 認識 interface_menu.php

一、這也不是XOOPS規定要的檔，此檔不存在也沒關係。
二、interface_menu.php 主要用來做幾件事：
   1.判斷目前登入者身份（是否為模組管理員，或其他身份？）
      //判斷是否對該模組有管理權限
      $is_admin = basename(__DIR__) . '_adm';
         if (!isset($_SESSION[$is_admin])) {
            $_SESSION[$is_admin] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
      }
   2.模組的工具列設定
    //回模組首頁
    $interface_menu[_TAD_TO_MOD] = "index.php";
    $interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

    //模組後台
    if ($_SESSION[$is_admin]) {
        $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
        $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
    }

  3.若有 interface_menu.php 存在，則工具列會整合至導覽列中（也可以同時出現在模組頁面上）。
  4.若 interface_menu.php 不存在（但缺將奇內容複製到 header.php），則不會將模組工具列整合至導覽列，而是
    出現在模組頁面上。
