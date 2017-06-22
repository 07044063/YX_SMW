/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('orderDetailController', function ($scope, $http, Util) {

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
                    $scope.order = r.ret_msg.order;
                    $scope.order.statusX = $scope.order_status_list[$scope.order.status];
                    $scope.goodslist = r.ret_msg.goodslist;
                    for (var i = 0; i < $scope.goodslist.length; i++) {
                        $scope.goodslist[i].packing = $scope.good_pack_typelist[$scope.goodslist[i].goods_packing];
                    }
                    Util.loading(false);
                });
            } else {
                Util.loading(false);
            }
        }

        fnInit();
    }
);
