/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('wxtestController', function ($scope, $http, Util) {

        $scope.post_head = {
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        };

        $scope.accesstoken = 'AccessToken';

        $scope.getAccessToken = function () {
            $http.get('?/Weixin/getAccessToken/', {
                params: {
                    id: 0
                }
            }).success(function (r) {
                    if (r.ret_code == 0) {
                        $scope.accesstoken = r.ret_msg;
                    }
                }
            )
        }

    }
)
