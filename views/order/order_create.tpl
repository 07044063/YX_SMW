{include file='../__header_v2.tpl'}
{assign var="script_name" value="order_create_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="orderCreateController" ng-app="ngApp" uploader="uploader">

    {include file='../order/modal_order_import.html'}

    {literal}
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <lable>需求日期</lable>
                    <input style="display: inline; width: 65%; margin-left: 15px" type="text" id="order_date"
                           placeholder="" ng-model="order.order_date" ng-change="initOrderNo()"
                           class="form-control"/>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <lable>供货单位</lable>
                    <select class="form-control" id="vendor_select"
                            style="display: inline; width: 75%; margin-left: 15px"
                            ng-model="order.vendor_id" ng-change="vendorChange()"
                            ng-options="vendor.id as vendor.text for vendor in vendorlist">
                    </select>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group">
                    <lable>发货单号</lable>
                    <input style="display: inline; width: 65%; margin-left: 15px" type="text" placeholder=""
                           disabled="true" ng-model="order.order_code"
                           class="form-control"/>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <button type="button" class="btn btn-success" ng-click="saveAll()">保存
                    </button>
                    <button type="button" class="btn btn-primary pull-right" href="#" data-toggle="modal"
                            data-target="#modal_order_import">Excel导入
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <lable>收货单位</lable>
                    <select class="form-control" id="address_select"
                            style="display: inline; width: 65%; margin-left: 15px"
                            ng-model="order.address" ng-options="value for value in addresslist">
                    </select>
                </div>
            </div>
            <div class="col-xs-4">
                <div ng-show="order.address == '其他'" class="form-group">
                    <lable>详细地址</lable>
                    <input style="display: inline; width: 75%; margin-left: 15px" type="text" placeholder=""
                           ng-model="order.address_txt"
                           class="form-control"/>
                </div>
                <div ng-show="order.address == '备件库' || order.address == '江淮四工厂'" class="form-group">
                    <lable>采购单号</lable>
                    <input style="display: inline; width: 75%; margin-left: 15px" type="text" placeholder=""
                           ng-model="order.pur_no"
                           class="form-control"/>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group">
                    <lable>联系方式</lable>
                    <input style="display: inline; width: 65%; margin-left: 15px" type="text" placeholder=""
                           ng-model="order.contacts"
                           class="form-control"/>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="pd15 row">
                <div class="col-xs-5">
                    <div class="form-group">
                        <select id="goods_select" class="form-control select2" style="width: 100%">
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <input type="text" placeholder="请输入数量" ng-model="goods.needs"
                               class="form-control"/>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <input type="text" placeholder="请输入备注" ng-model="goods.remark"
                               class="form-control"/>
                    </div>
                </div>
                <div class="col-xs-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary pull-left" ng-click="addGoods()">添加
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-heading">物料清单</div>
            <table>
                <tbody>
                <tr ng-repeat="gd in gdlist">
                    <td class="hidden"><span>{{gd.goods_id}}</span></td>
                    <td><span>{{gd.goods_name}}</span></td>
                    <td width="20%"><span>数量：{{gd.needs}}</span></td>
                    <td width="30%"><span>{{gd.remark}}</span></td>
                    <td width="5%"><a class="text-danger" data-id="{{gd.goods_id}}" ng-click="remove($index)"
                                      href="#">移除</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    {/literal}
</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/lib/angular-file-upload.js"></script>
<script type="text/javascript" src="{$docroot}static/script/order/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
