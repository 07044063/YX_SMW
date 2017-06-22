$('#order-percent2').highcharts({
    credits: {
        enabled: false
    },
    chart: {
        type: 'area',
        inverted: true,
        style: {
            fontFamily: '"Microsoft YaHei"',
            fontSize: '12px'
        }
    },
    title: {
        text: ' '
    },
    xAxis: {
        categories: [
            '已交货',
            '运送中',
            '待发货',
            '已接收',
            '未处理'
        ]
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        area: {
            fillOpacity: 0.5,
            dataLabels: {
                enabled: true,
                x: 20,
                y: 15
            },
            color: '#44b549'
        }
    },
    series: [{
        name: '',
        data: [parseInt($('#order_delivery').html()), parseInt($('#order_send').html()), parseInt($('#order_check').html()), parseInt($('#order_receive').html()), parseInt($('#order_create').html())]
    }],
    exporting: {
        enabled: false
    },
    legend: {
        enabled: false
    },
    tooltip: {
        enabled: false,
        pointFormat: '<b>{point.y}</b>'
    }
});

$.get('?/Home/getCatSummary/', {}, function (res) {
    $('#order-percent').eq(0).highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'pie', style: {
                fontFamily: '"Microsoft YaHei"',
                fontSize: '12px'
            }
        },
        title: {
            text: ' ',
            style: {
                fontSize: '15px',
                color: '#666',
                fontWeight: 'lighter'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                size: '60%'
            }
        },

        series: [{
            type: 'pie',
            name: '占比',
            data: res
        }]
    });
});