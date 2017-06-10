<a href="javascript:;" class="navItem" id="navitem1" rel='subnav1'>
    <span class="glyphicon glyphicon-home" aria-hidden="true"></span><i class="label">首页</i>
</a>
<div class='subnavs clearfix' id='subnav1'>
    <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="home">首页</a>
</div>

{if $Auth.mdata}
    <a href="javascript:;" class="navItem" id="navitem2" rel='subnav2'>
        <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><i class='label'>主数据管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav2'>
    {if $Auth.vendor}<a class='cap-nav-item' href='javascript:;' data-page="vendor" data-nav="mdata">供货商管理</a>{/if}
    {if $Auth.customer}
        <a class='cap-nav-item' href='javascript:;' data-page="customer" data-nav="mdata">需求方管理</a>
    {/if}
    {if $Auth.modelx}
        <a class='cap-nav-item' href='javascript:;' data-page="modelx" data-nav="mdata">车型信息管理</a>
    {/if}
    {if $Auth.warehouse}
        <a class='cap-nav-item' href='javascript:;' data-page="warehouse" data-nav="mdata">仓储管理</a>
    {/if}
    {if $Auth.stock}<a class='cap-nav-item' href='javascript:;' data-page="stock" data-nav="mdata">库区管理</a>{/if}
    {if $Auth.person}<a class='cap-nav-item' href='javascript:;' data-page="person" data-nav="mdata">人员管理</a>{/if}
    {if $Auth.goods}<a class='cap-nav-item' href='javascript:;' data-page="goods" data-nav="mdata">物料管理</a>{/if}
    {if $Auth.truck}<a class='cap-nav-item' href='javascript:;' data-page="truck" data-nav="mdata">车辆管理</a>{/if}
    {if $Auth.stockloan}
        <a class='cap-nav-item' href='javascript:;' data-page="stockloan" data-nav="mdata">库区租赁管理</a>
    {/if}
</div>

{if $Auth.receives}
    <a href="javascript:;" class="navItem" id="navitem3" rel='subnav3'>
        <span class="glyphicon glyphicon-import" aria-hidden="true"></span><i class='label'>收货管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav3'>
    {if $Auth.receive}
        <a class='cap-nav-item' href='javascript:;' data-page="receive" data-nav="receives">收货入库</a>
    {/if}
    {if $Auth.receivecheck}
        <a class='cap-nav-item' href='javascript:;' data-page="receivecheck" data-nav="receives">收货信息查询</a>
    {/if}
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="receives">到货异常记录</a>{/if}
</div>

{if $Auth.send}
    <a href="javascript:;" class="navItem" id="navitem4" rel='subnav4'>
        <span class="glyphicon glyphicon-export" aria-hidden="true"></span><i class='label'>发货管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav4'>
    {if $Auth.order}<a class='cap-nav-item' href='javascript:;' data-page="order" data-nav="send">发货单管理</a>{/if}
</div>

{if $Auth.return}
    <a href="javascript:;" class="navItem" id="navitem5" rel='subnav5'>
        <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span><i class='label'>退货管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav5'>
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="return">退货单管理</a>{/if}
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="return">客服确认</a>{/if}
</div>

{if $Auth.report}
    <a href="javascript:;" class="navItem" id="navitem6" rel='subnav6'>
        <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span><i class='label'>库内管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav6'>
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="report">库存查询</a>{/if}
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="report">流水查询</a>{/if}
</div>

{if $Auth.settings}
    <a href="javascript:;" class="navItem" id="navitem9" rel='subnav9'>
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span><i class='label'>系统设置</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav9'>
    {if $Auth.setting}
        <a class='cap-nav-item' href='javascript:;' data-page="setting" data-nav="settings">基础设置</a>
    {/if}
    {if $Auth.aaa}<a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="settings">管理权限</a>{/if}
</div>

{if $Auth.system}
    <a href="javascript:;" class="navItem" id="navitem10" rel='subnav10'>
        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span><i class='label'>系统维护</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav10'>
    {if $Auth.logs}<a class='cap-nav-item' href='javascript:;' data-page="logs" data-nav="system">系统日志</a>{/if}
    {if $Auth.Wxpage_index}<a class='cap-nav-item' href='?/Wxpage/index' data-nav="system">微信调试</a>{/if}
</div>

<br/>
<br/>
<br/>