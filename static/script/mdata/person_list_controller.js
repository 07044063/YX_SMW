/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('personListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20
        };

        $scope.ptypelist = persontypelist;

        //$.datetimepicker.setLocale('zh');
        //
        //// 日期选择器
        //$('#stime').datetimepicker({
        //    format: 'Y-m-d'
        //});
        //$('#etime').datetimepicker({
        //    format: 'Y-m-d'
        //});

        // 搜索框回车
        $('#search_text').bind('keyup', function (e) {
            if (e.keyCode === 13) {
                $scope.init = false;
                $scope.params.search_text = $scope.search_text;
                fnGetList();
            }
        });

        $('#modal_modify_person').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.person_id = parseInt(btn.data('id'));
            if ($scope.person_id > 0) {
                $http.get('?/Person/getById/', {
                    params: {
                        id: $scope.person_id
                    }
                }).success(function (r) {
                    $scope.person = r.ret_msg;
                    $scope.selected_person_type = $scope.ptypelist[$scope.person.person_type];
                    $scope.typeChange();
                });
            } else {
                $scope.person = {};
                $scope.orglist = {};
                $scope.$apply();
            }
        });

        $scope.modifyPerson = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            var param = $.param($scope.person);
            $http.post('?/Person/createOrUpdate/', param, $scope.post_head).
                success(function (r) {
                    if (r.ret_code === 0) {
                        $('#modal_modify_person').modal('hide');
                        fnGetList();
                        if ($scope.person_id > 0) {
                            Util.alert('保存成功');
                        } else {
                            Util.alert('添加成功');
                        }
                    } else {
                        Util.alert('操作失败 ' + r.ret_msg, true);
                    }
                });
            btn.html('保存');
        };

        $scope.deletePerson = function (e) {
            var node = e.currentTarget;
            var param = $.param({
                id: $(node).data('id')
            });
            if (confirm('你确定要删除这个人员吗?')) {
                $http.post('?/Person/deleteById/', param, $scope.post_head).
                    success(function (r) {
                        if (r.ret_code === 0) {
                            Util.alert('删除成功');
                            $(node).parents('tr').remove();
                        } else {
                            //alert(r.ret_msg);
                            Util.alert('操作失败 ' + r.ret_msg, true);
                        }
                    });
            }
        }
        ;

        $scope.typeChange = function () {
            //类型变化时 组织的下拉菜单联动
            $scope.orglist = {};
            if ($scope.person.person_type > 0) {
                $http.get('?/Person/getOrgOption/', {
                    params: {
                        type: $scope.person.person_type
                    }
                }).success(function (r) {
                    $scope.orglist = r.ret_msg;
                });
            } else {
                $scope.orglist = {};
            }
        };

        function fnGetList() {
            Util.loading();
            $http.get('?/Person/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.personlist = json;
                for (var i = 0; i < $scope.personlist.length; i++) {
                    $scope.personlist[i].person_type_desc = $scope.ptypelist[$scope.personlist[i].person_type];
                }
                $scope.listcount = r.total;
                if (!$scope.init) {
                    $scope.init = true;
                    fnInitPager();
                }
            });
        }

        /**
         * 初始化分页
         * @returns {x}
         */
        function fnInitPager() {
            var page = 1;
            if ($scope.listcount > 0) {
                page = Math.ceil($scope.listcount / $scope.params.pagesize);
            }
            Util.initPaginator(page, function (page) {
                $('body').animate({scrollTop: '0'}, 200);
                $scope.params.page = page - 1;
                fnGetList();
            });
        }

        fnGetList();

    }
)
