var currentPage = 0;
var dLoading = false;
var dLoadingLock = false;
var totalheight;

// 列表页面
if ($('#returninglist').length > 0) {
    // init list
    loadList(currentPage);
    // onscroll bottom
    $(window).scroll(function () {
        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 150;
        if ($(document).height() <= totalheight && !dLoading) {
            //加载数据
            loadList(++currentPage);
        }
    });
}

$('.return-sort').unbind('click').click(function () {
    currentPage = -1;
    dLoading = false;
    dLoadingLock = false;
    $('#status').val($(this).attr('data-status'));
    $('.return-sort.hover').removeClass('hover');
    $(this).addClass('hover');
    loadList(currentPage);
});

function doReceive(id, status) {
    if (status == 'create') {
        $.actions({
            title: "选择操作",
            onClose: function () {
                console.log("close");
            },
            actions: [
                {
                    text: "确认收货",
                    className: "color-primary",
                    onClick: function () {
                        $.post('?/Weixin/confirmReturningReceive', {id: id}, function (r) {
                            if (r.ret_code == 0) {
                                $.toast("操作成功");
                                currentPage = -1;
                                loadList(currentPage);
                            } else {
                                $.alert("操作失败 " + r.ret_msg);
                            }
                        });
                    }
                }
            ]
        });
    }
}

function viewPic(url) {
    wx.previewImage({
        current: url,
        urls: [
            url
        ]
    });
}

// Ajax load Order list
function loadList(page) {
    if (!dLoadingLock) {
        page = parseInt(page);
        if (page === -1) {
            page = 0;
            $("#returninglist").html('');
        }
        // request uri
        dLoading = true;
        $('#list-loading').show();
        // [HttpGet]
        $.get('?/Weixin/getReturningListByStatus/page=' + page + '&status=' + $('#status').val(), function (HTML) {
            dLoading = false;
            if (HTML === '' && page === 0) {
                // 什么都没有
                $("#returninglist").html('');
                $("#returninglist").append('<div class="weui-media-box weui-media-box_text"><h4 class="weui-media-box__title">暂无数据</h4></div>');
            } else if (HTML !== '') {
                if (page === 0) {
                    $("#returninglist").html(HTML);
                } else {
                    $("#returninglist").append(HTML);
                }
            } else {
                dLoadingLock = true;
            }
            $('#list-loading').hide();
        });
    }
}