<?php /** Created by conghu on 2017/5/3.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="truck_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="truckListController" ng-app="ngApp">

    {include file='../mdata/modal_modify_truck.html'}

    {literal}

    <div class="pheader clearfix">
        <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                         placeholder="输入车牌号或车辆描述按回车"/></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_modify_truck">添加</a>
            <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th class="hidden">ID</th>
            <th>车牌号</th>
            <th>车型</th>
            <th>车辆长度（米）</th>
            <th>车辆描述</th>
            <th>购买日期</th>
            <th width="100px" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="rowTruck in trcuklist">
            <td class="hidden">{{rowTruck.id}}</td>
            <td>{{rowTruck.truck_code}}</td>
            <td>{{rowTruck.truck_type}}</td>
            <td>{{rowTruck.truck_length}}</td>
            <td>{{rowTruck.truck_desc}}</td>
            <td>{{rowTruck.truck_date}}</td>
            <td>
                <a class="text-success" data-toggle="modal" data-target="#modal_modify_truck" data-id="{{rowTruck.id}}"
                   href="#">编辑</a>
                <a class="text-danger" data-id="{{rowTruck.id}}" ng-click="deleteModelTruck($event)" href="#">删除</a>
            </td>
        </tr>
        </tbody>
    </table>

    {/literal}
</div>


<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js?v={$cssversion}"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
