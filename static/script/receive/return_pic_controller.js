/**
 * Created by conghu on 2017/7/13.
 */
var app = angular.module('ngApp', ['Util.services']);

app.controller('returnpicController', function ($scope, $http, Util) {

        $scope.returnpic_id = $('#returnpic_id').val();
        $scope.returnpic_url = "";
        var r=0;
        $scope.imgRotate = function () {

            //document.getElementById("ddd").innerHTML= $scope.returnpic_url;

            var element = document.getElementById("return_div");

            function rotate() {
                r += 90;
                element.style.MozTransform = "rotate(" + r + "deg)";
                element.style.webkitTransform = "rotate(" + r + "deg)";
                element.style.msTransform = "rotate(" + r + "deg)";
                element.style.OTransform = "rotate(" + r + "deg)";
                element.style.transform = "rotate(" + r + "deg)";
            }

            rotate();
        };

        function getUrlById() {
            $http.get('?/Returning/getUrlById/', {
                params: {
                    id: $scope.returnpic_id
                }
            }).success(function (r) {
                $scope.returnpic_url = r.ret_msg.pic_url;
            });
        }
        getUrlById();
    }
);