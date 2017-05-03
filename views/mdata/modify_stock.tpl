{include file='../__header_v2.tpl'}
{assign var="script_name" value="modify_stock_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="modifyStockController" ng-app="ngApp">

    <input class="hidden" ng-modle="stock_id" value="{$stock_id}" id="stock_id"/>

    {literal}

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>库区代码</span>
        </div>
        <div class="fv2Right">
            <input type="text" class="form-control" ng-model="stock.stock_code" autofocus/>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>库区名称</span>
        </div>
        <div class="fv2Right">
            <input type="text" class="form-control" ng-model="stock.stock_name"/>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>库区面积</span>
        </div>
        <div class="fv2Right">
            <input type="text" class="form-control" ng-model="stock.stock_area"/>
        </div>
    </div>

    <div class="fv2Field clearfix">
        <div class="fv2Left">
            <span>所属仓储</span>
        </div>
        <div class="fv2Right">
            <select class="form-control"
                    data-placeholder=""
                    ng-model="stock.warehouse_id"
                    ng-options="warehouse.id as warehouse.text for warehouse in warehouselist">
            </select>
            <!--<div class='fv2Tip'>Tips</div>-->
        </div>
    </div>

    <div class="fv2Field clearfix">

        <div class="fv2Left">
            <span>设置库管</span>
        </div>
        <div class="fv2Right">
            <select class="form-control"
                    data-placeholder=""
                    ng-model="stock.admin_id"
                    ng-options="person.id as person.text for person in personlist">
            </select>
        </div>
    </div>

    <div class="fv2Field clearfix">

        <div class="fv2Left">
            <span>设置理货员</span>
        </div>
        <div class="fv2Right">
            <select id="clerk_ids" multiple="multiple" class="form-control select2">
            </select>
        </div>
    </div>

    <div class="fix_bottom" style="position: fixed; height: 58px;">
        <button type="button" class="btn btn-success" ng-click="modifyStock($event)">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存
        </button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-gray" ng-click="goBack();">
        <span class="glyphicon glyphicon-chevron-left"
              aria-hidden="true"></span>返回
        </button>
    </div>

</div>

{/literal}

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>
<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
