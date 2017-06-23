var currentOrderpage = 0;
var orderLoading = false;
var orderLoadingLock = false;
var totalheight;

// orderlist列表页面
if ($('#orderlist').length > 0) {
    // init list
    loadOrderList(currentOrderpage);
    // onscroll bottom
    $(window).scroll(function () {
        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 150;
        if ($(document).height() <= totalheight && !orderLoading) {
            //加载数据
            loadOrderList(++currentOrderpage);
        }
    });
}

$('.order-sort').unbind('click').click(function () {
    currentOrderpage = -1;
    orderLoading = false;
    orderLoadingLock = false;
    $('#status').val($(this).attr('data-status'));
    $('.order-sort.hover').removeClass('hover');
    $(this).addClass('hover');
    loadOrderList(currentOrderpage);
});

function goDetail(order_code) {
    location.href = '?/Wxpage/order/order_code=' + order_code;
}

// Ajax load Order list
function loadOrderList(page) {
    if (!orderLoadingLock) {
        page = parseInt(page);
        if (page === -1) {
            page = 0;
            $("#orderlist").html('');
        }
        // request uri
        orderLoading = true;
        $('#list-loading').show();
        // [HttpGet]
        $.get('?/Weixin/getOrderListByStatus/page=' + page + '&status=' + $('#status').val(), function (HTML) {
            orderLoading = false;
            if (HTML === '' && page === 0) {
                // 什么都没有
                $("#orderlist").html('');
                $("#orderlist").append('<div class="weui-media-box weui-media-box_text"><h4 class="weui-media-box__title">暂无数据</h4></div>');
            } else if (HTML !== '') {
                if (page === 0) {
                    $("#orderlist").html(HTML);
                } else {
                    $("#orderlist").append(HTML);
                }
            } else {
                orderLoadingLock = true;
            }
            $('#list-loading').hide();
        });
    }
}