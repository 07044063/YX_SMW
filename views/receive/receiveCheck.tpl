<?php /** Created by conghu on 2017/5/3.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="receiveCheck_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="receiveCheckController" ng-app="ngApp">

    {literal}
    <div class="panel panel-default">
        <div class="panel-heading">收货信息查询（请任意输入条件查询）</div>
        <div class="panel-body">
        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <lable>库区</lable>
                    <select class="form-control"  ng-model="stock_id"
                            ng-change="vendorListChange()"
                            ng-options="stock.id as stock.text for stock in stocklist">
                    </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <lable>供货商</lable>
                    <select class="form-control"  ng-model="vendor_id"
                            ng-change="goodsListChange()"
                            ng-options="vendor.id as vendor.text for vendor in vendorlist">
                    </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <lable>物料名称</lable>
                    <select class="form-control"  ng-model="goods_id"
                            ng-options="goods.id as goods.text for goods in goodslist">
                    </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <lable>收货日期开始:</lable>
                    <input  type="text" id="receiveFrom_date"
                           placeholder="请选择收货日期" ng-model="receiveFrom_date"
                           class="form-control"/>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <lable>收货日期结束:</lable>
                    <input  type="text" id="receiveTo_date"
                           placeholder="请选择收货日期" ng-model="receiveTo_date"
                           class="form-control"/>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-lg btn-block" ng-click="receiveCheckList($event)">查询
                    </button>
                </div>
            </div>

        </div>
        </div>
    </div>


        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th>库位</th>
            <th>材料名称</th>
            <th>供应商</th>
            <th>收件数量</th>
            <th>收件日期</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="rowReceive in receiveList">
            <td>{{rowReceive.stock_name}}</td>
            <td>{{rowReceive.goods_name}}</td>
            <td>{{rowReceive.vendor_name}}</td>
            <td>{{rowReceive.count}}</td>
            <td>{{rowReceive.receive_date}}</td>
            <td>{{rowReceive.remark}}</td>
        </tr>
        </tbody>
        </table>


    {/literal}
</div>

{*<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>*}
<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
