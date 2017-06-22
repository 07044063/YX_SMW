/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('orderEditController', function ($scope, $http, Util) {

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
                    if (r.ret_msg.order.status == 'create') {
                        $scope.order = r.ret_msg.order;
                        $scope.order.statusX = $scope.order_status_list[$scope.order.status];
                        $scope.goodslist = r.ret_msg.goodslist;
                        for (var i = 0; i < $scope.goodslist.length; i++) {
                            $scope.goodslist[i].packing = $scope.good_pack_typelist[$scope.goodslist[i].goods_packing];
                        }
                    } else {
                        Util.alert('发货单已经处理，无法修改！', true);
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
            odata['sends'] = $(node).parent().children("input").val();
            odata['receives'] = odata['sends'];
            $.post('?/Order/updateSingelOrderDetail/', odata, function (r) {
                if (r.ret_code == 0) {
                    Util.alert('保存成功');
                } else {
                    Util.alert('保存失败 ' + r.ret_msg, true);
                }
            });
        };

        $scope.refresh = function () {
            fnInit();
        };

        fnInit();
    }
);
