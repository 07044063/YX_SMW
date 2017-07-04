/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('orderListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20
        };

        $scope.address_list = address_list;
        $scope.address_list.unshift('收货单位');
        $scope.order_status_list = order_status_list;
        $scope.order_status_list.all="订单状态";
        $scope.order_type_list = order_type_list;
        $scope.order_type_list.unshift('订单类型');

        //$.datetimepicker.setLocale('zh');
        //
        //// 日期选择器
        //$('#stime').datetimepicker({
        //    format: 'Y-m-d'
        //});
        //$('#etime').datetimepicker({
        //    format: 'Y-m-d'
        //});

        // 搜索框回车
        $('#search_text').bind('keyup', function (e) {
            if (e.keyCode === 13) {
                $scope.init = false;
                $scope.params.search_text = $scope.search_text;
                fnGetList();
            }
        });

        $('#modal_order_status').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.order_id = parseInt(btn.data('id'));
            $scope.order = {};
            $scope.statuslist = [];
            $scope.$apply();
            if ($scope.order_id > 0) {
                $http.get('?/Order/getStatusById/', {
                    params: {
                        id: $scope.order_id
                    }
                }).success(function (r) {
                    $scope.order = r.ret_msg.order;
                    $scope.statuslist = r.ret_msg.statuslist;
                    for (var i = 0; i < $scope.statuslist.length; i++) {
                        $scope.statuslist[i].statusX = $scope.order_status_list[$scope.statuslist[i].status];
                    }
                });
            }
        });

        function fnGetList() {
            Util.loading();
            $http.get('?/Order/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.orderlist = json;
                for (var i = 0; i < $scope.orderlist.length; i++) {
                    $scope.orderlist[i].statusX = $scope.order_status_list[$scope.orderlist[i].status];
                }
                $scope.listcount = r.total;
                if (!$scope.init) {
                    $scope.init = true;
                    fnInitPager();
                }
            });
        }

        $scope.modifyOrderStatus = function (status) {
            Util.loading();
            $.post('?/Order/modifyOrderStatus/', {
                orderId: $scope.order_id,
                oldstatus: $scope.order.status,
                newstatus: status
            }, function (r) {
                Util.loading(false);
                if (r.ret_code === 0) {
                    $('#modal_order_status').modal('hide');
                    Util.alert('保存成功');
                    fnGetList();
                } else {
                    Util.alert('操作失败 ' + order_error_list[r.ret_code], true);
                }
            });
        };

        $scope.deleteOrder = function (e) {
            var node = e.currentTarget;
            var param = $.param({
                id: $(node).data('id')
            });
            if (confirm('确定要删除这个收货单吗?')) {
                $http.post('?/Order/deleteById/', param, $scope.post_head).success(function (r) {
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

        $scope.selectChange= function () {
            //状态-收货方-类型发生变化是时，获取的结果发生改变
            $scope.params.order_status=$scope.order_status;
            $scope.params.order_address=$scope.order_address;
            $scope.params.order_type=$scope.order_type;
            $scope.params.search_text=$scope.search_text;
            fnGetList();
        };

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
);
