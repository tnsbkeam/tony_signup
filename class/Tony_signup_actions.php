<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Tony_signup;

use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\My97DatePicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tony_signup\Tony_signup_data;
use XoopsModules\Tadtools\BootstrapTable;

class Tony_signup_actions
{
    //列出所有資料
    public static function index()
    {
        global $xoopsTpl;

        $all_data = self::get_all();
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯表單
    public static function create($id = '')
    {
        global $xoopsTpl,$xoopsUser;
        if (!$_SESSION['tony_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF']."?id=$id", 3, "非管理員，無法執行此動作");
        }

        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id);
        /* 有些欄位的預設值可以事先來規劃例如: */
        $db_values['number'] = empty($id) ? 50 : $db_values['number'];
        $db_values['enable'] = empty($id) ? 1 : $db_values['enable'];

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "tony_signup_actions_store" : "tony_signup_actions_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

        My97DatePicker::render();

        $uid = $xoopsUser->uid();
        $xoopsTpl->assign('uid', $uid);

    }

    //新增資料
    public static function store()
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $sql = "insert into `" . $xoopsDB->prefix("tony_signup_actions") . "` (
            `title`,
            `detail`,
            `action_date`,
            `end_date`,
            `number`,
            `setup`,
            `enable`,
            `uid`
        ) values(
            '{$title}',
            '{$detail}',
            '{$action_date}',
            '{$end_date}',
            '{$number}',
            '{$setup}',
            '{$enable}',
            '{$uid}'
        )";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();
        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $id = (int) $id;
        $data = self::get($id, true);

        foreach ($data as $col_name => $col_val) {
            $xoopsTpl->assign($col_name, $col_val);
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_action", "index.php?op=tony_signup_actions_destroy&id=", 'id');

        $signup = Tony_signup_data::get_all($id, null, true);
        $xoopsTpl->assign('signup', $signup);

        BootstrapTable::render();

        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign('uid', $uid);
    }

    //更新某一筆資料
     public static function update($id = '')
     {
        global $xoopsDB;

        if (!$_SESSION['tony_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }
        $uid = (int) $uid;
        $enable = (int) $enable;
        $number = (int) $number;

        $sql = "update `" . $xoopsDB->prefix("tony_signup_actions") . "` set
        `title` = '{$title}',
        `detail` = '{$detail}',
        `action_date` = '{$action_date}',
        `end_date` = '{$end_date}',
        `number` = '{$number}',
        `setup` = '{$setup}',
        `enable` = '{$enable}',
        `uid` = '{$uid}'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $id;
     }

    //刪除某筆資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB;

        if (!$_SESSION['tony_signup_adm']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        if (empty($id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("tony_signup_actions") . "`
        where `id` = '{$id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //以流水號取得某筆資料
    public static function get($id = '', $filter = false)
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("tony_signup_actions") . "`
        where `id` = '{$id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        if ($filter) {
            $myts = \MyTextSanitizer::getInstance();
            $data['detail'] = $myts->displayTarea($data['detail'], 0, 1, 0, 1, 1);
            $data['title'] = $myts->htmlSpecialChars($data['title']);
        }
        return $data;
    }

    //取得所有資料陣列(已更正)
    public static function get_all($only_enable = true, $auto_key = false)
    {
        global $xoopsDB;
        $myts = \MyTextSanitizer::getInstance();

        $and_enable = $only_enable ? "and `enable` = '1' and `action_date` >= now()" : '';

        $sql = "select * from `" . $xoopsDB->prefix("tony_signup_actions") . "` where 1 $and_enable";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while ($data = $xoopsDB->fetchArray($result)) {
            $data['title'] = $myts->htmlSpecialChars($data['title']);
            $data['detail'] = $myts->displayTarea($data['detail'], 0, 1, 0, 1, 1);
            $data['setup'] = $myts->displayTarea($data['setup'], 0, 1, 0, 1, 1);

            $data['signup'] = Tony_signup_data::get_all($data['id']);

            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

   //複製活動資料
   public static function copy($id)
   {
       global $xoopsDB , $xoopsUser;
       if (!$_SESSION['tony_signup_adm']) {
        redirect_header($_SERVER['PHP_SELF']."?id=$id", 3, "非管理員，無法執行此動作");
       }
      /*  ⑷ 加入擷取下列資料的新信息 */
       $action = self::get($id);   /* 抓取要複製的活動資料 */
       $uid = $xoopsUser->uid();       /* 抓目前的管理員 */
       $end_date = date('Y-m-d 17:30:00', strtotime('+2 weeks'));      /* 變更截止日期 */
       $action_date = date('Y-m-d 09:00:00', strtotime('+16 days'));   /* 變更活動日期  */

      /*  ⑸ 重寫SQL指令及匯入資料庫 */
       $sql = "insert into `" . $xoopsDB->prefix("tony_signup_actions") . "` (
       `title`,
       `detail`,
       `action_date`,
       `end_date`,
       `number`,
       `setup`,
       `uid`  ,
       `enable`
       ) values(
        '{$action['title']}_copy',
        '{$action['detail']}',
        '{$action_date}',
        '{$end_date}',
        '{$action['number']}',
        '{$action['setup']}',
        '{$uid}',
        '0'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
       //取得最後新增資料的流水編號
       $id = $xoopsDB->getInsertId();
       return $id;
   }

}
