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
        };
        //微信JSSDK签名获取
        var url = window.location.href;
        $http.get('?/Weixin/getSignPackage/', {
            params: {
                url: url
            }
        }).success(function (r) {
                if (r.ret_code == 0) {
                    $scope.signPackage = r.ret_msg;
                    wx.config({
                        debug: true,
                        appId: $scope.signPackage['appid'],
                        timestamp: $scope.signPackage['timestamp'],
                        nonceStr: $scope.signPackage['noncestr'],
                        signature: $scope.signPackage['signature'],
                        jsApiList: [
                            // 所有要调用的 API 都要加到这个列表中
                            'chooseImage', 'uploadImage', 'scanQRCode'
                        ]
                    });
                    wx.ready(function () {
                        // 在这里调用 API
                    });
                }
            }
        );

        $scope.choosePhoto = function () {
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                }
            });
        };

        $scope.scanQRCode = function () {
            wx.scanQRCode({
                desc: 'scanQRCode desc',
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    // 回调
                },
                error: function (res) {
                    if (res.errMsg.indexOf('function_not_exist') > 0) {
                        alert('版本过低请升级')
                    }
                }
            });
        }

    }
);
