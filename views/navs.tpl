{if $Auth.stat}
    <a href="javascript:;" class="navItem" id="navitem1" rel='subnav1'>
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span><i class="label">首页</i>
    </a>
    <div class='subnavs clearfix' id='subnav1'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="home">首页</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem2" rel='subnav2'>
        <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><i class='label'>主数据管理</i>
    </a>
    <div class='subnavs clearfix' id='subnav2'>
        <a class='cap-nav-item' href='javascript:;' data-page="vendor" data-nav="mdata">供货商管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="customer" data-nav="mdata">需求方管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="modelx" data-nav="mdata">车型信息管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="warehouse" data-nav="mdata">仓储管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="stock" data-nav="mdata">库区管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="person" data-nav="mdata">人员管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="goods" data-nav="mdata">物料管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="truck" data-nav="mdata">车辆管理</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem3" rel='subnav3'>
        <span class="glyphicon glyphicon-import" aria-hidden="true"></span><i class='label'>收货管理</i>
    </a>
    <div class='subnavs clearfix' id='subnav3'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">收货入库</a>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">到货异常维护</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem4" rel='subnav4'>
        <span class="glyphicon glyphicon-export" aria-hidden="true"></span><i class='label'>发货管理</i>
    </a>
    <div class='subnavs clearfix' id='subnav4'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="send">需求单管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="send">客服确认</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem5" rel='subnav5'>
        <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span><i class='label'>退货管理</i>
    </a>
    <div class='subnavs clearfix' id='subnav5'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">退货单管理</a>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">客服确认</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem6" rel='subnav6'>
        <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span><i class='label'>库内管理</i>
    </a>
    <div class='subnavs clearfix' id='subnav6'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">库存查询</a>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">流水查询</a>
    </div>
{/if}

{if $Auth.orde}
    <a href="javascript:;" class="navItem" id="navitem9" rel='subnav9'>
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span><i class='label'>系统设置</i>
    </a>
    <div class='subnavs clearfix' id='subnav9'>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">基础设置</a>
        <a class='cap-nav-item' href='javascript:;' data-page="?" data-nav="recive">管理权限</a>
    </div>
{/if}

{if $Auth.sett}
    <a href="javascript:;" class="navItem" id="navitem10" rel='subnav10'>
        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span><i class='label'>系统维护</i>
    </a>
    <div class='subnavs clearfix' id='subnav10'>
        <a class='cap-nav-item' href='javascript:;' data-page="logs" data-nav="system">系统日志</a>
    </div>
{/if}

<br/>
<br/>
<br/>