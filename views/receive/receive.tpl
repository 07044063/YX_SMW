{include file='../__header_v2.tpl'}
{assign var="script_name" value="receive_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="receiveController" ng-app="ngApp">

    {literal}
        <div class="panel panel-default">
            <button type="button" class="btn btn-success pull-right" ng-click="saveAll()"
                    style="float: right;margin-top: 5px;margin-right: 5px">全部保存
            </button>
            <div class="panel-heading">收货信息</div>
            <div class="pd15 row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <lable>供货商</lable>
                        <select class="form-control" id="vendor_select"
                                style="display: inline; width: 80%; margin-left: 15px"
                                ng-model="vendor_id" ng-change="vendorChange()"
                                ng-options="vendor.id as vendor.text for vendor in vendorlist">
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <lable>库区</lable>
                        <select class="form-control" id="stock_select"
                                style="display: inline; width: 80%; margin-left: 15px"
                                ng-model="stock_id" ng-change="stockChange()"
                                ng-options="stock.id as stock.text for stock in stocklist">
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <lable>收货时间</lable>
                        <input style="display: inline; width: 60%; margin-left: 15px" type="text" id="receive_date"
                               placeholder="" ng-model="receive_date"
                               class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">收货明细</div>
            <div class="pd15 row">
                <div class="col-xs-5">
                    <div class="form-group">
                        <select id="goods_select" class="form-control select2" style="width: 100%">
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <input id="input_qty" type="text" placeholder="请输入数量" ng-model="goods.count"
                               class="form-control" />
                    </div>

                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <input id="input_remark" type="text" placeholder="请输入备注" ng-model="goods.remark"
                               class="form-control" />
                    </div>

                </div>
                <div class="col-xs-1">
                    <div class="form-group">
                        <button id="re_add" type="button" class="btn btn-primary pull-left" ng-click="addGoods()">添加
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">物料清单</div>
            <table>
                <tbody>
                <tr ng-repeat="receive in receivelist">
                    <td class="hidden"><span>{{receive.goods_id}}</span></td>
                    <td><span>{{receive.goods_name}}</span></td>
                    <td width="20%"><span>数量：{{receive.count}}</span></td>
                    <td width="30%"><span>{{receive.remark}}</span></td>
                    <td width="5%"><a class="text-danger" data-id="{{receive.goods_id}}" ng-click="remove($index)"
                                      href="#">移除</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    {/literal}

</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js"></script>


{include file='../__footer_v2.tpl'}
