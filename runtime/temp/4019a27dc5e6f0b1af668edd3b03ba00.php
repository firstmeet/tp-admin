<?php /*a:2:{s:64:"/www/wwwroot/mf.iirr5.com/application/user/view/index/index.html";i:1556090954;s:59:"/www/wwwroot/mf.iirr5.com/application/user/view/layout.html";i:1556090954;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>小程序商城</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="小程序商城"/>
    <link rel="icon" type="image/png" href="assets/user/i/favicon.ico"/>
    <link rel="stylesheet" href="assets/user/css/wy_modality.css"/>
    <link rel="stylesheet" href="/assets/user/vendors/iconfonts/mdi/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" href="/assets/user/vendors/css/vendor.bundle.base.css"/>
	<link rel="stylesheet" href="assets/layui/css/layui.css"  media="all">
    <script src="assets/user/js/jquery.min.js"></script>
    <script src="assets/user/js/global.js"></script>
    <script>
        BASE_URL = '<?php echo htmlentities($base_url); ?>';
        STORE_URL =  '/index.php?s=/user';
		
    </script>
</head>

<body data-type="">
<div class="layer-g tpl-g">
    <!-- 头部 -->
    <header class="tpl-header">
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
            <!-- 侧边切换 -->
            <div class="layer-fl tpl-header-button switch-button">
                <i class="iconfont icon-menufold"></i>
            </div>
            <!-- 刷新页面 -->
            <div class="layer-fl tpl-header-button refresh-button">
                <i class="iconfont icon-refresh"></i>
				<div class="tpl-header-text-gl"><a href="#">功能列表</a></div>
            </div>
            <!-- 其它功能-->
            <div class="layer-fr tpl-header-navbar">
                <ul>
                    <!-- 欢迎语 -->
                    <li class="layer-text-sm tpl-header-navbar-welcome">
                        <a href="<?php echo url("","",true,false);?>">欢迎你，<span><?php echo htmlentities($store['user_name']); ?></span>
                        </a>
                    </li>

                    <!-- 退出 -->
                    <li class="layer-text-sm">
                        <a href="<?php echo url('user/login/logout'); ?>">
                            <i class="iconfont icon-tuichu"></i> 退出
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar dis-flex">
        <!-- 一级菜单 -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading"><img class="logo" src="/assets/user/img/logo.png" width="60" /></li>
           <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
				<li class="sidebar-nav-link">
                    <a href="<?= isset($item['url']) ? url($item['url']) : 'javascript:void(0);' ?>"
                       class="<?php if($item['model'] == $group): ?> active <?php endif; ?>">
                            <i class="mdi sidebar-nav-link-logo menu-icon <?php echo htmlentities($item['icon']); ?>"></i>
                        <?php echo htmlentities($item['name']); ?>
                    </a>
                </li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- 子级菜单-->
		<?php $second = $menu[$group]; if(isset($second['list'])): ?>
            <ul class="left-sidebar-second">
                <!-- <li class="sidebar-second-title"><?php echo htmlentities($menu[$group]['name']); ?></li> -->
                <li class="sidebar-second-item">
                   
					<?php if(is_array($second['list']) || $second['list'] instanceof \think\Collection || $second['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $second['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
						
                            <!-- 二级菜单-->
                            <a href="<?php echo url($item['url']); ?>" class="two-active" style="">
                                <?php echo htmlentities($item['name']); ?>
                            </a>
                       
                            <!-- 三级菜单-->
                            <div class="sidebar-third-item">
                               <!--  <a href="javascript:void(0);"
                                   class="sidebar-nav-sub-title <?php echo !empty($item['active']) ? 'active'  :  ''; ?>">
                                    <i class="iconfont icon-caret"></i>
                                    <?php echo htmlentities($item['name']); ?>
                                </a>  -->
								<?php if(isset($item['list'])): ?>
								
                                <ul class="sidebar-third-nav-sub">
								
									<?php if(is_array($item['list']) || $item['list'] instanceof \think\Collection || $item['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$third): $mod = ($i % 2 );++$i;?>
                                        <li>
                                            <a class="<?php echo $third['url']==$url ? 'active'  :  ''; ?>"
                                               href="<?php echo url($third['url']); ?>">
                                                <?php echo htmlentities($third['name']); ?></a>
                                        </li>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
									
                                </ul>
								<?php endif; ?>
                            </div>
                      
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- 内容区域 start -->
    <div class="tpl-content-wrapper <?php if(isset($second['list']) == null): ?>no-sidebar-second<?php endif; ?>">
	
        <link rel="stylesheet" href="assets/user/css/swiper.min.css">
<div class="page-home row-content layer-cf">

    <!-- 商城统计 -->
    <div class="row">
        <div class="layui-col-md12 layui-col-xs12">
            <div class="widget layui-col-md12 widget-bff">
                <div class="widget-head">
                    <div class="widget-title">商城统计</div>
                </div>
                <div class="widget-body layer-cf ">
                    <div class="layui-col-sm12 layui-col-md6 layui-col-lg3 lay-rouw ">
                 
						<div class="stat-number">
							<div class="volume vol-blue-left">
								<p>商品总量</p>
								<h3><?php echo htmlentities($data['widget-card']['goods_total']); ?></h3>
							</div>
							<div class="icon-volume vol-blue-right">
								<span class="mdi menu-icon mdi-gift"></span>
							</div>
						</div>
                    </div>

                    <div class="layui-col-sm12 layui-col-md6 layui-col-lg3 lay-rouw ">
                     
						<div class="stat-number">
							<div class="volume vol-purple-left">
								<p>用户总量</p>
								<h3><?php echo htmlentities($data['widget-card']['user_total']); ?></h3>
							</div>
							<div class="icon-volume vol-purple-right">
                                <span class="mdi menu-icon mdi-account"></span>
							</div>
						</div>
                    </div>

                    <div class="layui-col-sm12 layui-col-md6 layui-col-lg3 lay-rouw ">
                     
						<div class="stat-number">
							<div class="volume vol-green-left">
								<p>订单总量</p>
								<h3><?php echo htmlentities($data['widget-card']['order_total']); ?></h3>
							</div>
							<div class="icon-volume vol-green-right">
									<span class="mdi menu-icon mdi-clipboard-text"></span>
							</div>
						</div>
                    </div>

                    <div class="layui-col-sm12 layui-col-md6 layui-col-lg3 lay-rouw ">
                   
						<div class="stat-number">
							<div class="volume vol-yellow-left">
								<p>评价总量</p>
								<h3><?php echo htmlentities($data['widget-card']['comment_total']); ?></h3>
							</div>
							<div class="icon-volume vol-yellow-right">
									<span class="mdi menu-icon mdi-star-half"></span>
							</div>
						</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 实时概况 -->
    <div class="row">
		<div class="layui-col-sm9">
		<!-- 近七日交易走势 -->
			<div class="layui-col-md12 layui-col-xs12" style="padding:0;">
				<div class="widget layer-cf  widget-bff">
					<div class="widget-head">
						<div class="widget-title">近七日交易走势</div>
					</div>
					<div class="widget-body layer-cf">
						<div id="echarts-trade" class="widget-echarts"></div>
					</div>
				</div>
			</div>
			<div class="layui-col-md12 layui-col-xs12" style="padding:0;">
				<div class="widget layer-cf  widget-bff">
					<div class="widget-head">
						<div class="widget-title ">登录日志</div>
						
					</div>
					
					<table width="100%" class="layui-table">
						<thead>
						<tr>
							<th>ID</th>
							<th>用户名</th>
							<th>类型</th>
							<th>权限组</th>
							<th>登录ip</th>
							<th>登录时间</th>
						</tr>
						</thead>
						<tbody>
						<?php if(!$log->isEmpty()): foreach($log as $list): ?>
							<tr>
								<td><?php echo htmlentities($list['id']); ?></td>
								<td><?php echo htmlentities($list['user']['user_name']); ?></td>
								<td><?php echo $list['user']['is_super']==0 ? '主账号' : '子账号'; ?></td>
								<td></td>
								<td><?php echo htmlentities($list['ip']); ?></td>
								<td><?php echo htmlentities($list['create_time']); ?></td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td colspan="12" class="layer-text-center">暂无记录</td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="layui-col-sm2 laui-col-width" >
			<div class="widget layer-cf  widget-bff" style="padding:0;">			
				<div class="widget-head "style="padding:10px 20px 10px;">
					<div class="widget-title">当前版本:<?php echo htmlentities($version['ver']); ?></div>
				</div>
				<div >
					<div class="ly-genx">
						<div class="ly-gengxin"><span>检查更新</span></div>
					</div>
				</div>
				<div class="in-new-list">
					
				</div>
			</div>
		</div>
		<div class="layui-col-sm2 laui-col-width" >
			<div class="widget layer-cf  widget-bff" >			
				<div class="widget-head ">
					<div class="widget-title">实时数据</div>
				</div>
				<div class="layui-col-flex">
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_total_price']['tday']); ?></strong>
						<p>销售额</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_total_price']['ytd']); ?></strong>
						<p>昨日销售额</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_total']['tday']); ?></strong>
						<p>支付订单数</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_total']['ytd']); ?></strong>
						<p>昨日支付订单数</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['new_user_total']['tday']); ?></strong>
						<p>新增用户数</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['new_user_total']['ytd']); ?></strong>
						<p>昨日新增用户数</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_user_total']['tday']); ?></strong>
						<p>下单用户数</p>
					</div>
					<div class="layui-in-list-1">
						<strong><?php echo htmlentities($data['widget-outline']['order_user_total']['ytd']); ?></strong>
						<p>昨日下单用户数</p>
					</div>
				</div>
			</div>
		</div>
    </div>

</div>
<script src="assets/user/js/echarts.min.js"></script>
<script src="assets/user/js/echarts-walden.js"></script>
<script type="text/javascript">

    /**
     * 近七日交易走势
     * @type {HTMLElement}
     */
    var dom = document.getElementById('echarts-trade');
    echarts.init(dom, 'walden').setOption({
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['成交量', '成交额']
        },
        toolbox: {
            show: true,
            showTitle: false,
            feature: {
                mark: {show: true},
                magicType: {show: true, type: ['line', 'bar']}
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: <?= $data['widget-echarts']['date'] ?>
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name: '成交额',
                type: 'line',
                data: <?= $data['widget-echarts']['order_total_price'] ?>
            },
            {
                name: '成交量',
                type: 'line',
                data: <?= $data['widget-echarts']['order_total'] ?>
            }
        ]
    }, true);

</script>
 <!-- Swiper JS -->
  <script src="assets/user/js/swiper.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper('.swiper-container', {
		loop : true,
		autoplay:true,
		pagination: {
			el: '.swiper-pagination',
		  },
    });
	var strs;
	$(function () {

		$('.ly-gengxin')._get('', "<?php echo url('index/update'); ?>",'升级前请先备份好程序和数据库！');

		$.ajax({
			type: 'get',
			url: 'https://day.iirr5.com/',
			success: function (res) {
		
				console.log(res);
				var str = '',reslut = res.version, base = new Base64();
				for(var index in reslut){
					str += '<p data-type="test15"  data-ver='+reslut[index].name+' data-datae='+reslut[index].datae+' data-time="'+RiQi(reslut[index].time)+'" data-cont="'+reslut[index].content+'">';
					str += '	<em>V'+reslut[index].name+'</em>';
					str += '	<b>['+RiQi(reslut[index].time)+']</b>';
					str += '</p>';
				}
				strs = str;
				$('.in-new-list').html(str);
			}
		})
	});
	
	$(document).on('click','.in-new-list p',function(){
		var str ='';
		var cont =$(this).attr('data-cont');
		var datae =$(this).attr('data-datae');
		var base = new Base64();
		str += '<div class="layui-col-md12 genxin">';
		str += ' <div class="layui-card">';
        str += '<div class="layui-card-header genxin-time"><h3 style="margin-bottom:0; margin-top:30px;">版本号: '+$(this).attr('data-ver')+'</h3> <span> 更新日期: '+$(this).attr('data-time')+'</span></div>';
		if(cont){
        str += '<div class="layui-card-body genxin-center">';
		str += '<h3 style="text-align:left">网站更新</h3>';
        str += '<p>'+base.decode(cont)+'</p>';
		}
		if(datae){
			str += '<h3 style="text-align:left">数据库更新</h3>';
			str += '<p>'+base.decode(datae)+'</p>';
			if(cont){
				str += ' </div>';
				str += '</div>';
				str += '</div>';
				layer.open({
				  type: 1,
				   title: '更新记录', //不显示标题
				  area: ['700px', 'auto'], //宽高
				  content: str
				})			
			}		
		}
	})
	
	function RiQi(sj)
	{
		var now = new Date(sj*1000);
		var   year=now.getFullYear();    
		var   month=(now.getMonth()+1 < 10 ? '0' + (now.getMonth()+1) : now.getMonth()+1);    
		var   date=now.getDate() < 10 ?  '0'+now.getDate()+ ' ' : now.getDate()+ ' ';
		var   hour=now.getHours() < 10 ? '0'+now.getHours()+ ':' : now.getHours();
		var   minute=now.getMinutes() < 10 ? '0'+now.getMinutes()+ ':' : now.getMinutes();   
		var   second=now.getSeconds() < 10 ? '0'+now.getSeconds() : now.getSeconds();
		return   year+"-"+month+"-"+date+"   "+hour+":"+minute+second;    
	}
	
	Base64 = function() {
 
		// private property
		_keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	 
		// public method for encoding
		this.encode = function (input) {
			var output = "";
			var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
			var i = 0;
			input = _utf8_encode(input);
			while (i < input.length) {
				chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);
				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;
				if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				} else if (isNaN(chr3)) {
					enc4 = 64;
				}
				output = output +
				_keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
				_keyStr.charAt(enc3) + _keyStr.charAt(enc4);
			}
			return output;
		}
	 
		// public method for decoding
		this.decode = function (input) {
			var output = "";
			var chr1, chr2, chr3;
			var enc1, enc2, enc3, enc4;
			var i = 0;
			input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
			while (i < input.length) {
				enc1 = _keyStr.indexOf(input.charAt(i++));
				enc2 = _keyStr.indexOf(input.charAt(i++));
				enc3 = _keyStr.indexOf(input.charAt(i++));
				enc4 = _keyStr.indexOf(input.charAt(i++));
				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;
				output = output + String.fromCharCode(chr1);
				if (enc3 != 64) {
					output = output + String.fromCharCode(chr2);
				}
				if (enc4 != 64) {
					output = output + String.fromCharCode(chr3);
				}
			}
			output = _utf8_decode(output);
			return output;
		}
	 
		// private method for UTF-8 encoding
		_utf8_encode = function (string) {
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
			for (var n = 0; n < string.length; n++) {
				var c = string.charCodeAt(n);
				if (c < 128) {
					utftext += String.fromCharCode(c);
				} else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				} else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
	 
			}
			return utftext;
		}
	 
		// private method for UTF-8 decoding
		_utf8_decode = function (utftext) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
			while ( i < utftext.length ) {
				c = utftext.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				} else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				} else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		}
}
  </script>
    </div>
    <!-- 内容区域 end -->

</div>
    <script src="assets/user/js/validform.min.js"></script> <!-- 提交 -->
    <script src="assets/user/js/jquery.form.min.js"></script>
    <script src="assets/user/js/webuploader.html5only.js"></script>
	<script src="assets/user/js/art-template.js"></script>
	<script src="assets/user/js/app.js"></script>
	<script src="assets/user/js/file.library.js"></script>
	<script src="/assets/layui/layui.all.js" charset="utf-8"></script>
</body>
</html>
