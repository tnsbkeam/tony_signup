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

<<<<<<< HEAD
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
             //抓取預設值
=======
<<<<<<< HEAD
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
             //抓取預設值
=======
        $uid = $_SESSION['tony_signup_adm'] ? null : $xoopsUsers->uid();
        //抓取預設值
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
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
<<<<<<< HEAD
        }elseif (!$action['enable']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "該報名已關閉，無法再進行報名或修改報名");
        }elseif (count($action['signup']) >= $action['number']) {
=======
<<<<<<< HEAD
        }elseif (!$action['enable']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "該報名已關閉，無法再進行報名或修改報名");
        }elseif (count($action['signup']) >= $action['number']) {
=======
        } elseif (count($action['signup']) >= $action['number']) {
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
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

<<<<<<< HEAD
        $uid = $_SESSION['can_add']? null : $xoopsUser->uid();
=======
<<<<<<< HEAD
        $uid = $_SESSION['can_add']? null : $xoopsUser->uid();
=======
        $uid = $_SESSION['tony_signup_adm']? null : $xoopsUser->uid();
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef

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
<<<<<<< HEAD
            if (!$_SESSION['can_add'] or !$uid) {
=======
<<<<<<< HEAD
            if (!$_SESSION['can_add'] or !$uid) {
=======
            if (!$_SESSION['tony_signup_adm'] or !$uid) {
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
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

<<<<<<< HEAD
        if (!$_SESSION['can_add']) {
=======
<<<<<<< HEAD
        if (!$_SESSION['can_add']) {
=======
        if (!$_SESSION['tony_signup_adm']) {
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }
        /*  再次數字化  */
        $id = (int) $id;
        $accept = (int) $accept;

        $sql = "update `" . $xoopsDB->prefix("tony_signup_data") . "` set `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
     }

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
     //立即寄出
     public static function send($title = "無標題", $content = "無內容", $email = "")
     {
         global $xoopsUser;
         if (empty($email)) {
             $email = $xoopsUser->email();
         }
         $xoopsMailer = xoops_getMailer();
         $xoopsMailer->multimailer->ContentType = "text/html";
         $xoopsMailer->addHeaders("MIME-Version: 1.0");
         $header = '';
         return $xoopsMailer->sendMail($email, $title, $content, $header);
     }

    //產生寄信通知
    public static function mail($id, $type, $signup = [])
    {
        global $xoopsUser;
        $id = (int) $id;
        if (empty($id)){
            redirect_header($_SERVER['PHP_SELF'], 3, "無編號，無法寄送通知信");
        }
        /* 假如$signup已存在則用原來抓到的$signup,否則由get($id, true)來取得報名資料 */
        /* $signup = $signup ? $signup : self::get($id, true); */
        $signup = $signup ? $signup : self::get($id);
        $action = Tony_signup_actions::get($signup['action_id']);

        $now  = date("Y-m-d H:i:s");
        $name = $xoopsUser->name();       /* 目前登入的人 */
        $name = $name ? $name : $xoopsUser->uname(); /* 若沒有name則取uname */

        $member_handler = xoops_getHandler('member');   /* 取得會員物件 */
        $admUser = $member_handler->getUser($action['uid']);  /* 取得開發者 */
        $adm_email = $admUser->email();         /* 取得開發者信箱 */

        if ($type =='destroy'){
           $title     = "「{$action['title']}」取消報名通知";
           $head  =  "<p>您於 {$signup['signup_date']} 報名了「{$action['title']}」活動已於 {$now} 由 {$name} 取消報名。</p>";
           $foot = "欲重新報名，請連至 " . XOOPS_URL . "/modules/tony_signup/index.php?op=tony_signup_data_create&action_id={$action['id']}";
        } elseif ($type == 'store') {
            $title = "「{$action['title']}」報名完成通知";
            $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動已於 {$now} 由 {$name} 報名完成。</p>";
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tony_signup/index.php?op=tony_signup_data_show&id={$signup['id']}";
        } elseif ($type == 'update') {
            $title = "「{$action['title']}」修改報名資料通知";
            $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動已於 {$now} 由 {$name} 修改報名資料如下：</p>";
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tony_signup/index.php?op=tony_signup_data_show&id={$signup['id']}";
        } elseif ($type == 'accept') {
            $title = "「{$action['title']}」報名錄取狀況通知";
            if ($signup['accept'] == 1) {
                $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動經審核，<h2 style='color:blue'>恭喜錄取！</h2>您的報名資料如下：</p>";
            } else {
                $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動經審核，很遺憾的通知您，因名額有限，<span style='color:red;'>您並未錄取。</span>您的報名資料如下：</p>";
            }
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tony_signup/index.php?id={$signup['action_id']}";
             /* 取得報名者的資料 */
            $signupUser = $member_handler->getUser($signup['uid']);
            $email = $signupUser->email();

        }

        $content = self::mk_content($id, $head, $foot, $action);

        /* 假如沒有送出則秀出通知再轉向 */
        if (!self::send($title, $content, $email)) {
           redirect_header($_SERVER['PHP_SELF'], 3, "通知信寄發失敗！");
        }

        self::send($title, $content, $adm_email); /* 另寄通知給管理員 */
    }

    // 產生通知信內容
    public static function mk_content($id, $head = '', $foot = '', $action = [])
    {
        if ($id) {
            $TadDataCenter = new TadDataCenter('tony_signup');
            $TadDataCenter->set_col('id', $id);
            $tdc = $TadDataCenter->getData();

            $table = '<table class="table">';
            foreach ($tdc as $title => $signup) {
                $table .= "
                <tr>
                    <th>{$title}</th>
                    <td>";
                foreach ($signup as $i => $val) {
                    $table .= "<div>{$val}</div>";
                }

                $table .= "</td>
                </tr>";
            }
            $table .= '</table>';
        }

        $content = "
        <html>
            <head>
                <style>
                    .table{
                        border:1px solid #000;
                        border-collapse: collapse;
                        margin:10px 0px;
                    }

                    .table th, .table td{
                        border:1px solid #000;
                        padding: 4px 10px;
                    }

                    .table th{
                        background:#c1e7f4;
                    }

                    .well{
                        border-radius: 10px;
                        background: #fcfcfc;
                        border: 2px solid #cfcfcf;
                        padding:14px 16px;
                        margin:10px 0px;
                    }
                </style>
            </head>
            <body>
                $head
                <h2>{$action['title']}</h2>
                <div>活動日期：{$action['action_date']}</div>
                <div class='well'>{$action['detail']}</div>
                $table
                $foot
            </body>
        </html>
        ";
        return $content;
    }

<<<<<<< HEAD
=======
=======
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
}
