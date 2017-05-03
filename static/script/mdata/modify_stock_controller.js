/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('modifyStockController', function ($scope, $http, Util) {

        $scope.stock = {};

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.stock_id = $('#stock_id').val();

        $scope.modifyStock = function (e) {
            var btn = $(e.currentTarget);
            $scope.stock.clerk_ids = $("#clerk_ids").val();
            var param = $.param($scope.stock);
            $http.post('?/Stock/createOrUpdate/', param, $scope.post_head).
                success(function (r) {
                    if (r.ret_code === 0) {
                        if ($scope.stock_id > 0) {
                            Util.alert('保存成功');
                        } else {
                            Util.alert('添加成功');
                        }
                        //history.go(-1);
                    } else {
                        Util.alert('操作失败 ' + r.ret_msg, true);
                    }
                });
        };

        $scope.goBack = function () {
            history.back();
        }

        function fnInit() {
            Util.loading();
            $http.get('?/Stock/getSelectOption/', {
                params: {}
            }).success(function (r) {
                $scope.warehouselist = r.ret_msg.wh;
                $scope.personlist = r.ret_msg.per;
                $("#clerk_ids").select2({
                    data: $scope.personlist
                });
                if ($scope.stock_id > 0) {
                    $http.get('?/Stock/getById/', {
                        params: {
                            id: $scope.stock_id
                        }
                    }).success(function (r) {
                        $scope.stock = r.ret_msg;
                        $("#clerk_ids").select2().val($scope.stock.clerk_ids).trigger("change");
                        Util.loading(false);
                    });
                } else {
                    $scope.stock = {};
                    Util.loading(false);
                }
            });
        }

        fnInit();
    }
)
