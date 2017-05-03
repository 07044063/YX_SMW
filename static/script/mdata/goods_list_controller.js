/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('goodsListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20
        };

        $scope.ptypelist = {
            1: "纸箱",
            2: "料盒",
            3: "器具"
        };

        $scope.vendorlist = {};
        $scope.stocklist = {};
        $http.get('?/Goods/getSelectOption/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg.vendorlist;
            $scope.stocklist = r.ret_msg.stocklist;
        });

        $scope.typeChange = function () {
        }

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

        $('#modal_modify_goods').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.goods_id = parseInt(btn.data('id'));
            if ($scope.goods_id > 0) {
                $http.get('?/Goods/getById/', {
                    params: {
                        id: $scope.goods_id
                    }
                }).success(function (r) {
                    $scope.goods = r.ret_msg;
                });
            } else {
                $scope.goods = {};
                $scope.$apply();
            }
        });

        $scope.modifyGoods = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            var param = $.param($scope.goods);
            $http.post('?/Goods/createOrUpdate/', param, $scope.post_head).
                success(function (r) {
                    if (r.ret_code === 0) {
                        $('#modal_modify_goods').modal('hide');
                        fnGetList();
                        if ($scope.goods_id > 0) {
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

        $scope.deleteGoods = function (e) {
            var node = e.currentTarget;
            var param = $.param({
                id: $(node).data('id')
            });
            if (confirm('你确定要删除这个物料吗?')) {
                $http.post('?/Goods/deleteById/', param, $scope.post_head).
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

        function fnGetList() {
            Util.loading();
            $http.get('?/Goods/getList/', {
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
                $scope.goodslist = json;
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
