/**
 * Created by conghu on 2017/5/12.
 */

var app = angular.module('ngApp', ['Util.services']);

app.controller('receiveCheckController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20,
        };

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#receiveFrom_date').datetimepicker({
            format: 'Y-m-d'
        });
        $('#receiveTo_date').datetimepicker({
            format: 'Y-m-d'
        });

        $scope.stocklist = {};
        $http.get('?/Receive/getSelectOption/', {
            params: {}
        }).success(function (r) {
            $scope.stocklist = r.ret_msg.stocklist;
        });

        $scope.vendorlist = {};
        $scope.goodslist = {};
        $scope.vendorListChange = function () {
            //变化时 整体联动
            $scope.vendorlist = {};
            $scope.goodslist = {};
            if ($scope.stock_id !='') {
                $http.get('?/Receive/getVendorList/', {
                    params: {
                        stock_id: $scope.stock_id
                    }
                }).success(function (r) {
                    $scope.vendorlist = r.ret_msg;
                });
            } else {
                $scope.vendorlist = {};
                $scope.goodslist = {};
            }
        };
        $scope.goodsListChange = function () {
            //变化时 整体联动
            $scope.goodslist = {};
            if ($scope.vendor_id !='') {
                $http.get('?/Receive/getGoodsList/', {
                    params: {
                        vendor_id: $scope.vendor_id,
                        stock_id: $scope.stock_id
                    }
                }).success(function (r) {
                    $scope.goodslist = r.ret_msg;
                });
            } else {
                $scope.goodslist = {};
            }
        };

        $scope.receiveCheckList = function (e) {
            // var btn = $(e.currentTarget);
            Util.alert('开始查询');
            fnGetList();
            Util.alert('查询完成');
        };

        function fnGetList() {
            Util.loading();
            $http.get('?/Receive/getList/', {
                params: {
                    stock_id: $scope.stock_id,
                    vendor_id: $scope.vendor_id,
                    goods_id:$scope.goods_id,
                    receiveFrom_date:$scope.receiveFrom_date,
                    receiveTo_date:$scope.receiveTo_date
                }
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
