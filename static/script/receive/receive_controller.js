/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('receiveController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.goods = {};
        $scope.receivelist = [];
        $scope.receiveId = [];

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#receive_date').datetimepicker({
            format: 'Y-m-d'
        });

        var date = new Date();
        var seperator = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        $scope.receive_date = year + seperator + month + seperator + strDate;

        $scope.vendorlist = {};
        $scope.stocklist = {};
        $http.get('?/Receive/getSelectOption/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg.vendorlist;
            $scope.stocklist = r.ret_msg.stocklist;
        });

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
                    $("#goods_select").html("");
                    if ($scope.goodslist.length > 0) {
                        $("#goods_select").select2({
                            placeholder: "请选择物料",
                            data: $scope.goodslist
                        }).val(0).trigger("change");
                    }
                });
            } else {
                $("#goods_select").select2({
                    placeholder: "请选择物料",
                    data: {}
                });
            }
        };

        $scope.addGoods = function () {
            $scope.goods.goods_id = $("#goods_select").val();
            $scope.goods.goods_name = $("#goods_select option:selected").text();
            if (!$scope.vendor_id > 0 || !$scope.stock_id > 0 || $scope.receive_date == null) {
                Util.alert('请填写完整的收货信息', true);
            } else if (!$scope.goods.goods_id > 0 || !$scope.goods.count > 0) {
                Util.alert('物料信息或数量不正确', true);
            } else {
                $scope.receivelist.push($scope.goods);
                $scope.receiveId.push($scope.goods.goods_id);
                $scope.goods = {};
                $("#goods_select").select2().val(0).trigger("change");
            }
        }

        $scope.remove = function (e) {
            var goods_id = $(e.target).data('id') + '';
            var delindex = $scope.receiveId.indexOf(goods_id);
            if (delindex != -1) {
                $scope.receiveId.splice(delindex, 1);
                $scope.receivelist.splice(delindex, 1);
            }
        }

        $scope.saveAll = function () {
            if (!$scope.receivelist.length > 0) {
                Util.alert('物料清单为空', true);
            }
            for (var i = 0; i < $scope.receivelist.length; i++) {
                $scope.receivelist[i].vendor_id = $scope.vendor_id;
                $scope.receivelist[i].stock_id = $scope.stock_id;
                $scope.receivelist[i].receive_date = $scope.receive_date;
            }
            Util.loading();
            $.post('?/Receive/createRecords/', {
                receiveData: $scope.receivelist
            }, function (r) {
                Util.loading(false);
                if (r.ret_code === 0) {
                    Util.alert('保存成功');
                    $scope.receivelist = [];
                    $scope.$apply();
                } else {
                    Util.alert('操作失败 ' + r.ret_msg, true);
                }
            });
        }


        $scope.selectChange();
    }
)
