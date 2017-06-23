// 允许上传的图片类型
var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
// 1024KB，也就是 1MB
var maxSize = 1024 * 1024;
// 图片最大宽度
var maxWidth = 1800;
// 最大上传图片数量
var maxCount = 1;

var imageData = [];

$('#uploaderInput').on('change', function (event) {
    var files = event.target.files;
    // 如果没有选中文件，直接返回
    if (files.length === 0) {
        return;
    }
    if (files.length > maxCount) {
        $.alert('最多只能上传' + maxCount + '张图片');
        return;
    }
    for (var i = 0, len = files.length; i < len; i++) {
        var file = files[i];
        var reader = new FileReader();
        // 如果类型不在允许的类型范围内
        if (allowTypes.indexOf(file.type) === -1) {
            $.alert('该类型不允许上传');
            continue;
        }
        if (file.size > maxSize) {
            $.alert('图片太大，不允许上传');
            continue;
        }

        if ($('.weui-uploader__file').length >= maxCount) {
            $.alert('最多只能上传' + maxCount + '张图片');
            return;
        }

        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                // 不要超出最大宽度
                var w = Math.min(maxWidth, img.width);
                // 高度按比例计算
                var h = img.height * (w / img.width);
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                // 设置 canvas 的宽度和高度
                canvas.width = w;
                canvas.height = h;
                ctx.drawImage(img, 0, 0, w, h);
                var base64 = canvas.toDataURL('image/png');

                // 插入到预览区
                var $preview = $('<li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(' + base64 + ')"><div class="weui-uploader__file-content">0%</div></li>');
                $('#uploaderFiles').append($preview);
                var num = $('.weui-uploader__file').length;
                $('#uploadFilesNumber').text(num + '/' + maxCount);

                // 然后假装在上传，可以post base64格式，也可以构造blob对象上传，也可以用微信JSSDK上传

                var progress = 0;

                function uploading() {
                    $preview.find('.weui-uploader__file-content').text(++progress + '%');
                    if (progress < 100) {
                        setTimeout(uploading, 30);
                    }
                    else {
                        // 如果是失败，塞一个失败图标
                        //$preview.find('.weui_uploader_status_content').html('<i class="weui_icon_warn"></i>');
                        $preview.removeClass('weui-uploader__file_status').find('.weui-uploader__file-content').remove();
                    }
                }

                setTimeout(uploading, 30);
            };

            img.src = e.target.result;
            imageData.push(img);
        };
        reader.readAsDataURL(file);
    }
});

function saveData() {
    alert('save');
}