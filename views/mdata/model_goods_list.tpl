{include file='../__header_v2.tpl'}
{assign var="script_name" value="model_goods_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="modelGoodsListController" ng-app="ngApp">

    {include file='../mdata/modal_modify_model_goods.html'}

    <input id="model_id" value="{{$model_id}}" class="hidden"/>

    {literal}

    <table>
        <thead>
        <tr>
            <th>车型信息</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><b>主机厂：</b><span>{{model.customer_name}}</span></td>
            <td><b>车型代码：</b><span>{{model.model_code}}</span></td>
            <td><b>车型名称：</b><span>{{model.model_name}}</span></td>
            <td><b>别名：</b><span>{{model.model_alias}}</span></td>
            <td><b>生产厂区：</b><span>{{model.model_plant}}</span></td>
        </tr>
        </tbody>
    </table>

    <div class="pheader clearfix">
        <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                         placeholder="输入物料代码/名称按回车"/></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_modify_model_goods">添加</a>
            <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
            &nbsp;&nbsp;
            <button type="button" class="btn btn-gray" ng-click="goBack();">返回</button>
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th class="hidden">ID</th>
            <th>物料客户图号</th>
            <th>物料供应商图号</th>
            <th>物料名称</th>
            <th>数量</th>
            <th width="100px" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="mgs in mglist">
            <td class="hidden">{{mgs.id}}</td>
            <td>{{mgs.goods_ccode}}</td>
            <td>{{mgs.goods_vcode}}</td>
            <td>{{mgs.goods_name}}</td>
            <td>{{mgs.goods_count}}</td>
            <td>
                <a class="text-success" data-toggle="modal" data-target="#modal_modify_model_goods" data-id="{{mgs.id}}"
                   href="#">编辑</a>
                <a class="text-danger" data-id="{{mgs.id}}" ng-click="deleteModelGoods($event)" href="#">删除</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{/literal}

</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
