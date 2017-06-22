/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('passwordController', function ($scope, $http, Util) {

        $scope.password = {};

        $scope.modifyPassword = function (e) {
            var btn = $(e.currentTarget);
            if ($scope.password.new != $scope.password.confirm) {
                Util.alert('两次输入的新密码不一致', true);
                return;
            }
            if ($scope.password.new.length < 6) {
                Util.alert('密码长度至少为6位', true);
                return;
            }
            $.post('?/Person/changePassword/', $scope.password, function (r) {
                if (r.ret_code === 0) {
                    Util.alert('修改成功，下次请使用新密码登录');
                } else {
                    Util.alert('操作失败 ' + r.ret_msg, true);
                }
            });
        };
    }
);
