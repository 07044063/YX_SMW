<?php /** Created by conghu on 2017/5/3.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="inventory_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="inventoryController" ng-app="ngApp">

    {literal}
        <div class="panel panel-default">
            <div class="pull-right" style="padding-top: 5px;padding-right: 5px ">
                <button type="button" class="btn btn-default"
                        ng-click="resetQuery($event)">重置
                </button>
                <button type="button" class="btn btn-success"
                        ng-click="getList($event)">查询
                </button>
                <button type="button" class="btn btn-info"
                        ng-click="export($event)">导出
                </button>
            </div>
            <div class="panel-heading">输入条件查询</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <lable>供货商</lable>
                            <select class="form-control" ng-model="vendor_id"
                                    ng-change="vendorChange()"
                                    ng-options="vendor.id as vendor.text for vendor in vendorlist">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <lable>库区</lable>
                            <select class="form-control" ng-model="stock_id"
                                    ng-change="stockChange()"
                                    ng-options="stock.id as stock.text for stock in stocklist">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <lable>物料名称</lable>
                            <!--<select class="form-control" ng-model="goods_id"
                                    ng-options="goods.id as goods.text for goods in goodslist">
                            </select>-->
                            <select id="goods_select" class="form-control select2" style="width: 100%">
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th>库区</th>
                <th>物料图号</th>
                <th>物料名称</th>
                <th>供应商</th>
                <th>良品数量</th>
                <th>冻结数量</th>
                <th>不良品数量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="inventory in inventoryList">
                <td>{{inventory.stock_name}}</td>
                <td>{{inventory.goods_ccode}}</td>
                <td>{{inventory.goods_name}}</td>
                <td>{{inventory.vendor_name}}</td>
                <td>{{inventory.quantity}}</td>
                <td>{{inventory.locked}}</td>
                <td>{{inventory.abnormal}}</td>
                <td><a class="text-success" href="?/Page/record/goods_id={{inventory.goods_id}}" target="_blank">查看流水</a>
                </td>
            </tr>
            </tbody>
        </table>
    {/literal}
</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>
<script type="text/javascript" src="{$docroot}static/script/report/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
