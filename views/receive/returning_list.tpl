{include file='../__header_v2.tpl'}
{assign var="script_name" value="returning_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="returningListController" ng-app="ngApp">

    {include file='../receive/modal_returning_detail.html'}
    {literal}
        <div class="pheader clearfix">
            <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                             placeholder="输入编号按回车"/></div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>退货单</th>
                <th>退货单号</th>
                <th>创建时间</th>
                <th>状态</th>
                <th>备注</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="returnings in returninglist">
                <td class="hidden">{{returnings.id}}</td>
                <td><a href="{{returnings.pic_url}}" target="_blank">
                        <img style="max-height:50px;max-width: 400px;margin: 3px"
                             src="{{returnings.pic_url}}"/></a></td>
                <td>{{returnings.returning_code}}</td>
                <td>{{returnings.create_at}}</td>
                <td>{{returnings.statusX}}</td>
                <td>{{returnings.remark}}</td>
                <td class="text-center">
                    <a class="text-success" data-id="{{returnings.id}}" data-date="{{returnings.create_at}}"
                       data-status="{{returnings.status}}"
                       data-toggle="modal" data-target="#modal_returning_detail" href="#">
                        <p ng-if="returnings.status == 'receive'">添加退货明细</p>
                        <p ng-if="returnings.status == 'done'">查看退货明细</p>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    {/literal}
</div>


<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
