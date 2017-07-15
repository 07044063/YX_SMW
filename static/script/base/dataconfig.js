/**
 * Created by ben.niu on 2017-05-12.
 */

var persontypelist = {
    1: "供货商员工",
    2: "需求方员工",
    3: "仓储员工"
};

var backtypelist = {
    1: "良品",
    2: "不良品"
    //3: "器具/料盒"
};

var goodpacktypelist = {
    1: "纸箱",
    2: "料盒",
    3: "器具",
    4: "铁笼"
};

var returningtypelist = {
    1: "良品退货",
    2: "不良品退货"
};

var truck_type_list = [
    "飞翼",
    "厢车"
];

var order_status_list = {  //ORDER_STATUS_Z
    create: "新创建",
    receive: "仓库已接收",
    ready: "备货已完成",
    check: "对点已完成",
    send: "发车已完成",
    arrive: "货已送达",
    delivery: "交货已完成",
    done: "全部完成"
};

var back_status_list = {
    create: "新创建",
    receive: "仓库已接收",
    send: "发车已完成",
    done: "全部完成"
};

var returning_status_list = {
    create: "新创建",
    receive: "仓库已接收",
    done: "全部完成"
};

//操作发货单错误信息
var order_error_list = {
    0: "成功",
    1: "数据库执行失败",
    2: "库存不足",
    8: "没有操作权限",
    9: "发货单信息不正确"
};

//发货错误信息
var send_error_list = {
    0: "成功",
    1: "数据库执行失败",
    8: "没有操作权限",
    9: "车辆信息不正确"
};

//手工单创建，收货单位选择
var address_list = [
    '江淮一工厂',
    '江淮二工厂',
    '江淮三工厂',
    '江淮四工厂',
    '江淮技术中心',
    '延峰',
    '备件库',
    'KD库',
    '其他'
];

var order_type_list = [
    '计划看板',
    '外部序列',
    '循环看板',
    '紧急要货',
    '手工单'
];