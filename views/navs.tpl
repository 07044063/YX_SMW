<a href="javascript:;" class="navItem" id="navitem1" rel='subnav1'>
    <span class="glyphicon glyphicon-home" aria-hidden="true"></span><i class="label">首页</i>
</a>
<div class='subnavs clearfix' id='subnav1'>
    <a class='cap-nav-item' href='javascript:;' data-page="index" data-nav="home">首页</a>
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
        <span class="glyphicon glyphicon-import" aria-hidden="true"></span><i class='label'>入库管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav3'>
    {if $Auth.receive}
        <a class='cap-nav-item' href='javascript:;' data-page="receive" data-nav="receives">收货入库</a>
    {/if}
    {if $Auth.receivecheck}
        <a class='cap-nav-item' href='javascript:;' data-page="receivecheck" data-nav="receives">收货信息查询</a>
    {/if}
    {if $Auth.returning}
        <a class='cap-nav-item' href='javascript:;' data-page="returning" data-nav="receives">退货入库</a>
    {/if}
    {if $Auth.blank}<a class='cap-nav-item' href='javascript:;' data-page="blank" data-nav="receives">到货异常记录</a>{/if}
</div>

{if $Auth.send}
    <a href="javascript:;" class="navItem" id="navitem4" rel='subnav4'>
        <span class="glyphicon glyphicon-export" aria-hidden="true"></span><i class='label'>出库管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav4'>
    {if $Auth.ordercreate}
        <a class='cap-nav-item' href='javascript:;' data-page="ordercreate" data-nav="send">发货单创建</a>
    {/if}
    {if $Auth.order}<a class='cap-nav-item' href='javascript:;' data-page="order" data-nav="send">发货单查询</a>{/if}
    {if $Auth.backcreate}
        <a class='cap-nav-item' href='javascript:;' data-page="backcreate" data-nav="send">退回单创建</a>
    {/if}
    {if $Auth.back}
        <a class='cap-nav-item' href='javascript:;' data-page="back" data-nav="send">退回单查询</a>
    {/if}
</div>

{if $Auth.report}
    <a href="javascript:;" class="navItem" id="navitem6" rel='subnav6'>
        <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span><i class='label'>库内管理</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav6'>
    {if $Auth.inventory}
        <a class='cap-nav-item' href='javascript:;' data-page="inventory" data-nav="report">库存查询</a>
    {/if}
    {if $Auth.record}<a class='cap-nav-item' href='javascript:;' data-page="record" data-nav="report">流水查询</a>{/if}
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
    {if $Auth.blank}<a class='cap-nav-item' href='javascript:;' data-page="blank" data-nav="settings">管理权限</a>{/if}
    {if $Auth.password}
        <a class='cap-nav-item' href='javascript:;' data-page="password" data-nav="settings">修改密码</a>
    {/if}
</div>

{if $Auth.system}
    <a href="javascript:;" class="navItem" id="navitem10" rel='subnav10'>
        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span><i class='label'>系统维护</i>
    </a>
{/if}
<div class='subnavs clearfix' id='subnav10'>
    {if $Auth.logs}<a class='cap-nav-item' href='javascript:;' data-page="logs" data-nav="system">系统日志</a>{/if}
</div>

<br/>
<br/>
<br/>