/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('orderCreateController', function ($scope, $http, Util) {


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

        //初始化文件上传控件
        $('#input_file').fileinput({
            language: 'zh', //设置语言
            uploadUrl: '?/Common/uploadFile', //上传的地址，如果自定义上传按钮则不需要在这设置
            allowedFileExtensions: ['xls', 'xlsx'],//接收的文件后缀
            showRemove: true, //是否显示删除按钮
            showUpload: false, //是否显示上传按钮
            showCaption: true, //是否显示标题
            showPreview: false, //是否显示预览画面
            browseClass: "btn btn-primary", //按钮样式
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
        }).on('fileuploaded', function (event, data) {
            Util.loading();
            if (data.response.ret_code == 0) {
                afterUpload(data.response.ret_msg);
            } else {
                Util.alert(data.response.ret_msg, true);
            }
        });

        $scope.uploadclick = function () {
            var resultFile = $('#input_file')[0].files; //获取文件列表
            if (resultFile.length == 1) {
                $('#input_file').fileinput('upload');
            } else {
                Util.alert('请先选择文件', true);
            }
        };

        function afterUpload(filePath) {
            $.post('?/Common/importExcel', {filePath: filePath, action: 'Order'}, function (r) {
                Util.loading(false);
                if (r.ret_code == 0) {
                    $('#success_text').html("成功导入" + r.ret_msg.success + "笔发货单");
                    $('#msg_text').html(r.ret_msg.msg);
                } else {
                    Util.alert(r.ret_msg, true);
                }
            });
        }

        $scope.address_change = function () {
            if ($scope.order.address == '备件库' || $scope.order.address == '延峰') {
                $('#order_code').attr("disabled", false);
            } else {
                $('#order_code').attr("disabled", true);
            }
        }

    }
);
