/**
 * Created by conghu on 2017/5/12.
 */

var app = angular.module('ngApp', ['Util.services']);

app.controller('receiveCheckController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20,
        };

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#receiveFrom_date').datetimepicker({
            format: 'Y-m-d'
        });
        $('#receiveTo_date').datetimepicker({
            format: 'Y-m-d'
        });

        $scope.vendorlist = {};
        $scope.stocklist = {};
        $http.get('?/Receive/getSelectOption/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg.vendorlist;
            $scope.stocklist = r.ret_msg.stocklist;
        });


        $scope.receiveCheckList = function (e) {
            // var btn = $(e.currentTarget);
            Util.alert('开始查询');
            fnGetList();
            Util.alert('查询完成');
        };

        function fnGetList() {
            Util.loading();
            $http.get('?/Receive/getList/', {
                params: {
                    stock_name: $scope.stock_name,
                    vendor_name: $scope.vendor_name,
                    goods_name:$scope.goods_name,
                    receiveFrom_date:$scope.receiveFrom_date,
                    receiveTo_date:$scope.receiveTo_date
                }
            }).success(function (r) {
                Util.loading(false);
                var json = r.list;
                $scope.receiveList = json;
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
)
