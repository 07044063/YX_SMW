/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('modelGoodsListController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.mg = {};
        $scope.model_id = $('#model_id').val();

        $scope.params = {
            page: 0,
            pagesize: 20,
            id: $scope.model_id
        };

        $scope.goodslist = {};
        $http.get('?/Modelx/getGoodsSelectOption/', {
            params: {}
        }).success(function (r) {
            $scope.goodslist = r.ret_msg;
            $("#goods_select").select2({
                data: $scope.goodslist
            });
        });

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

        $('#modal_modify_model_goods').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.mg_id = parseInt(btn.data('id'));
            if ($scope.mg_id > 0) {
                $http.get('?/Modelx/getMgById/', {
                    params: {
                        id: $scope.mg_id
                    }
                }).success(function (r) {
                    $scope.mg = r.ret_msg;
                    $("#goods_select").select2().val($scope.mg.goods_id).trigger("change");
                });
            } else {
                $scope.mg = {};
                $scope.mg.model_id = $('#model_id').val();
                $("#goods_select").select2().val(0).trigger("change");
                $scope.$apply();
            }
        });

        $scope.goBack = function () {
            history.back();
        }

        $scope.modifyModelGoods = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            $scope.mg.goods_id = $("#goods_select").val();
            var param = $.param($scope.mg);
            $http.post('?/Modelx/createOrUpdateMg/', param, $scope.post_head).success(function (r) {
                if (r.ret_code === 0) {
                    $('#modal_modify_model_goods').modal('hide');
                    fnGetList();
                    if ($scope.mg_id > 0) {
                        Util.alert('保存成功');
                    } else {
                        Util.alert('添加成功');
                    }
                } else {
                    Util.alert('操作失败 ' + r.ret_msg, true);
                }
            });
            btn.html('保存');
        };

        $scope.deleteModelGoods = function (e) {
            var node = e.currentTarget;
            var param = $.param({
                id: $(node).data('id')
            });
            if (confirm('你确定要删除这个物料明细信息吗?')) {
                $http.post('?/Modelx/deleteMgById/', param, $scope.post_head).success(function (r) {
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

        function fnGetList() {
            Util.loading();
            $http.get('?/Modelx/getMgList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.mglist = json;
                $scope.listcount = r.total;
                if (!$scope.init) {
                    $scope.init = true;
                    fnInitPager();
                }
            });
        }

        function fnGetModel() {
            Util.loading();
            $http.get('?/Modelx/getById/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.ret_msg;
                $scope.model = json;
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

        fnGetModel();
        fnGetList();

    }
);
