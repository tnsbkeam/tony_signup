<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Tony_signup;

use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tony_signup\Tony_signup_actions;

class Tony_signup_data
{
    //列出所有報名資料
    public static function index()
    {
        global $xoopsTpl;

        $all_data = self::get_all();
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯報名表單
    public static function create($action_id, $id = '')
    {
        global $xoopsTpl, $xoopsUser;

        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id);

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
        $action = Tony_signup_actions::get($action_id);

        if (time() > strtotime($action['end_date'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "以報名截止，無法再進行報名或修改報名");
        }

        $myts = \MyTextSanitizer::getInstance();
        foreach ($action as $col_name => $col_val) {
            //過濾讀出的變數值
            if ($col_name == 'detail') {
                $col_val = $myts->displayTarea($col_val, 0, 1, 0, 1, 1);
            } else {
                $col_val = $myts->htmlSpecialChars($col_val);
            }
            $action[$col_name] = $col_val;
        }
        $xoopsTpl->assign('action', $action);
        //加入uid的資料
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign('uid', $uid);
    }

    //新增報名資料
    public static function store()
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $sql = "insert into `" . $xoopsDB->prefix("tony_signup_data") . "` (
        `欄位1`,
        `欄位2`,
        `欄位3`
        ) values(
        '{$欄位1值}',
        '{$欄位2值}',
        '{$欄位3值}'
        )";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();
        return $id;
    }

    //以流水號秀出某筆報名資料內容
    public static function show($id = '')
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($id)) {
            return;
        }

        $id = (int) $id;
        $data = self::get($id);

        $myts = \MyTextSanitizer::getInstance();
        foreach ($data as $col_name => $col_val) {
            $col_val = $myts->htmlSpecialChars($col_val);

            //過濾讀出的變數值 displayTarea($text, $html=0, $smiley=1, $xcode=1, $image=1, $br=1);
            // $data['大量文字欄'] = $myts->displayTarea($data['大量文字欄'], 0, 1, 0, 1, 1);
            // $data['HTML文字欄'] = $myts->displayTarea($data['HTML文字欄'], 1, 0, 0, 0, 0);

            $xoopsTpl->assign($col_name, $col_val);
        }
    }

    //更新某一筆報名資料
    public static function update($id = '')
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $sql = "update `" . $xoopsDB->prefix("tony_signup_data") . "` set
        `欄位1` = '{$欄位1值}',
        `欄位2` = '{$欄位2值}',
        `欄位3` = '{$欄位3值}'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $id;
    }

    //刪除某筆報名資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("tony_signup_data") . "`
        where `id` = '{$id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    public static function get_all($auto_key = false)
    {
        global $xoopsDB;
        $myts = \MyTextSanitizer::getInstance();

        $sql = "select * from `" . $xoopsDB->prefix("tony_signup_data") . "` where 1 ";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while ($data = $xoopsDB->fetchArray($result)) {

            // $data['文字欄'] = $myts->htmlSpecialChars($data['文字欄']);
            // $data['大量文字欄'] = $myts->displayTarea($data['大量文字欄'], 0, 1, 0, 1, 1);
            // $data['HTML文字欄'] = $myts->displayTarea($data['HTML文字欄'], 1, 0, 0, 0, 0);
            // $data['數字欄'] = (int) $data['數字欄'];

            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

}