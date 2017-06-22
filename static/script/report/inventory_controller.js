/**
 * Created by conghu on 2017/5/12.
 */

var app = angular.module('ngApp', ['Util.services']);

app.controller('inventoryController', function ($scope, $http, Util) {

        $scope.params = {
            page: 0,
            pagesize: 12
        };

        $.datetimepicker.setLocale('zh');

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
        };

        $scope.stockChange = function () {
            $scope.selectChange();
        };

        $scope.selectChange = function () {
            //供应商和库区变化时 物料的下拉菜单联动
            $http.get('?/Receive/getGoodsList/', {
                params: {
                    vendor_id: $scope.vendor_id,
                    stock_id: $scope.stock_id
                }
            }).success(function (r) {
                $scope.goodslist = r.ret_msg;
                $("#goods_select").html("");
                if ($scope.goodslist.length > 0) {
                    $("#goods_select").select2({
                        placeholder: "请选择物料",
                        data: $scope.goodslist
                    }).val(0).trigger("change");
                }
            });
        };

        $scope.getList = function (e) {
            //开始查询
            $scope.init = false;
            fnGetList();
        };

        $scope.export = function (e) {
            //导出数据
            initparams();
            Util.loading();
            $http.get('?/Inventory/export/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                if (r.ret_code == 0) {
                    window.open(r.ret_msg);
                } else {
                    Util.Alert(r.ret_msg, true);
                }
            });
        };

        $scope.resetQuery = function (e) {
            //重置查询条件
            $scope.stocklist = [];
            $scope.goodslist = [];
            $scope.stock_id = 0;
            $scope.vendor_id = 0;
            $scope.goods_id = 0;
            $("#goods_select").html("");
            $scope.vendorChange();
        };

        function initparams() {
            $scope.params.stock_id = $scope.stock_id;
            $scope.params.vendor_id = $scope.vendor_id;
            $scope.params.goods_id = $("#goods_select").val();
        }

        function fnGetList() {
            initparams();
            Util.loading();
            $http.get('?/Inventory/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var sjson = r.list;
                $scope.inventoryList = sjson;
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

        $scope.vendorChange();

    }
)
;
