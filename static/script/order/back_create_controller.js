/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('backCreateController', function ($scope, $http, Util) {

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#back_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });

        $scope.back = {};
        $scope.back.vendor_id = 0;
        $scope.backtypelist = backtypelist;

        //设置默认的收货日期
        var date = new Date();
        var seperator = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        var strDate = date.getDate();
        if (strDate < 10) {
            strDate = '0' + strDate;
        }
        var datestr = year + seperator + month + seperator + strDate;
        $scope.back.back_date = datestr;

        $scope.vendorlist = [];
        $http.get('?/Order/getVendorSelect/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg;
        });

        $scope.gdlist = [];

        $scope.vendorChange = function () {
            //供应商变化时 物料的下拉菜单联动
            if ($scope.back.vendor_id > 0) {
                $http.get('?/Back/getGoodsList/', {
                    params: {
                        vendor_id: $scope.back.vendor_id,
                        type: $scope.back.back_type
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
            } else {
                $("#goods_select").html("");
                $("#goods_select").select2({
                    placeholder: "请选择物料",
                    data: {}
                });
            }
        };

        $scope.addGoods = function () {
            $scope.goods.goods_id = $("#goods_select").val();
            $scope.goods.goods_name = $("#goods_select option:selected").text();
            if (!$scope.back.vendor_id > 0 || $scope.back.back_code == null || $scope.back.back_date == null) {
                Util.alert('请检查日期/单号/供货商/收货单位等信息是否完整', true);
            } else if (!$scope.goods.goods_id > 0 || isNaN($scope.goods.needs) || $scope.goods.needs == 0) {
                Util.alert('物料信息或数量不正确', true);
            } else {
                $scope.gdlist.push($scope.goods);
                $scope.goods = {};
                $("#goods_select").select2().val(0).trigger("change");
            }
        };

        $scope.remove = function (index) {
            $scope.gdlist.splice(index, 1);
        };

        $scope.saveAll = function () {
            if (!$scope.gdlist.length > 0) {
                Util.alert('物料清单为空', true);
                return;
            }
            Util.loading();
            $.post('?/Back/createBack/', {
                backData: $scope.back,
                gdData: $scope.gdlist
            }, function (r) {
                Util.loading(false);
                if (r.ret_code === 0) {
                    Util.alert('保存成功');
                    $scope.back = {};
                    $scope.gdlist = [];
                    $scope.back.back_date = datestr;
                    $scope.$apply();
                    window.open("?/Page/orderprint/backid=" + r.ret_msg);
                } else {
                    Util.alert('操作失败 ' + r.ret_msg, true);
                }
            });
        };

        $scope.initOrderNo = function () {
            var datestr = $scope.back.back_date;
            if (datestr != '') {
                var dates = new Array();
                dates = datestr.split("-");
                datestr = dates[0].slice(2) + dates[1] + dates[2];
                $http.get('?/Back/getMaxBackNo/', {
                    params: {date: datestr}
                }).success(function (r) {
                    $scope.back.back_code = r.ret_msg;
                });
            } else {
                $scope.back.back_code = '';
            }

        };

        $scope.vendorChange();
        $scope.initOrderNo();
    }
);
