{include file='../__header_v2.tpl'}
{assign var="script_name" value="person_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="personListController" ng-app="ngApp">

    {include file='../mdata/modal_modify_person.html'}

    {literal}
        <div class="pheader clearfix">
            <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                             placeholder="输入工号/姓名/部门/电话按回车"/></div>
            <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_modify_person">添加</a>
                <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
            </div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>工号</th>
                <th>姓名</th>
                <th>联系电话</th>
                <th>部门</th>
                <th>邮箱</th>
                <th>类型</th>
                <th>组织</th>
                <th width="100px" class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="persons in personlist">
                <td class="hidden">{{persons.id}}</td>
                <td>{{persons.person_code}}</td>
                <td>{{persons.person_name}}</td>
                <td>{{persons.person_phone}}</td>
                <td>{{persons.person_dept}}</td>
                <td>{{persons.person_email}}</td>
                <td>{{persons.person_type_desc}}</td>
                <td>{{persons.org_name}}</td>
                <td>
                    <a class="text-success" data-toggle="modal" data-target="#modal_modify_person"
                       data-id="{{persons.id}}"
                       href="#">编辑</a>
                    <a class="text-danger" data-id="{{persons.id}}" ng-click="deletePerson($event)" href="#">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    {/literal}


</div>

<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
