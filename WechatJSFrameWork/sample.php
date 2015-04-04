<?php 
/**
*本文件是基本的jsSDK调用框架，在本文件中调用相应的JSSDK即可，
*由于不同的微信公众平台API权限不同所以需要将相应的appID 和appSecret 填入JSSDK的构造函数中
*本样例适用于简单功能开发，并提供ajax异步get post数据样例
*重要：
*   需要在jssdk 文件同级文件夹中包含
*                               1.access_token.json
*                               2.jsapi_ticket.json
*                这两个文件用于取得token和交换票据
*                "expire_time":0 表示过期时间，为0表示需要重新从服务器交换数据
*
*依赖：
*   1.jQuery
*   2.http://res.wx.qq.com/open/js/jweixin-1.0.0.js
*官方说明文档：
*http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
*
*   Author: CHENQUAN
*   Update_time:04/03/2015
*   Version:1.0
*   Email:tinycq@163.com
*   Author_url:http:www.tinycq.com
*/

require_once "jssdk.php";
//定义APPID,APPSECRET两个常量

define("APPID","your_appid");
define("APPSECRET","your_appsecret");

//构建对象并取得票据

$jssdk=new JSSDK( APPID, APPSECRET);
$signPackage=$jssdk->GetSignPackage(); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>WECHAT JS EXAMPLE</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <?php 
        /*
        *container 这个class用于包裹整个网页对象，js代码中实现了其对移动屏幕的自适应，同
        *时在样式表中定义了其背景的自适应
        
        */
    
    
    ?>
    <div class="container">
    <span>this is container</span>
    </div>
    <!--/container-->
    <!--jQuery引用-->
    <script src="js/jquery.1.9.1.min.js"></script>
    <!--微信分享接口-->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        $(function () {
            //自适应设置宽高
            $('.container').height($(window).height());
            $('.container').width($(window).width());
            
           //响应屏幕尺寸变化
            $(window).resize(function () {
                $('.container').height($(window).height());
                $('.container').width($(window).width());
            });
            //ajax get 请求初始化数据
            $.ajax({
                url: "data.json",
                type: "GET",
                //data:{trans_data:my_data},
                dataType: "json",
                error: function () {
                    console.log('sorry! data load faild,please reflash this page!');
                },
                success: function (data) {    
                    console.log(data);
                }
            });

            //按钮点击响应
            $('#btn').click(function () {
                //  console.log('clicked div:', this);
                }
                //点击数目ajax get回传
                var para  = 1;
                                   
                $.get("get_target.php?para=" + para, function (data) {
                    console.log("Data Loaded: " + data);
                
            });

            });
        });

        
       /*************************************************************************
       *
       *
       *    以下是微信api调用部分，所有的微信api的调用都需要在wx.ready(function(){ })中调用
       *    在调用api之前需要对其进行配置，这段代码不推荐压缩
       *
       *
       ************************************************************************/ 
        
        
        
        wx.config({
            debug: true,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <? php echo $signPackage["timestamp"]; ?> ,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            //需要调用的api list本文件中需要调用的api都需要在这里声明
            //所有的api列表在下面的说明文档的附录1 中有详细说明，需要注意的是，不同的微信公众号其权限不同，需要在后台查看其权限
            //http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo'
    ]
        });
        wx.ready(function () {
            // 在这里调用 API

            //分享到朋友圈
            var share = wx.onMenuShareTimeline({
                title: '分享标题',                     // 分享标题
                link: 'www.tinycq.php',               // 分享链接 不需要http
                imgUrl: 'http://www.example.com/ico.jpg', // 分享图标 需要http
                success: function () {
                    // 用户确认分享后执行的回调函数
                    //本样例中的static.php是一个统计处理页面，ajax请求之后将会提示成功
                    //如果不需要请重写本回调函数
                    $.get("static.php?static_num=1", function (data) {
                    console.log("success!");
                });
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log("cancle");
                }
            });
            //分享给朋友
            wx.onMenuShareAppMessage({
                title: '分享标题', // 分享标题
                desc: '分享描述', // 分享描述
                link: 'www.tinycq.php', // 分享链接
                imgUrl: 'http://http://www.example.com/ico.jpg', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                //点击数目ajax统计回传
                $.get("static.php?static_num=1", function (data) {
                    console.log("success!");
                });
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log("cancle!");
                }
            });

            //分享到qq
            wx.onMenuShareQQ({
                title: '分享标题', // 分享标题
                desc: '分享描述', // 分享描述
                link: 'www.tinycq.php', // 分享链接
                imgUrl: 'http://http://www.example.com/ico.jpg', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    $.get("static.php?static_num=1", function (data) {
                    console.log("success!");
                });
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log("cancle!");
                }
            });

            wx.onMenuShareWeibo({
                title: '分享标题', // 分享标题
                desc: '分享描述', // 分享描述
                link: 'www.tinycq.php', // 分享链接
                imgUrl: 'http://http://www.example.com/ico.jpg', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    $.get("static.php?static_num=1", function (data) {
                    console.log("success!");
                });

                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log("cancle!");
                }
            });


        });



        //下列函数是必须的，取得微信的服务器ip用于跨域请求
        function getServerIP(accessToken) {
            //创建XMLHttpRequest对象
            var xmlHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

            //获取值
            var accToken = accessToken;
            xmlHttp.open("open", "https://api.weixin.qq.com/cgi-bin/getcallbackip", true)
                //设置回调函数
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    document.getElementById("body").innerHTML = xmlHttp.responseText;
                    return true;
                } else {
                    return false;
                }
            }

            //发送请求,因为参数都在URL里,所以此处发送null
            xmlHttp.send(access_token = accToken);

        }
    </script>



</body>

</html>