/* global angular */

var app = angular.module('ngApp', ['Util.services', 'angularFileUpload']);

app.controller('orderCreateController', function ($scope, $http, Util, FileUploader) {

        var uploader = $scope.uploader = new FileUploader({
            url: '?/Common/uploadExcel/',
            formData: new Array({action: "Order"})
        });

        uploader.onBeforeUploadItem = function (fileItem) {
            Util.loading();
        };
        uploader.onAfterAddingFile = function (fileItem) {
        };
        uploader.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.ret_code == 0) {
                Util.alert(response.ret_msg);
            } else if (response.ret_code == 1) {
                Util.alert(response.ret_msg, true);
            }
        };
        $scope.clearItems = function () {    //重新选择文件时，清空队列，达到覆盖文件的效果
            uploader.clearQueue();
        };
        uploader.onCompleteAll = function () {
            uploader.clearQueue();
            Util.loading(false);
        };

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.params = {
            page: 0,
            pagesize: 20
        };

        $.datetimepicker.setLocale('zh');

        // 日期选择器
        $('#order_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });

        $scope.order = {};
        $scope.order.customer_id = 0;
        $scope.order.order_type = '手工单';

        //设置默认的收货日期
        var date = new Date();
        var seperator = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        var strDate = date.getDate();
        if (strDate < 10) {
            strDate = '0' + strDate;
        }
        var datestr = year + seperator + month + seperator + strDate;
        $scope.order.order_date = datestr;

        $scope.vendorlist = [];
        $http.get('?/Order/getVendorSelect/', {
            params: {}
        }).success(function (r) {
            $scope.vendorlist = r.ret_msg;
        });

        $scope.addresslist = address_list;
        $scope.gdlist = [];

        $scope.vendorChange = function () {
            //供应商变化时 物料的下拉菜单联动
            if ($scope.order.vendor_id > 0) {
                $http.get('?/Order/getGoodsList/', {
                    params: {
                        vendor_id: $scope.order.vendor_id
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
                $("#goods_select").html("");
                $("#goods_select").select2({
                    placeholder: "请选择物料",
                    data: {}
                });
            }
        };

        $scope.addGoods = function () {
            $scope.goods.goods_id = $("#goods_select").val();
            $scope.goods.goods_name = $("#goods_select option:selected").text();
            if (!$scope.order.vendor_id > 0 || $scope.order.order_code == null || $scope.order.order_date == null
                || $scope.order.address == null) {
                Util.alert('请检查日期/单号/供货商/收货单位等信息是否完整', true);
            } else if (!$scope.goods.goods_id > 0 || isNaN($scope.goods.needs) || $scope.goods.needs == 0) {
                Util.alert('物料信息或数量不正确', true);
            } else {
                $scope.gdlist.push($scope.goods);
                $scope.goods = {};
                $("#goods_select").select2().val(0).trigger("change");
            }
        };

        $scope.remove = function (index) {
            $scope.gdlist.splice(index, 1);
        };

        $scope.saveAll = function () {
            if (!$scope.gdlist.length > 0) {
                Util.alert('物料清单为空', true);
                return;
            }
            if ($scope.order.address == '其他') {
                if (!$scope.order.address_txt > 0) {
                    Util.alert('请填写收货单位详细信息', true);
                    return;
                } else {
                    $scope.order.address = $scope.order.address_txt;
                }
            }
            for (var i = 0; i < $scope.gdlist.length; i++) {
                $scope.gdlist[i].sends = $scope.gdlist[i].needs;
                $scope.gdlist[i].receives = $scope.gdlist[i].needs;
            }
            Util.loading();
            $.post('?/Order/createOrder/', {
                orderData: $scope.order,
                gdData: $scope.gdlist
            }, function (r) {
                Util.loading(false);
                if (r.ret_code === 0) {
                    Util.alert('保存成功');
                    $scope.order = {};
                    $scope.gdlist = [];
                    $scope.order.order_date = datestr;
                    $scope.$apply();
                    window.open("?/Page/orderprint/orderid=" + r.ret_msg);
                } else {
                    Util.alert('操作失败 ' + r.ret_msg, true);
                }
            });
        };

        $scope.initOrderNo = function () {
            var datestr = $scope.order.order_date;
            if (datestr != '') {
                var dates = new Array();
                dates = datestr.split("-");
                datestr = dates[0].slice(2) + dates[1] + dates[2];
                $http.get('?/Order/getMaxOrderNo/', {
                    params: {date: datestr}
                }).success(function (r) {
                    $scope.order.order_code = r.ret_msg;
                });
            } else {
                $scope.order.order_code = '';
            }

        };

        $scope.vendorChange();
        $scope.initOrderNo();
    }
);
