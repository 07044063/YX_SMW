var signPackage = null;
var accesstoken = 'AccessToken';
var orderscanlist = [];
var odlist = [];

function scanQRCode() {
    wx.scanQRCode({
        desc: 'scanQRCode desc',
        needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
        scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有
        success: function (res) {
            // 回调
            if (res.resultStr) {
                var res_str = res.resultStr.split(',');
                if (res_str[0] == 'CODE_128') {
                    orderscanlist.push("'" + res_str[1] + "'");
                } else {
                    $.alert('无法识别扫描结果');
                }
                setTimeout(scanQRCode, 500);
            }
        },
        error: function (res) {
            if (res.errMsg.indexOf('function_not_exist') > 0) {
                $.alert('版本过低请升级')
            }
        },
        cancel: function (res) {
            //用户点击取消，扫描结束
            getOrderInfo(orderscanlist.join(","));
        }
    });
}

function getOrderInfo(olist) {
    $.toptip('正在获取数据', 2000, 'warning');
    odlist = [];
    $.post('?/Weixin/getOrderInfoByList/', {
        orderlist: olist
    }, function (r) {
        odlist = r.ret_msg;
        showOrderList();
    });
}

function showOrderList() {
    var data = {
        title: 'baiduTemplate',
        list: odlist
    };
    var html0 = baidu.template('order_temp', data);
    $('#orderlist').html(html0);
    $('#listcount').html(odlist.length + '单');

    //添加点击事件
    $('#orderlist .weui-cell').click(function () {
        var click_orderid = $(this).data('id');
        $.actions({
            actions: [{
                text: "移除",
                className: "color-danger",
                onClick: function () {
                    //删除元素
                    $('#od' + click_orderid).remove();
                }
            }]
        });
    });

}

//微信JSSDK签名获取
var url = window.location.href;

$.get('?/Weixin/getSignPackage/', {
        url: url
    }, function (r) {
        if (r.ret_code == 0) {
            signPackage = r.ret_msg;
            wx.config({
                debug: false,
                appId: signPackage['appid'],
                timestamp: signPackage['timestamp'],
                nonceStr: signPackage['noncestr'],
                signature: signPackage['signature'],
                jsApiList: [
                    // 所有要调用的 API 都要加到这个列表中
                    'scanQRCode'
                ]
            });
            wx.ready(function () {
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，
                // config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，
                // 则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，
                // 则可以直接调用，不需要放在ready函数中。
                //scanQRCode();
            });
            wx.error(function (res) {
                // config信息验证失败会执行error函数，如签名过期导致验证失败，
                // 具体错误信息可以打开config的debug模式查看，
                // 也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                //$.alert('JSSDK初始化失败！');
            });
        }
    }
);

$.get('?/Weixin/getTruckList/', {}, function (r) {
    $("#truck_select").select({
        title: "选择车辆",
        items: r.ret_msg
    });
});

$('#begin_scan').click(function () {
    scanQRCode();
});

$('#test_btn').click(function () {
    getOrderInfo("'XBH590E0A','XBH5RCECF'");
});

$('#do_order').click(function () {
    var truckid = $('#truck_select').data('values');
    if (!truckid) {
        $.alert('没有选择车辆！');
        return;
    }
    //禁用按钮防止重复点击
    $('#do_order').attr({"disabled": "disabled"});
    var odcommitlist = '';
    $('#orderlist .weui-cell').each(function () {
        odcommitlist = odcommitlist + "," + $(this).data('id');
    });
    odcommitlist = odcommitlist.slice(1);
    if (odcommitlist.length > 0) {
        //处理发货数据
        $.post('?/Weixin/orderSend/', {truckid: truckid, odlist: odcommitlist}, function (r) {
            if (r.ret_code == 0) {
                $.toast('发货成功');
            } else {
                $.alert('操作失败 ' + send_error_list[r.ret_code]);
            }
        });
    } else {
        $.alert('没有添加发货单！');
    }
    //启用按钮防止重复点击
    $('#do_order').removeAttr("disabled");
});
