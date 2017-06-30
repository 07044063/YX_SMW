<?php /** Created by conghu on 2017/5/3.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="record_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="recordController" ng-app="ngApp">
    <input class="hidden" id="q_goods_id" value="{$q_goods_id}"/>
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
                            <lable>收货日期开始</lable>
                            <input type="text" id="from_date"
                                   placeholder="" ng-model="from_date"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <lable>收货日期结束</lable>
                            <input type="text" id="to_date"
                                   placeholder="" ng-model="to_date"
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
                <th>类型</th>
                <th>状态</th>
                <th>数量</th>
                <th>日期</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="record in recordList">
                <td>{{record.stock_name}}</td>
                <td>{{record.goods_ccode}}</td>
                <td>{{record.goods_name}}</td>
                <td>{{record.vendor_name}}</td>
                <td>{{record.gtype}}</td>
                <td>{{record.statusX}}</td>
                <td>{{record.numb}}</td>
                <td>{{record.gdate}}</td>
                <td>{{record.remark}}</td>
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
