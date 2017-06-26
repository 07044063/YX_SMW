var signPackage = null;
var accesstoken = 'AccessToken';

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
                    'chooseImage', 'uploadImage'  //, 'getLocalImgData'
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

//以下为jquery weUi + JS 的代码，不使用，采用微信内置的代码实现。

//// 允许上传的图片类型
//var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
//// 1024KB，也就是 1MB
//var maxSize = 1024 * 1024;
//// 图片最大宽度
//var maxWidth = 1800;
//// 最大上传图片数量
//var maxCount = 1;
//
//var imageData = [];
//
//$('#uploaderInput').on('change', function (event) {
//    var files = event.target.files;
//    // 如果没有选中文件，直接返回
//    if (files.length === 0) {
//        return;
//    }
//    if (files.length > maxCount) {
//        $.alert('最多只能上传' + maxCount + '张图片');
//        return;
//    }
//    for (var i = 0, len = files.length; i < len; i++) {
//        var file = files[i];
//        var reader = new FileReader();
//        // 如果类型不在允许的类型范围内
//        if (allowTypes.indexOf(file.type) === -1) {
//            $.alert('该类型不允许上传');
//            continue;
//        }
//        if (file.size > maxSize) {
//            $.alert('图片太大，不允许上传');
//            continue;
//        }
//
//        if ($('.weui-uploader__file').length >= maxCount) {
//            $.alert('最多只能上传' + maxCount + '张图片');
//            return;
//        }
//
//        reader.onload = function (e) {
//            var img = new Image();
//            img.onload = function () {
//                // 不要超出最大宽度
//                var w = Math.min(maxWidth, img.width);
//                // 高度按比例计算
//                var h = img.height * (w / img.width);
//                var canvas = document.createElement('canvas');
//                var ctx = canvas.getContext('2d');
//                // 设置 canvas 的宽度和高度
//                canvas.width = w;
//                canvas.height = h;
//                ctx.drawImage(img, 0, 0, w, h);
//                var base64 = canvas.toDataURL('image/png');
//
//                // 插入到预览区
//                var $preview = $('<li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(' + base64 + ')"><div class="weui-uploader__file-content">0%</div></li>');
//                $('#uploaderFiles').append($preview);
//                var num = $('.weui-uploader__file').length;
//                $('#uploadFilesNumber').text(num + '/' + maxCount);
//
//                // 然后假装在上传，可以post base64格式，也可以构造blob对象上传，也可以用微信JSSDK上传
//
//                var progress = 0;
//
//                function uploading() {
//                    $preview.find('.weui-uploader__file-content').text(++progress + '%');
//                    if (progress < 100) {
//                        setTimeout(uploading, 30);
//                    }
//                    else {
//                        // 如果是失败，塞一个失败图标
//                        //$preview.find('.weui_uploader_status_content').html('<i class="weui_icon_warn"></i>');
//                        $preview.removeClass('weui-uploader__file_status').find('.weui-uploader__file-content').remove();
//                    }
//                }
//
//                setTimeout(uploading, 30);
//            };
//
//            img.src = e.target.result;
//            imageData.push(img);
//        };
//        reader.readAsDataURL(file);
//    }
//});

var localIds = '';
var serverId = '';

function chooseImg() {
    $('#uploaderFiles').html('');
    wx.chooseImage({
        count: 1, // 默认9
        sizeType: ['compressed'],  // ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'],  // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {
            localIds = res.localIds[0]; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
            var $preview = $('<li class="weui-uploader__file" style="background-image:url(' + localIds + ')"></li>');
            $('#uploaderFiles').append($preview);
        }
    });
}

function saveData() {
    if (!localIds) {
        $.alert('你没有选择图片！');
        return;
    }
    $.showLoading();
    wx.uploadImage({
        localId: localIds, // 需要上传的图片的本地ID，由chooseImage接口获得
        isShowProgressTips: 0, // 默认为1，显示进度提示
        success: function (res) {
            serverId = res.serverId; // 返回图片的服务器端ID
            if (serverId) {
                $.post('?/Common/downloadPicFromWx', {mid: serverId}, function (r) {
                    $.hideLoading();
                    if (r.ret_code == 0) {
                        var pram = {
                            returning_code: $('#r_id').val(),
                            remark: $('#r_remark').val(),
                            pic_url: r.ret_msg.url
                        };
                        $.post('?/Returning/create', pram, function (r) {
                            if (r.ret_code == 0) {
                                $('#r_id').val('');
                                $('#r_remark').val('');
                                localIds = '';
                                $('#uploaderFiles').html('');
                                $.toast('创建成功');
                            } else {
                                $.alert('创建失败 ' + r.ret_msg);
                            }
                        })
                    } else {
                        alert("图片获取失败！ " + r.ret_msg);
                    }
                })
            } else {
                $.alert('上传图片失败！');
            }
        },
        fail: function (res) {
            $.hideLoading();
            alert(res.errMsg);
        }

    });
}