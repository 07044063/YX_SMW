/* global angular */

var app = angular.module('ngApp', ['Util.services']);

app.controller('systemLogsController', function ($scope, $http, Util) {

    $scope.init = false;

    $scope.params = {
        page: 0,
        pagesize: 20,
    };

    $scope.list = [];

    function fnGetList() {
        Util.loading();
        $http.get('?/System/getSystemLogs/', {
            params: $scope.params
        }).success(function (r) {
            Util.loading(false);
            $scope.list = r.list;
            var paget = 1;
            if (r.total > 0) {
                paget = Math.ceil(r.total / $scope.params.pagesize);
            }
            if (!$scope.init) {
                Util.initPaginator(paget, function (page) {
                    if ($scope.init) {
                        $(window).scrollTop(0);
                        $scope.params.page = page - 1;
                        fnGetList();
                    }
                });
                $scope.init = true;
            }
        });
    }

    fnGetList();

});