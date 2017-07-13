<a href="javascript:;" class="navItem" id="navitem1" rel='subnav_i'>
    <span class="glyphicon glyphicon-home" aria-hidden="true"></span><i class="label">首页</i>
</a>
<div class='subnavs clearfix' id='subnav_i'>
    <a class='cap-nav-item' href='javascript:;' data-controller="Page" data-function="index">首页</a>
</div>
<div>
{foreach from=$menu item=me}
  {if $me.type == 'menu'}
    </div>
    <a href="javascript:;" class="navItem" id="navitem{$me.id}" rel='subnav{$me.id}'>
        <span class="glyphicon {$me.icon}" aria-hidden="true"></span><i class='label'>{$me.name}</i>
    </a>
    <div class='subnavs clearfix' id='subnav{$me.id}'>
  {/if}
  {if $me.type == 'page'}
    <a class='cap-nav-item' href='javascript:;' data-controller="{$me.controller}"
    data-function="{$me.function}">{$me.name}</a>
  {/if}
{/foreach}
</div>
<br/>
<br/>
<br/>
