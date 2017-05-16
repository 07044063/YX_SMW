/**
 * Created by conghu on 2017/5/12.
 */

var app = angular.module('ngApp', ['Util.services']);

app.controller('receiveListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 12
        };

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#receiveFrom_date').datetimepicker({
            format: 'Y-m-d'
        });
        $('#receiveTo_date').datetimepicker({
            format: 'Y-m-d'
        });

        $scope.vendorlist = [];
        $scope.stocklist = [];
        $http.get('?/Receive/getVendorSelect/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg;
            //$scope.stocklist = r.ret_msg.stocklist;
        });

        $scope.vendorChange = function () {
            //供应商选择变化时 库区的下拉菜单联动
            $scope.stock_id = 0;
            $scope.selectChange();
            if ($scope.vendor_id > 0) {
                $http.get('?/Receive/getStockSelect/', {
                    params: {
                        vendor_id: $scope.vendor_id
                    }
                }).success(function (r) {
                    $scope.stocklist = r.ret_msg;
                    if ($scope.stocklist.length == 1) {
                        $scope.stock_id = $scope.stocklist[0].id;
                        $scope.selectChange();
                    }
                });
            }
        }

        $scope.stockChange = function () {
            $scope.selectChange();
        }

        $scope.selectChange = function () {
            //供应商和库区变化时 物料的下拉菜单联动
            if ($scope.vendor_id > 0 && $scope.stock_id > 0) {
                $http.get('?/Receive/getGoodsList/', {
                    params: {
                        vendor_id: $scope.vendor_id,
                        stock_id: $scope.stock_id
                    }
                }).success(function (r) {
                    $scope.goodslist = r.ret_msg;
                });
            } else {
                $scope.goodslist = [];
            }
        };

        $scope.receiveCheckList = function (e) {
            //开始查询
            fnGetList();
        };

        $scope.resetQuery = function (e) {
            //重置查询条件
            $scope.stocklist = [];
            $scope.goodslist = [];
            $scope.stock_id = 0;
            $scope.vendor_id = 0;
            $scope.goods_id = 0;
        };

        function fnGetList() {
            Util.loading();
            $scope.params.stock_id = $scope.stock_id;
            $scope.params.vendor_id = $scope.vendor_id;
            $scope.params.goods_id = $scope.goods_id;
            $scope.params.receiveFrom_date = $scope.receiveFrom_date;
            $scope.params.receiveTo_date = $scope.receiveTo_date;
            $http.get('?/Receive/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.receiveList = json;
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
