/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('vendorListController', function ($scope, $http, Util) {

        $scope.params = {
            page: 0,
            pagesize: 20
        };

        $scope.vendor = {};
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

        $('#modal_modify_vendor').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.vendor_id = parseInt(btn.data('id'));
            if ($scope.vendor_id > 0) {
                $http.get('?/Vendor/getById/', {
                    params: {
                        id: $scope.vendor_id
                    }
                }).success(function (r) {
                    $scope.vendor = r.ret_msg;
                });
            } else {
                $scope.vendor = null;
                $scope.$apply();
            }
        });

        $scope.modifyVendor = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            $.post('?/Vendor/createOrUpdate/', $scope.vendor, function (r) {
                if (r.ret_code === 0) {
                    $('#modal_modify_vendor').modal('hide');
                    fnGetList();
                    if ($scope.vendor_id > 0) {
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

        $scope.deleteVendor = function (e) {
            var node = e.currentTarget;
            if (confirm('你确定要删除这个供货商吗?')) {
                $.post('?/Vendor/deleteById/', {
                    id: $(node).data('id')
                }, function (r) {
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

        function fnGetList() {
            Util.loading();
            $http.get('?/Vendor/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                // 处理数据
                //for (var i in json) {
                //    if (json[i].vendor_address === null) {
                //        json[i].vendor_address = '未知';
                //    }
                //}
                $scope.vendorlist = json;
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
);
