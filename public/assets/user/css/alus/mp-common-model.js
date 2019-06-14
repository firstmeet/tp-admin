// var mpHttp  = function(angular){
//
//     this.$get = function(){
//
//     }
//
// }

(function(angular){
    var DEFAULT_ID = '__default';
    "use strict";
    angular.module("mpCommonModel",[]).config(['$httpProvider', function($httpProvider) {//注册http拦截器加入loading
        $httpProvider.interceptors.push('myInterceptor');
    }]).factory('myInterceptor', function () {
        var timestampMarker = {
            request: function (config) {
                //start
                $(".loading").show();
                return config;
            },
            response: function (response) {
                //end
                $(".loading").hide();
                return response;
            }
        };
        return timestampMarker;
    }).service('mpHttp',function($location,$http,mpLayer){
        // 检查返回的数据
        var _check_response = function(response){
            $(".loading").hide();
            if(response.status != 200 && response.status != 503 ){
                mpLayer.alert("请求失败："+response.status);
                return false;
            }
            switch (response.data.code){
                case "SUCCESS":
                    return true;
                    break;
                case "ACCOUNT_NOT_LOGIN": // 账号未登录
                    mpLayer.alert(response.data.message);
                    window.location.href = response.data.data;
                    return false;
                    break;
                default:
                    mpLayer.alert(response.data.message+":"+response.data.data);
                    return true;
                    break;
            }
        }

        return {
            get:function(url,successCallback,errorCallback){
                $http.get(url,{headers:{"X-Requested-With":"xmlhttprequest"}}).then(function(response){
                    if(_check_response(response) && successCallback != null && typeof  successCallback != 'undefined') {
                        successCallback(response);
                    }
                },function(response){
                    if(_check_response(response) && errorCallback != null && typeof  errorCallback != 'undefined'){
                        errorCallback(response);
                    }
                });
            },
            post:function(url,data,successCallback,errorCallback,finalCallback){
                $http.post(url,data,{headers:{"X-Requested-With":"xmlhttprequest"}}).then(function(response){
                    try{
                        if(_check_response(response) && successCallback != null &&  typeof  successCallback != 'undefined') {
                            successCallback(response);
                        }
                    }catch (err){

                    }
                    if( finalCallback != null &&  typeof  finalCallback != 'undefined') {
                        finalCallback(response);
                    }
                },function(response){
                    try{
                        if(_check_response(response) && errorCallback != null && typeof  errorCallback != 'undefined') {
                            errorCallback(response);
                        }
                    }catch(err){
                    }
                    if( finalCallback != null &&  typeof  finalCallback != 'undefined') {
                        finalCallback(response);
                    }
                });
            },
            delete:function(url,successCallback,errorCallback){
                $http.delete(url,{headers:{"X-Requested-With":"xmlhttprequest"}}).then(function(response){
                    if(_check_response(response) && successCallback != null &&  typeof  successCallback != 'undefined') {
                        successCallback(response);
                    }
                },function(response){
                    if(_check_response(response) && errorCallback != null && typeof  errorCallback != 'undefined'){
                        errorCallback(response);
                    }
                });
            }
        }
    }).service('mpLayer',function(){

        return {
            alert:function(msg){
                // alert(msg);
                layer.msg(msg);
            },
            confirm:function(content,btn,accuss,fail){
                layer.confirm(content, {
                    btn: btn,
                }, function(e){
                    if(accuss != undefined){
                        accuss(e);
                    }
                }, function(e) {
                    if(fail != undefined){
                        fail(e);
                    }
                });
            }
        }
    }).directive('myPagination', function () {
        // config
        var nextPage = "»";
        var previousPage = "«";
        nextPage = "下一页";
        previousPage = "上一页";
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                option: '=pageOption'
            },
            template: '<ul class="pager">' +
            '<li ng-click="pageClick(p)" ng-repeat="p in page" class="{{option.curr==p?\'active\':\'\'}}">' +
            '<a href="javascript:;">{{p}}</a>' +
            '</li>' +
            '</ul>',
            link: function ($scope) {
                //容错处理
                if (!$scope.option.curr || isNaN($scope.option.curr) || $scope.option.curr < 1) $scope.option.curr = 1;
                if (!$scope.option.all || isNaN($scope.option.all) || $scope.option.all < 1) $scope.option.all = 1;
                if ($scope.option.curr > $scope.option.all) $scope.option.curr = $scope.option.all;
                if (!$scope.option.count || isNaN($scope.option.count) || $scope.option.count < 1) $scope.option.count = 10;


                //得到显示页数的数组
                $scope.page = getRange($scope.option.curr, $scope.option.all, $scope.option.count);

                $scope.$watch('option.all',function(){
                    $scope.page = getRange($scope.option.curr, $scope.option.all, $scope.option.count);
                });
                $scope.$watch('option.curr',function(){
                    $scope.pageClick($scope.option.curr);
                });
                //绑定点击事件
                $scope.pageClick = function (page) {
                    if (page == previousPage) {
                        page = parseInt($scope.option.curr) - 1;
                    } else if (page == nextPage) {
                        page = parseInt($scope.option.curr) + 1;
                    }
                    if (page < 1) page = 1;
                    else if (page > $scope.option.all) page = $scope.option.all;
                    //点击相同的页数 不执行点击事件
                    if (page == $scope.option.curr) return;
                    if ($scope.option.click && typeof $scope.option.click === 'function') {
                        $scope.option.click(page);
                        $scope.option.curr = page;
                        $scope.page = getRange($scope.option.curr, $scope.option.all, $scope.option.count);
                    }
                };

                //返回页数范围（用来遍历）
                function getRange(curr, all, count) {
                    if(all == 1 || all == 0){
                        return  null;
                    }
                    //计算显示的页数
                    curr = parseInt(curr);
                    all = parseInt(all);
                    count = parseInt(count);
                    var from = curr - parseInt(count / 2);
                    var to = curr + parseInt(count / 2) + (count % 2) - 1;
                    //显示的页数容处理
                    if (from <= 0) {
                        from = 1;
                        to = from + count - 1;
                        if (to > all) {
                            to = all;
                        }
                    }
                    if (to > all) {
                        to = all;
                        from = to - count + 1;
                        if (from <= 0) {
                            from = 1;
                        }
                    }
                    var range = [];
                    for (var i = from; i <= to; i++) {
                        range.push(i);
                    }
                    range.push(nextPage);
                    range.unshift(previousPage);

                    return range;
                }

            }
        }
    });

})(window.angular);