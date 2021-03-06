<ul class="list-group">
    <{foreach from=$block item=action}>
        <li class="list-group-item">
            <span class="badge badge-info"><{$action.action_date|substr:0:-3}></span>
            <small>名額<{$action.number}>人，已報名<{$action.signup|@count}>人</small>
            <div>
                <{if $action.enable && $action.number > $action.signup|@count && $xoops_isuser && $action.end_date|strtotime >= $smarty.now}>
                    <i class="fa fa-check text-success" data-toggle="tooltip" title="報名中" aria-hidden="true"></i>
                <{else}>
                    <i class="fa fa-times text-danger" data-toggle="tooltip" title="無法報名" aria-hidden="true"></i>
                <{/if}>
                <a href="<{$xoops_url}>/modules/tony_signup/index.php?id=<{$action.id}>"><{$action.title}></a>
            </div>
        </li>
    <{/foreach}>
</ul>
