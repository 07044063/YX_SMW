/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('backListController', function ($scope, $http, Util) {

        $scope.params = {
            page: 0,
            pagesize: 12
        };

        var queryCode = $('#back_code').val();
        if (queryCode) {
            $scope.params.search_text = queryCode;
        }

        $scope.back_status_list = back_status_list;
        $scope.backtypelist = backtypelist;
        $scope.back = {};

        // 搜索框回车
        $('#search_text').bind('keyup', function (e) {
            if (e.keyCode === 13) {
                $scope.init = false;
                $scope.params.search_text = $scope.search_text;
                fnGetList();
            }
        });

        $('#modal_back_detail').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.back_id = parseInt(btn.data('id'));
            $scope.back = {};
            $scope.goodslist = [];
            $scope.$apply();
            if ($scope.back_id > 0) {
                $http.get('?/Back/getById/', {
                    params: {id: $scope.back_id}
                }).success(function (r) {
                    $scope.back = r.ret_msg.back;
                    $scope.goodslist = r.ret_msg.goodslist;
                });
            }
        });

        function fnGetList() {
            Util.loading();
            $http.get('?/Back/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.backlist = json;
                for (var i = 0; i < $scope.backlist.length; i++) {
                    $scope.backlist[i].statusX = $scope.back_status_list[$scope.backlist[i].status];
                    $scope.backlist[i].back_typeX = $scope.backtypelist[$scope.backlist[i].back_type];
                }
                $scope.listcount = r.total;
                if (!$scope.init) {
                    $scope.init = true;
                    fnInitPager();
                }
            });
        }

        $scope.confirmBack = function (e) {
            var node = e.currentTarget;
            if (confirm('退回货物已经确认发出吗？')) {
                Util.loading();
                $.post('?/Back/confirmBack/', {
                    id: $(node).data('id')
                }, function (r) {
                    Util.loading(false);
                    if (r.ret_code === 0) {
                        Util.alert('确认成功');
                        fnGetList();
                    } else {
                        Util.alert('操作失败 ' + r.ret_msg, true);
                    }
                });
            }
        };

        $scope.deleteBack = function (e) {
            var node = e.currentTarget;
            if (confirm('确定要删除这个收货单吗?')) {
                $.post('?/Back/deleteById/', {
                    id: $(node).data('id')
                }, function (r) {
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
