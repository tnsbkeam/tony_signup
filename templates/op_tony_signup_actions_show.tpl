<h2 class="my">
    <{if $enable==1}>
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$title}>
    <small><i class="fa fa-calendar" aria-hidden="true"></i> 活動日期：<{$action_date}></small>
</h2>

<div class="alert alert-info">
    <{$detail}>
</div>

<h3 class="my">
    已報名表資料
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i> 報名截止日期：<{$end_date}>
        <i class="fa fa-users" aria-hidden="true"></i> 報名人數上限：<{$number}>
    </small>
</h3>
<table data-toggle="table" data-pagination="true" data-search="true" data-mobile-responsive="true">
    <thead>
        <tr>
           <{foreach from=$signup.0.tdc key=col_name item=user name=tdc}>
              <th data-sortable="true"><{$col_name}></th>
           <{/foreach}>
<<<<<<< HEAD
           <{if $smarty.session.can_add}>
=======
<<<<<<< HEAD
           <{if $smarty.session.can_add}>
=======
           <{if $smarty.session.tony_signup_adm}>
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
               <th data-sortable="true">錄取</th>
           <{/if}>
             <th data-sortable="true">報名日期</th>
        </tr>
    </thead>
    <tbody>
         <{foreach from=$signup item=signup_data}>
            <tr>
                <{foreach from=$signup_data.tdc key="col_name" item=user_data}>
                    <td>
                        <{foreach from=$user_data item=data}>
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
                           <{if ($smarty.session.can_add && $uid == $now_uid) || $signup_data.uid == $now_uid}>
                                <div>
                                    <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_data_show&id=<{$signup_data.id}>"><{$data}></a>
                                </div>
                           <{else}>
<<<<<<< HEAD
=======
=======
                            <{if $smarty.session.tony_signup_adm || $signup_data.uid == $uid}>
                                <div>
                                    <a href="index.php?op=tony_signup_data_show&id=<{$signup_data.id}>"><{$data}></a>
                                </div>
                            <{else}>
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
                                <{if strpos($col_name, '姓名')!==false}>
                                   <div><{$data|substr_replace:'○':3:3}></div>
                                <{else}>
                                    <div>****</div>
                                <{/if}>
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
                           <{/if}>
                        <{/foreach}>
                    </td>
                 <{/foreach}>
                 <{if $smarty.session.can_add}>
                    <td>
                        <{if $signup_data.accept==='1'}>
                            <div class="text-primary">錄取</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/ttony_signup/index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">改成未錄取</a>
                            <{/if}>
                        <{elseif $signup_data.accept==='0'}>
                            <div class="text-danger">未錄取</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">改成錄取</a>
                            <{/if}>
                        <{else}>
                            <div class="text-muted">尚未設定</div>
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">未錄取</a>
                                <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">錄取</a>
                            <{/if}>
<<<<<<< HEAD
=======
=======
                            <{/if}>
                        <{/foreach}>
                    </td>
                 <{/foreach}>
                 <{if $smarty.session.tony_signup_adm}>
                    <td>
                        <{if $signup_data.accept==='1'}>
                            <!--本來宣佈已錄取現改成未錄取 -->
                            <div class="text-primary">錄取</div>  <!--  (原己設定值) -->
                            <a href="index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">改成未錄取</a>

                        <{elseif $signup_data.accept==='0'}>
                            <!--本來宣佈未錄取現改成錄取 -->
                            <div class="text-danger">未錄取</div>  <!-- (原己設定值) -->
                            <a href="index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">改成錄取</a>

                        <{else}>
                            <!--本來尚未設定現改成錄取或未錄取 -->
                            <div class="text-muted">尚未設定</div>   <!-- (原己設定值) -->
                            <a href="index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning">未錄取</a>
                            <a href="index.php?op=tony_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success">錄取</a>

>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
                        <{/if}>
                    </td>
                 <{/if}>
                 <td><{$signup_data.signup_date}></td>
             </tr>
        <{/foreach}>
    </tbody>
</table>

<<<<<<< HEAD
<{if $smarty.session.can_add}>
=======
<<<<<<< HEAD
<{if $smarty.session.can_add}>
=======
<{if $smarty.session.tony_signup_adm}>
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
    <div class="bar">
        <a href="javascript:del_action('<{$id}>')" class="btn btn-danger">
            <i class="fa fa-times" aria-hidden="true"></i> 刪除活動
        </a>
<<<<<<< HEAD
        <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_actions_edit&id=<{$id}>" class="btn btn-warning">
=======
<<<<<<< HEAD
        <a href="<{$xoops_url}>/modules/tony_signup/index.php?op=tony_signup_actions_edit&id=<{$id}>" class="btn btn-warning">
=======
        <a href="index.php?op=tony_signup_actions_edit&id=<{$id}>" class="btn btn-warning">
>>>>>>> 5e44e78d25d08b3d998f5b82822c7b20e93c69bf
>>>>>>> 3441e70fc10f6bead2f495bf7ee81548f7a086ef
            <i class="fa fa-pencil" aria-hidden="true"></i> 編輯活動
        </a>
    </div>
<{/if}>
