var signPackage = null;
var accesstoken = 'AccessToken';

function getAccessToken() {
    $.get('?/Weixin/getAccessToken/', {}, function (r) {
            if (r.ret_code == 0) {
                accesstoken = r.ret_msg;
                $.alert(accesstoken);
            }
        }
    )
}

function choosePhoto() {
    wx.chooseImage({
        count: 1, // 默认9
        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {
            var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
        }
    });
}

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
                    location.href = '?/Wxpage/order/order_code=' + res_str[1];
                } else {
                    $.alert('无法识别扫描结果');
                }
            }
        },
        error: function (res) {
            if (res.errMsg.indexOf('function_not_exist') > 0) {
                $.alert('版本过低请升级')
            }
        }
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
                debug: true,
                appId: signPackage['appid'],
                timestamp: signPackage['timestamp'],
                nonceStr: signPackage['noncestr'],
                signature: signPackage['signature'],
                jsApiList: [
                    // 所有要调用的 API 都要加到这个列表中
                    'chooseImage', 'uploadImage', 'scanQRCode'
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
                $.alert('JSSDK初始化失败！');
            });
        }
    }
);

$('#get_access_token').click(function () {
    getAccessToken();
});
$('#select_pic').click(function () {
    choosePhoto();
});
$('#scan_qrcode').click(function () {
    scanQRCode();
});
$('#test_button').click(function () {

});
