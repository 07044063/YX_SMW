/**
 * Created by ben.niu on 2017-05-12.
 */

var persontypelist = {
    1: "供货商员工",
    2: "需求方员工",
    3: "仓储员工"
};

var goodpacktypelist = {
    1: "纸箱",
    2: "料盒",
    3: "器具"
};

var truck_type_list = {
    1: "飞翼",
    2: "厢车"
};

var order_status_list = {
    create: "新创建",
    receive: "仓库已接收",
    ready: "备货已完成",
    check: "对点已完成",
    send: "发货已完成",
    delivery: "交货已完成",
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