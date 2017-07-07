var currentOrderpage = 0;
var orderLoading = false;
var orderLoadingLock = false;
var totalheight;


function checkData() {
    $("#inventorylist").html('');
    currentOrderpage = -1;
    orderLoading = false;
    orderLoadingLock = false;
    loadInventoryList(currentOrderpage);
}

// inventorylist列表页面
if ($('#inventorylist').length > 0) {
    // init list
    loadInventoryList(currentOrderpage);
    // onscroll bottom
    $(window).scroll(function () {
        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 150;
        if ($(document).height() <= totalheight && !orderLoading) {
            //加载数据
            loadInventoryList(++currentOrderpage);
        }
    });
}

// Ajax load Order list
function loadInventoryList(page) {
    if (!orderLoadingLock) {
        page = parseInt(page);
        if (page === -1) {
            page = 0;
            $("#inventorylist").html('');
        }
        // request uri
        orderLoading = true;
        $('#list-loading').show();
        // [HttpGet]
        $.get('?/Weixin/getInventoryList/page=' + page + '&goods_code=' + $('#i_goodscode').val(), function (HTML) {
            orderLoading = false;
            if (HTML === '' && page === 0) {
                // 什么都没有
                $("#inventorylist").html('');
                $("#inventorylist").append('<div class="weui-media-box weui-media-box_text"><h4 class="weui-media-box__title">暂无数据</h4></div>');
            } else if (HTML !== '') {
                if (page === 0) {
                    $("#inventorylist").html(HTML);
                } else {
                    $("#inventorylist").append(HTML);
                }
            } else {
                orderLoadingLock = true;
            }
            $('#list-loading').hide();
        });
    }

}