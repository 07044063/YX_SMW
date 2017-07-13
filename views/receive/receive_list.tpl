<?php /** Created by conghu on 2017/5/3.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="receive_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="receiveListController" ng-app="ngApp">

    {literal}
        <div class="panel panel-default">
            <div class="pull-right" style="padding-top: 5px;padding-right: 5px ">
                <button type="button" class="btn btn-default"
                        ng-click="resetQuery($event)">重置
                </button>
                <button type="button" class="btn btn-success"
                        ng-click="receiveCheckList($event)">查询
                </button>
                <button type="button" class="btn btn-info"
                        ng-click="export($event)">导出
                </button>
            </div>
            <div class="panel-heading">输入条件查询</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <lable>供货商</lable>
                            <select class="form-control" ng-model="vendor_id"
                                    ng-change="vendorChange()"
                                    ng-options="vendor.id as vendor.text for vendor in vendorlist">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <lable>库区</lable>
                            <select class="form-control" ng-model="stock_id"
                                    ng-change="stockChange()"
                                    ng-options="stock.id as stock.text for stock in stocklist">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <lable>物料名称</lable>
                            <!--<select class="form-control" ng-model="goods_id"
                                    ng-options="goods.id as goods.text for goods in goodslist">
                            </select>-->
                            <select id="goods_select" class="form-control select2" style="width: 100%">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <lable>收货时间开始</lable>
                            <input type="text" id="receiveFrom_date"
                                   placeholder="" ng-model="receiveFrom_date"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <lable>收货时间结束</lable>
                            <input type="text" id="receiveTo_date"
                                   placeholder="" ng-model="receiveTo_date"
                                   class="form-control"/>
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
                <th>收件数量</th>
                <th>收件时间</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="rowReceive in receiveList">
                <td>{{rowReceive.stock_name}}</td>
                <td>{{rowReceive.goods_ccode}}</td>
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

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>
<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js?v={$cssversion}"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
