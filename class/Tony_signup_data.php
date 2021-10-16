<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Tony_signup;

use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tony_signup\Tony_signup_actions;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\SweetAlert;


class Tony_signup_data
{
    //列出所有報名資料
    public static function index($action_id)
    {
        global $xoopsTpl;

        $all_data = self::get_all($action_id);
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯報名表單
    public static function create($action_id, $id = '')
    {
        global $xoopsTpl, $xoopsUser;

        $uid = $_SESSION['tony_signup_adm'] ? null : $xoopsUsers->uid();
        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id, $uid);

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "tony_signup_data_store" : "tony_signup_data_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
        //加入action_id的資料
        $action = Tony_signup_actions::get($action_id, true);

        if (time() > strtotime($action['end_date'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "以報名截止，無法再進行報名或修改報名");
        } elseif (count($action['signup']) >= $action['number']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "報名人數已滿，無法再進行報名");
        }

        $xoopsTpl->assign('action', $action);
        //加入uid的資料
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign('uid', $uid);

        $TadDataCenter = new TadDataCenter('tony_signup');//指定那個模組要使用
        $TadDataCenter->set_col('id', $id);      //修改時綁定id

        $signup_form = $TadDataCenter->strToForm($action['setup']);
        $xoopsTpl->assign('signup_form', $signup_form);
    }

    //新增報名資料
    public static function store()
    {
        global $xoopsDB ;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $sql = "insert into `" . $xoopsDB->prefix("tony_signup_data") . "` (
            `action_id`,
            `uid`,
            `signup_date`
            ) values(
            '{$action_id}',
            '{$uid}',
            now()
            )";

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();

        $TadDataCenter = new TadDataCenter('tony_signup');
        $TadDataCenter->set_col('id', $id);   //綁定$id
        $TadDataCenter->saveData();

        return $id;
    }

    //以流水號秀出某筆報名資料內容
    public static function show($id = '', $uid = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $uid = $_SESSION['tony_signup_adm']? null : $xoopsUser->uid();

        $id = (int) $id;
        $data = self::get($id , $uid);

        if (empty($data)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "查無報名無資料，無法觀看");
        }

        $myts = \MyTextSanitizer::getInstance();
        foreach ($data as $col_name => $col_val) {
            $col_val = $myts->htmlSpecialChars($col_val);

            $xoopsTpl->assign($col_name, $col_val);
            $$col_name = $col_val;
        }
       /*  取得陣列資料(參考3-1-5) */

        $TadDataCenter = new TadDataCenter('tony_signup');
        $TadDataCenter->set_col('id', $id);
        $tdc = $TadDataCenter->getData();
       /*  Utility::dd($tdc); */
        $xoopsTpl->assign('tdc', $tdc);
        $action = Tony_signup_actions::get($action_id, true);
        $xoopsTpl->assign('action', $action);

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("now_uid", $now_uid);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_data", "index.php?op=tony_signup_data_destroy&action_id={$action_id}&id=", 'id');

    }

    //更新某一筆報名資料
    public static function update($id = '')
    {
        global $xoopsDB , $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;
        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "update `" . $xoopsDB->prefix("tony_signup_data") . "` set
        `signup_date` = now()
        where `id` = '$id' and `uid` = '$now_uid'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('tony_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->saveData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }

        return $id;
    }

    //刪除某筆報名資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "delete from `" . $xoopsDB->prefix("tony_signup_data") . "`
        where `id` = '{$id}' and `uid`='$now_uid'";
        /*加入權限判斷後再刪除如下(參考3-5-1 刪除資料方法) */
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('tony_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->delData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }

    }

      //以流水號取得某筆報名資料
    public static function get($id = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("tony_signup_data") . "`
        where `id` = '{$id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得所有報名資料陣列
    public static function get_all($action_id = '', $uid = '' , $auto_key = false)
    {
        global $xoopsDB, $xoopsUser;
        $myts = \MyTextSanitizer::getInstance();

        if ($action_id) {
            $sql = "select * from `" . $xoopsDB->prefix("tony_signup_data") . "` where `action_id`='$action_id' order by `signup_date`";
        } else {
            /* 假如使用者不是管理員而且不指定要擷取那個uid的資料 */
            if (!$_SESSION['tony_signup_adm'] or !$uid) {
                $uid = $xoopsUser ? $xoopsUser->uid() : 0;
            }
            $sql = "select * from `" . $xoopsDB->prefix("tony_signup_data") . "` where `uid`='$uid' order by `signup_date`";
        }
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        $TadDataCenter = new TadDataCenter('tony_signup');
        while ($data = $xoopsDB->fetchArray($result)) {
            $TadDataCenter->set_col('id',$data['id']);
            $data['tdc'] = $TadDataCenter->getData();
            $data['action'] = Tony_signup_actions::get($data['action_id'], true);
            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

    //查詢某人的報名記錄
    public static function my($uid){
        global $xoopsTpl, $xoopsUser;

        $my_signup = self::get_all(null, $uid);
        $xoopsTpl->assign('my_signup', $my_signup);

        BootstrapTable::render();

     }

     // 更改錄取狀態(借用有update的函數來修改)
     public static function accept($id, $accept)
     {
        global $xoopsDB;

        if (!$_SESSION['tony_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }
        /*  再次數字化  */
        $id = (int) $id;
        $accept = (int) $accept;

        $sql = "update `" . $xoopsDB->prefix("tony_signup_data") . "` set `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
     }

}
