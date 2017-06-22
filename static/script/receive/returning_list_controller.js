/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('returningListController', function ($scope, $http, Util) {

        $scope.params = {
            page: 0,
            pagesize: 12
        };

        $scope.goodslist = [];
        $scope.goods = {};

        $scope.returning = {};
        $scope.returninglist = [];

        $scope.typelist = returningtypelist;
        $scope.status_list = returning_status_list;

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#returning_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });

        // 搜索框回车
        $('#search_text').bind('keyup', function (e) {
            if (e.keyCode === 13) {
                $scope.init = false;
                $scope.params.search_text = $scope.search_text;
                fnGetList();
            }
        });


        $.get('?/Receive/getGoodsList/', {}, function (r) {
            $scope.goodslist = r.ret_msg;
            $("#goods_select").html("");
            if ($scope.goodslist.length > 0) {
                $("#goods_select").select2({
                    placeholder: "请选择物料",
                    data: $scope.goodslist
                }).val(0).trigger("change");
            } else {
                $("#goods_select").html("");
                $("#goods_select").select2({
                    placeholder: "请选择物料",
                    data: {}
                });
            }
        });

        $('#modal_returning_detail').on('show.bs.modal', function (event) {
            var btn = $(event.relatedTarget);
            $scope.returning_id = parseInt(btn.data('id'));
            $scope.returning_status = btn.data('status');
            $scope.goods.returning_date = btn.data('date').substr(0, 10);
            if ($scope.returning_id > 0) {
                $http.get('?/Returning/getById/', {
                    params: {
                        id: $scope.returning_id
                    }
                }).success(function (r) {
                    var lists = r.ret_msg;
                    for (var i = 0; i < lists.length; i++) {
                        lists[i].returning_typeX = $scope.typelist[lists[i].returning_type];
                    }
                    $scope.gdlist = lists;
                });
            }
        });

        $scope.addGoods = function () {
            $scope.goods.goods_id = $("#goods_select").val();
            $scope.goods.goods_name = $("#goods_select option:selected").text();
            $scope.goods.returning_typeX = $scope.typelist[$scope.goods.returning_type];
            $scope.goods.returning_id = $scope.returning_id;
            var returning_type = $scope.goods.returning_type;
            var returning_date = $scope.goods.returning_date;
            if ($scope.goods.returning_type == null || $scope.goods.returning_date == null) {
                Util.alert('请选择日期和退货类型', true);
            } else if ($scope.goods.goods_id == null || isNaN($scope.goods.counts) || $scope.goods.counts == 0) {
                Util.alert('物料信息或数量不正确', true);
            } else {
                $scope.gdlist.push($scope.goods);
                $scope.goods = {};
                $scope.goods.returning_type = returning_type;
                $scope.goods.returning_date = returning_date;
                $("#goods_select").select2().val(0).trigger("change");
            }
        };

        $scope.saveAll = function (e) {
            var btn = $(e.currentTarget);
            btn.html('处理中');
            $.post('?/Returning/createDetail/', {id: $scope.returning_id, data: $scope.gdlist}, function (r) {
                    if (r.ret_code === 0) {
                        $('#modal_returning_detail').modal('hide');
                        Util.alert('保存成功');
                        fnGetList();
                    }
                    else {
                        Util.alert('操作失败 ' + r.ret_msg, true);
                    }
                    btn.html('保存');
                }
            );
        };

        function fnGetList() {
            Util.loading();
            $http.get('?/Returning/getList/', {
                params: $scope.params
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                // 处理数据
                for (var i in json) {
                    json[i].statusX = $scope.status_list[json[i].status];
                }
                $scope.returninglist = json;

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

    }
);
