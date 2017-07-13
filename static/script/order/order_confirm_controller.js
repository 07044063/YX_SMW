/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('orderConfirmController', function ($scope, $http, Util) {

        $scope.order = {};
        $scope.goodslist = [];

        $scope.order_status_list = order_status_list;
        $scope.good_pack_typelist = goodpacktypelist;

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.order_id = $('#order_id').val();

        function fnInit() {
            Util.loading();
            if ($scope.order_id > 0) {
                $http.get('?/Order/getById/', {
                    params: {
                        id: $scope.order_id
                    }
                }).success(function (r) {
                    //只有已交货和已完成的单据才显示信息
                    if (r.ret_msg.order.status == 'delivery' || r.ret_msg.order.status == 'done') {
                        $scope.order = r.ret_msg.order;
                        $scope.order.statusX = $scope.order_status_list[$scope.order.status];
                        $scope.goodslist = r.ret_msg.goodslist;
                        for (var i = 0; i < $scope.goodslist.length; i++) {
                            $scope.goodslist[i].packing = $scope.good_pack_typelist[$scope.goodslist[i].goods_packing];
                        }
                    } else {
                        Util.alert('发货单还未完成交货！', true);
                    }
                    Util.loading(false);
                });
            } else {
                Util.loading(false);
            }
        }

        $scope.editZero = function (e) {
            var node = e.currentTarget;
            $(node).parent().children("input").val(0);
        };

        $scope.saveEdit = function (e) {
            var node = e.currentTarget;
            var id = $(node).data('id');
            var odata = {};
            odata['id'] = id;
            odata['receives'] = $(node).parent().children("input").val();
            $.post('?/Order/updateSingelOrderDetail/', odata, function (r) {
                if (r.ret_code == 0) {
                    Util.alert('保存成功');
                } else {
                    Util.alert('保存失败 ' + r.ret_msg, true);
                }
            });
        };

        $scope.closeOrder = function (e) {
            Util.loading();
            $.post('?/Order/modifyOrderStatus/', {
                orderId: $scope.order_id,
                oldstatus: $scope.order.status,
                newstatus: 'done'
            }, function (r) {
                Util.loading(false);
                if (r.ret_code === 0) {
                    Util.alert('确认订单成功');
                    fnInit();
                } else {
                    Util.alert('操作失败 ' + order_error_list[r.ret_code], true);
                }
            });
        };

        $scope.refresh = function () {
            fnInit();
        };

        fnInit();
    }
);
