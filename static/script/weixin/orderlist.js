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
            if ($("#full").css("display") == 'none') {
                //如果没有显示查询条件的Model，则加载数据
                loadOrderList(++currentOrderpage);
            }
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
        var param = {
            page: page,
            order_status: $('#status').val(),
            search_text: $("#search_text").val(),
            order_address: $("#order_address").val(),
            order_type: $("#order_type").val(),
            order_vendor: $("#order_vendor").data('values')
        };

        $.get('?/Weixin/getOrderListByStatus', param, function (HTML) {
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

var o_status_l = order_status_list;
var o_address_list = address_list;
o_address_list.pop(); //删除最后一个元素"其他"
var o_type_list = order_type_list;

$("#order_address").select({
    title: "选择收货方",
    items: o_address_list
});

$("#order_type").select({
    title: "选择类型",
    items: o_type_list
});

$.get('?/Weixin/getVendorList', {}, function (r) {
    $("#order_vendor").select({
        title: "选择供应商",
        items: r.ret_msg
    });
});

$('#query').click(function () {
    currentOrderpage = -1;
    orderLoading = false;
    orderLoadingLock = false;
    if ($("#search_text").val()) {
        //如果输入单号查询，则默认显示全部卡片
        $('#status').val('all');
        $('.order-sort.hover').removeClass('hover');
        $('#sort_all').addClass('hover');
    }
    loadOrderList(currentOrderpage);
});

$('#reset').click(function () {
    $("#search_text").val('');
    $("#order_address").val('');
    $("#order_type").val('');
    $("#order_vendor").val('').data('values', 0);
});