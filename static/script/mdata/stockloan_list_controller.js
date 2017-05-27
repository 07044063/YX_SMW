/**
 * Created by conghu on 2017/5/8.
 */

var app = angular.module('ngApp', ['Util.services']);

app.controller('stockLoanListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20,
        };

        $scope.stockList = [];
        $http.get('?/Stockloan/getStockList/', {
            params: {}
        }).success(function (r) {
            $scope.stockList = r.ret_msg;
        });

        $scope.vendorList = [];
        $http.get('?/Stockloan/getVendorList/', {
            params: {}
        }).success(function (r) {
            $scope.vendorList = r.ret_msg;
        });


        // 搜索框回车
        $('#search_text').bind('keyup', function (e) {
            if (e.keyCode === 13) {
                $scope.init = false;
                $scope.params.search_text = $scope.search_text;
                fnGetList();
            }
        });

        $('#modal_modify_stockloan').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.stockloan_id = parseInt(btn.data('id'));
            if ($scope.stockloan_id > 0) {
                $http.get('?/Stockloan/getById/', {
                    params: {
                        id: $scope.stockloan_id
                    }
                }).success(function (r) {
                    $scope.stockloan = r.ret_msg;
                });
            } else {
                $scope.stockloan = null;
                $scope.$apply();
            }
        });

        $scope.modifyStockloan = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            var param = $.param($scope.stockloan);
            $http.post('?/Stockloan/createOrUpdate/', param, $scope.post_head).
                success(function (r) {
                    if (r.ret_code === 0) {
                        $('#modal_modify_stockloan').modal('hide');
                        fnGetList();
                        if ($scope.stockloan_id > 0) {
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

        $scope.deleteStockloan = function (e) {
            var node = e.currentTarget;
            var param = $.param({
                id: $(node).data('id')
            });
            if (confirm('你确定要删除这个供应商的仓储的信息吗?')) {
                $http.post('?/Stockloan/deleteById/', param, $scope.post_head).
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
        };

        function fnGetList() {
            Util.loading();
            $http.get('?/Stockloan/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.stockloanlist = json;
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
