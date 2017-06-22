{include file='../__header_v2.tpl'}
{assign var="script_name" value="password_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="passwordController" ng-app="ngApp">

    {literal}

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>旧密码</span>
        </div>
        <div class="fv2Right">
            <input type="password" class="form-control" ng-model="password.old" autofocus/>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>新密码</span>
        </div>
        <div class="fv2Right">
            <input type="password" class="form-control" ng-model="password.new"/>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>再次确认</span>
        </div>
        <div class="fv2Right">
            <input type="password" class="form-control" ng-model="password.confirm"/>
        </div>
    </div>

    <div class="fix_bottom" style="position: fixed; height: 58px;">
        <button type="button" class="btn btn-success" ng-click="modifyPassword($event)">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存
        </button>
    </div>

</div>

{/literal}

<script type="text/javascript" src="{$docroot}static/script/setting/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
