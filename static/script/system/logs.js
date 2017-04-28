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
            if (!$scope.init) {
                Util.initPaginator(Math.ceil(r.total / $scope.params.pagesize), function (page) {
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