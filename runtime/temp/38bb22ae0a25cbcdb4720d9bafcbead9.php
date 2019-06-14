<?php /*a:3:{s:66:"/www/wwwroot/mf.iirr5.com/application/user/view/setting/store.html";i:1556091498;s:59:"/www/wwwroot/mf.iirr5.com/application/user/view/layout.html";i:1556090954;s:83:"/www/wwwroot/mf.iirr5.com/application/user/view/layouts/_template/file_library.html";i:1556090954;}*/ ?>
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
	
        <div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-sm12 layui-col-md12 layui-col-lg12">
			<div class="widget-head">
				<div class="widget-title">网站设置</div></div>
			<form id="my-form" class="layui-form" action="<?php echo url("","",true,false);?>" enctype="multipart/form-data" method="post">
				<div class="widget-body layui-col-lg12">
					<div class="layui-tab layui-tab-brief" lay-filter="test">
						<ul class="layui-tab-title">
							<li class="layui-this">基础设置</li>
							<li>交易设置</li>
							<li>短信通知</li>
							<li>模板消息</li>
							<li>上传设置</li>
						</ul>
						<div class="layui-tab-content">
							<div class="layui-tab-item layui-show">
								<div class="widget-head">
									<div class="widget-title ">商城设置</div></div>
								<div class="layui-form-item">
									<label class="layui-form-label">网站备案号:</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[store][record_no]" value="<?php echo isset($values['store']['values']['record_no']) ? htmlentities($values['store']['values']['record_no']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">网站名称:</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][name]" value="<?php echo isset($values['store']['values']['name']) ? htmlentities($values['store']['values']['name']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">网站标题:</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][store_title]" value="<?php echo isset($values['store']['values']['store_title']) ? htmlentities($values['store']['values']['store_title']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">网站描述:</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][store_desc]" value="<?php echo isset($values['store']['values']['store_desc']) ? htmlentities($values['store']['values']['store_desc']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">联系人:</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][contact]" value="<?php echo isset($values['store']['values']['contact']) ? htmlentities($values['store']['values']['contact']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">联系电话：</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][phone]" value="<?php echo isset($values['store']['values']['phone']) ? htmlentities($values['store']['values']['phone']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">具体地址：</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[store][address]" value="<?php echo isset($values['store']['values']['address']) ? htmlentities($values['store']['values']['address']) : ''; ?>"></div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="widget-head">
									<div class="widget-title ">订单流程设置</div></div>
								<div class="layui-form-item">
									<label class="layui-form-label">未支付订单</label>
									<div class="layui-input-block  ">
										<div class="layui-col-sm4">
											<input type="number" class="layui-input" name="setting[trade][order][close_days]" value="<?php echo isset($values['trade']['values']['order']['close_days']) ? htmlentities($values['trade']['values']['order']['close_days']) : 0; ?>" class="layui-input" pattern="^(0|\+?[1-9][0-9]*)$"></div>
										<div class="layui-form-mid layui-word-aux">天后自动关闭</div>
										<div class="help-block layui-col-sm12">
											<small>订单下单未付款，n天后自动关闭，设置0天不自动关闭</small></div>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">已发货订单</label>
									<div class="layui-input-block  ">
										<div class="layui-col-sm4">
											<input type="number" class="layui-input" name="setting[trade][order][receive_days]" value="<?php echo isset($values['trade']['values']['order']['receive_days']) ? htmlentities($values['trade']['values']['order']['receive_days']) : 0; ?>" pattern="^(0|\+?[1-9][0-9]*)$"></div>
										<div class="layui-form-mid layui-word-aux">天后自动确认收货</div>
										<div class="help-block layui-col-sm12">
											<small>如果在期间未确认收货，系统自动完成收货，设置0天不自动收货</small></div>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">售后订单</label>
									<div class="layui-input-block  ">
										<div class="layui-col-sm4">
											<input type="number" class="layui-input" name="setting[trade][order][sale]" value="<?php echo isset($values['trade']['values']['order']['sale']) ? htmlentities($values['trade']['values']['order']['sale']) : 0; ?>" pattern="^(0|\+?[1-9][0-9]*)$"></div>
										<div class="layui-form-mid layui-word-aux">天后自动进入分佣</div>
										<div class="layui-col-sm12">
											<small>订单未申请售后,n天后将禁止再次申请,直接进入分佣</small></div>
									</div>
								</div>
								<div class="widget-head">
									<div class="widget-title">运费设置</div></div>
								<div class="layui-form-item">
									<div class="layui-form-item">
										<label class="layui-form-label">组合策略</label>
										<div class="layui-input-block">
											<input type="radio" name="setting[trade][freight_rule]" value="10" <?php if(!(empty($values[ 'trade'][ 'values'][ 'freight_rule']) || (($values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Collection || $values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Paginator ) && $values[ 'trade'][ 'values'][ 'freight_rule']->isEmpty()))): ?><?php echo $values[ 'trade'][ 'values'][ 'freight_rule']==='10' ? 'checked'  :  ''; ?><?php endif; ?>>运费叠加</div>
										<div class="layui-input-block">
											<small>订单中的商品有多个运费模板时，将每个商品的运费之和订为订单总运费</small></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label"></label>
										<div class="layui-input-block">
											<input type="radio" name="setting[trade][freight_rule]" value="20" <?php if(!(empty($values[ 'trade'][ 'values'][ 'freight_rule']) || (($values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Collection || $values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Paginator ) && $values[ 'trade'][ 'values'][ 'freight_rule']->isEmpty()))): ?><?php echo $values[ 'trade'][ 'values'][ 'freight_rule']==='20' ? 'checked'  :  ''; ?><?php endif; ?>>以最低运费结算</div>
										<div class="layui-input-block">
											<small>订单中的商品有多个运费模板时，取订单中运费最少的商品的运费计为订单总运费</small></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label"></label>
										<div class="layui-input-block">
											<input type="radio" name="setting[trade][freight_rule]" value="30" <?php if(!(empty($values[ 'trade'][ 'values'][ 'freight_rule']) || (($values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Collection || $values[ 'trade'][ 'values'][ 'freight_rule'] instanceof \think\Paginator ) && $values[ 'trade'][ 'values'][ 'freight_rule']->isEmpty()))): ?><?php echo $values[ 'trade'][ 'values'][ 'freight_rule']==='30' ? 'checked'  :  ''; ?><?php endif; ?>>以最高运费结算</div>
										<div class="layui-input-block">
											<small>订单中的商品有多个运费模板时，取订单中运费最多的商品的运费计为订单总运费</small></div>
									</div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="widget-head">
									<div class="widget-title ">短信通知（阿里云短信）</div></div>
								<input type="hidden" name="setting[sms][default]" value="aliyun">
								<div class="layui-form-item">
									<label class="layui-form-label">AccessKeyId</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[sms][engine][aliyun][AccessKeyId]" value="<?php echo isset($values['sms']['values']['engine']['aliyun']['AccessKeyId']) ? htmlentities($values['sms']['values']['engine']['aliyun']['AccessKeyId']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">AccessKeySecret</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[sms][engine][aliyun][AccessKeySecret]" value="<?php echo isset($values['sms']['values']['engine']['aliyun']['AccessKeySecret']) ? htmlentities($values['sms']['values']['engine']['aliyun']['AccessKeySecret']) : ''; ?>"></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">短信签名</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[sms][engine][aliyun][sign]" value="<?php echo isset($values['sms']['values']['engine']['aliyun']['sign']) ? htmlentities($values['sms']['values']['engine']['aliyun']['sign']) : ''; ?>"></div>
								</div>
								<div class="widget-head">
									<div class="widget-title ">新付款订单提醒</div></div>
								<div class="layui-form-item">
									<label class="layui-form-label">是否开启短信提醒</label>
									<div class="layui-input-block ">
										<label class="layui-inline">
											<input type="radio" name="setting[sms][engine][aliyun][order_pay][is_enable]" value="1" <?php if(!(empty($values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']) || (($values['sms']['values']['engine']['aliyun']['order_pay']['is_enable'] instanceof \think\Collection || $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable'] instanceof \think\Paginator ) && $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']->isEmpty()))): ?><?php echo $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']==='1' ? 'checked'  :  ''; ?><?php endif; ?>>开启</label>
										<label class="layui-inline">
											<input type="radio" name="setting[sms][engine][aliyun][order_pay][is_enable]" value="2" <?php if(!(empty($values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']) || (($values['sms']['values']['engine']['aliyun']['order_pay']['is_enable'] instanceof \think\Collection || $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable'] instanceof \think\Paginator ) && $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']->isEmpty()))): ?><?php echo $values['sms']['values']['engine']['aliyun']['order_pay']['is_enable']==='2' ? 'checked'  :  ''; ?><?php endif; ?>>关闭</label></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">模板ID
										<span class="">Template Code</span></label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[sms][engine][aliyun][order_pay][template_code]" value="<?php echo isset($values['sms']['values']['engine']['aliyun']['order_pay']['template_code']) ? htmlentities($values['sms']['values']['engine']['aliyun']['order_pay']['template_code']) : ''; ?>">
										<small>例如：SMS_139800030</small></div>
								</div>
								<div class="layui-form-item">
									<div class="layui-input-block layui-col-smpush-3">
										<small>模板内容：您有一条新订单，订单号为：123456789 ，请注意查看。</small></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">接收手机号</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[sms][engine][aliyun][order_pay][accept_phone]" value="<?php echo isset($values['sms']['values']['engine']['aliyun']['order_pay']['accept_phone']) ? htmlentities($values['sms']['values']['engine']['aliyun']['order_pay']['accept_phone']) : ''; ?>">
										<div class="help-block">
											<small>接收测试:
												<a class="j-sendTestMsg" data-msg-type="order_pay" href="javascript:void(0);">点击发送</a></small>
										</div>
									</div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="widget-head">
									<div class="widget-title ">支付成功通知</div></div>
								<div class="layui-form-item ">
									<label class="layui-form-label">是否启用</label>
									<div class="layui-input-block">
										<label class="layui-inline">
											<input type="radio" name="setting[tplMsg][payment][is_enable]" value="1" <?php if(!(empty($values['tplMsg']['values']['payment']['is_enable']) || (($values['tplMsg']['values']['payment']['is_enable'] instanceof \think\Collection || $values['tplMsg']['values']['payment']['is_enable'] instanceof \think\Paginator ) && $values['tplMsg']['values']['payment']['is_enable']->isEmpty()))): ?><?php echo $values['tplMsg']['values']['payment']['is_enable']==='1' ? 'checked'  :  ''; ?><?php endif; ?>>开启</label>
										<label class="layui-inline">
											<input type="radio" name="setting[tplMsg][payment][is_enable]" value="2" <?php if(!(empty($values['tplMsg']['values']['payment']['is_enable']) || (($values['tplMsg']['values']['payment']['is_enable'] instanceof \think\Collection || $values['tplMsg']['values']['payment']['is_enable'] instanceof \think\Paginator ) && $values['tplMsg']['values']['payment']['is_enable']->isEmpty()))): ?><?php echo $values['tplMsg']['values']['payment']['is_enable']==='2' ? 'checked'  :  ''; ?><?php endif; ?>>关闭</label>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">模板消息ID
										<span class="tpl-form-line-small-title">Template ID</span></label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[tplMsg][payment][template_id])" value="<?php echo isset($values['tplMsg']['values']['payment']['template_id']) ? htmlentities($values['tplMsg']['values']['payment']['template_id']) : ''; ?>">
										<div class="help-block layer-margin-top-xs">
											<small>模板编号AT0009，关键词 (订单编号、支付时间、订单金额、商品名称)</small>
										</div>
									</div>
								</div>
								<div class="widget-head">
									<div class="widget-title ">订单发货通知</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">是否启用</label>
									<div class="layui-input-block">
										<label class="layui-inline ">
											<input type="radio" name="setting[tplMsg][delivery][is_enable]" value="1" <?php if(!(empty($values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']) || (($values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable'] instanceof \think\Collection || $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable'] instanceof \think\Paginator ) && $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']->isEmpty()))): ?><?php echo $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']==='1' ? 'checked'  :  ''; ?><?php endif; ?> >开启</label>
										<label class="layui-inline ">
											<input type="radio" name="setting[tplMsg][delivery][is_enable]" value="2" <?php if(!(empty($values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']) || (($values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable'] instanceof \think\Collection || $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable'] instanceof \think\Paginator ) && $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']->isEmpty()))): ?><?php echo $values[ 'tplMsg'][ 'values'][ 'delivery'][ 'is_enable']==='2' ? 'checked'  :  ''; ?> <?php endif; ?>>关闭</label></div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">模板消息ID
										<span class="tpl-form-line-small-title">Template ID</span></label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[tplMsg][delivery][template_id]" value="<?php echo isset($values['tplMsg']['values']['delivery']['template_id']) ? htmlentities($values['tplMsg']['values']['delivery']['template_id']) : ''; ?>">
										<small>模板编号AT0007，关键词 (订单编号、商品信息、收货人、收货地址、物流公司、物流单号)</small></div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="widget-head">
									<div class="widget-title ">文件上传设置</div></div>
								<div class="layui-form-item">
									<label class="layui-form-label">默认上传方式</label>
									<div class="layui-input-block ">
										<label class="layui-inline">
											<input type="radio" name="setting[storage][default]" value="local" <?php if(!(empty($values[ 'storage'][ 'values'][ 'default']) || (($values[ 'storage'][ 'values'][ 'default'] instanceof \think\Collection || $values[ 'storage'][ 'values'][ 'default'] instanceof \think\Paginator ) && $values[ 'storage'][ 'values'][ 'default']->isEmpty()))): ?><?php echo $values[ 'storage'][ 'values'][ 'default']==='local' ? 'checked'  :  ''; ?><?php endif; ?>>本地 (不推荐)</label>
										<label class="layui-inline">
											<input type="radio" name="setting[storage][default]" value="qiniu" <?php if(!(empty($values[ 'storage'][ 'values'][ 'default']) || (($values[ 'storage'][ 'values'][ 'default'] instanceof \think\Collection || $values[ 'storage'][ 'values'][ 'default'] instanceof \think\Paginator ) && $values[ 'storage'][ 'values'][ 'default']->isEmpty()))): ?><?php echo $values[ 'storage'][ 'values'][ 'default']==='qiniu' ? 'checked'  :  ''; ?><?php endif; ?>>七牛云存储</label>
										<label class="layui-inline">
											<input type="radio" name="setting[storage][default]" value="aliyun" <?php if(!(empty($values[ 'storage'][ 'values'][ 'default']) || (($values[ 'storage'][ 'values'][ 'default'] instanceof \think\Collection || $values[ 'storage'][ 'values'][ 'default'] instanceof \think\Paginator ) && $values[ 'storage'][ 'values'][ 'default']->isEmpty()))): ?><?php echo $values[ 'storage'][ 'values'][ 'default']==='aliyun' ? 'checked'  :  ''; ?><?php endif; ?>>阿里云OSS</label>
										<label class="layui-inline">
											<input type="radio" name="setting[storage][default]" value="qcloud" <?php if(!(empty($values[ 'storage'][ 'values'][ 'default']) || (($values[ 'storage'][ 'values'][ 'default'] instanceof \think\Collection || $values[ 'storage'][ 'values'][ 'default'] instanceof \think\Paginator ) && $values[ 'storage'][ 'values'][ 'default']->isEmpty()))): ?><?php echo $values[ 'storage'][ 'values'][ 'default']==='qcloud' ? 'checked'  :  ''; ?><?php endif; ?>>腾讯云COS</label></div>
								</div>
								<div id="qiniu" class="form-tab-group {!empty($values['storage']['values']['default']) ? 'qiniu' : 'active' }">
									<div class="layui-form-item">
										<label class="layui-form-label">存储空间名称
											<span class="tpl-form-line-small-title">Bucket</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qiniu][bucket]" value="<?php echo isset($values['storage']['values']['engine']['qiniu']['bucket']) ? htmlentities($values['storage']['values']['engine']['qiniu']['bucket']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">ACCESS_KEY
											<span class="tpl-form-line-small-title">AK</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qiniu][access_key]" value="<?php echo isset($values['storage']['values']['engine']['qiniu']['access_key']) ? htmlentities($values['storage']['values']['engine']['qiniu']['access_key']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">SECRET_KEY
											<span class="tpl-form-line-small-title">SK</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qiniu][secret_key]" value="<?php echo isset($values['storage']['values']['engine']['qiniu']['secret_key']) ? htmlentities($values['storage']['values']['engine']['qiniu']['secret_key']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">空间域名
											<span class="tpl-form-line-small-title">Domain</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qiniu][domain]" value="<?php echo isset($values['storage']['values']['engine']['qiniu']['domain']) ? htmlentities($values['storage']['values']['engine']['qiniu']['domain']) : ''; ?>">
											<small>请补全http:// 或 https://，例如：http://static.cloud.com</small></div>
									</div>
								</div>
								<div id="aliyun" class="form-tab-group {!empty($values['storage']['values']['default']) ? 'aliyun' : 'active'}">
									<div class="layui-form-item">
										<label class="layui-form-label">存储空间名称
											<span class="tpl-form-line-small-title">Bucket</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][aliyun][bucket]" value="<?php echo isset($values['storage']['values']['engine']['aliyun']['bucket']) ? htmlentities($values['storage']['values']['engine']['aliyun']['bucket']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">AccessKeyId</label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][aliyun][access_key_id]" value="<?php echo isset($values['storage']['values']['engine']['aliyun']['access_key_id']) ? htmlentities($values['storage']['values']['engine']['aliyun']['access_key_id']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">AccessKeySecret</label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][aliyun][access_key_secret]" value="<?php echo isset($values['storage']['values']['engine']['aliyun']['access_key_secret']) ? htmlentities($values['storage']['values']['engine']['aliyun']['access_key_secret']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">空间域名
											<span class="tpl-form-line-small-title">Domain</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][aliyun][domain]" value="<?php echo isset($values['storage']['values']['engine']['aliyun']['domain']) ? htmlentities($values['storage']['values']['engine']['aliyun']['domain']) : ''; ?>">
											<small>请补全http:// 或 https://，例如：http://static.cloud.com</small></div>
									</div>
								</div>
								<div id="qcloud" class="form-tab-group {!empty($values['storage']['values']['default'] ? 'qcloud' : 'active'}">
									<div class="layui-form-item">
										<label class="layui-form-label">存储空间名称
											<span class="tpl-form-line-small-title">Bucket</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qcloud][bucket]" value="<?php echo isset($values['storage']['values']['engine']['qcloud']['bucket']) ? htmlentities($values['storage']['values']['engine']['qcloud']['bucket']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">所属地域
											<span class="tpl-form-line-small-title">Region</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qcloud][region]" value="<?php echo isset($values['storage']['values']['engine']['qcloud']['region']) ? htmlentities($values['storage']['values']['engine']['qcloud']['region']) : ''; ?>">
											<small>请填写地域简称，例如：ap-beijing、ap-hongkong、eu-frankfurt</small></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">SecretId</label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qcloud][secret_id]" value="<?php echo isset($values['storage']['values']['engine']['qcloud']['secret_id']) ? htmlentities($values['storage']['values']['engine']['qcloud']['secret_id']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">SecretKey</label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qcloud][secret_key]" value="<?php echo isset($values['storage']['values']['engine']['qcloud']['secret_key']) ? htmlentities($values['storage']['values']['engine']['qcloud']['secret_key']) : ''; ?>"></div>
									</div>
									<div class="layui-form-item">
										<label class="layui-form-label">空间域名
											<span class="tpl-form-line-small-title">Domain</span></label>
										<div class="layui-input-block ">
											<input type="text" class="layui-input" name="setting[storage][engine][qcloud][domain]" value="<?php echo isset($values['storage']['values']['engine']['qcloud']['domain']) ? htmlentities($values['storage']['values']['engine']['qcloud']['domain']) : ''; ?>">
											<small>请补全http:// 或 https://，例如：http://static.cloud.com</small></div>
									</div>
								</div>
							</div>

						<div class="layui-form-item">
						<button type="submit" class="j-submit layui-btn ">提交</button>
						</div>
					</div>
					
				</div>
		</div>
		</form>
    </div>
</div>
</div>
<!-- 图片文件列表模板 -->
<!-- 文件库弹窗 --><!-- 文件库模板 -->
<script id="tpl-file-library" type="text/template">
    <div class="row">
        <div class="file-group">
            <ul class="nav-new">
                <li class="ng-scope {{ is_default ? 'active' : '' }}" data-group-id="-1">
                    <a class="group-name layui-text-truncate" href="javascript:void(0);" title="全部">全部</a>
                </li>
                <li class="ng-scope" data-group-id="0">
                    <a class="group-name" href="javascript:void(0);" title="未分组">未分组</a>
                </li>
                {{ each group_list }}
                <li class="ng-scope"  data-group-id="{{ $value.group_id }}" title="{{ $value.group_name }}">
                    <a class="group-edit" href="javascript:void(0);" title="编辑分组">
                        <i class="iconfont icon-bianji"></i>
                    </a>
                    <a class="group-name" href="javascript:void(0);">
                        {{ $value.group_name }}
                    </a>
                    <a class="group-delete" href="javascript:void(0);" title="删除分组">
                        <i class="iconfont icon-shanchu1"></i>
                    </a>
                </li>
                {{ /each }}
            </ul>
            <a class="group-add" href="javascript:void(0);">新增分组</a>
        </div>
        <div class="file-list">
            <div class="v-box-header">
                <div class="h-left layui-col-flex">
					<div class="group-select">
						<button type="button" class="group-select layui-btn layer-dropdown">
							移动至 <span class="layer-icon-caret-down"></span>
						</button>
						
					  <!--   <ul class="group-list ">
							<li>请选择分组</li>
							{{ each group_list }}
							<li>
								<a class="move-file-group" data-group-id="{{ $value.group_id }}"
								   href="javascript:void(0);">{{ $value.group_name }}</a>
							</li>
							{{ /each }}
						</ul> -->
						
						<div class="layui-form-item">
							<label class="layui-form-label">请选择分组</label>
							<div class="layui-input-block">
							 
							  <select class="form-control" name="city" lay-verify="required"> 
							  {{ each group_list }}
								<option value="{{ $value.group_id }}" data-group-id="{{ $value.group_id }}">{{ $value.group_name }}</option>
							 {{ /each }}
							  </select>
							 
							</div>
						</div>
					</div>
						
						
					<div class="h-rigth layer-fl layui-input-block">
						<div class="j-upload upload-image">
							<i class="iconfont icon-add1"></i>
							上传图片
						</div>
						
					</div>
					<div class="tpl-table-black-operation layer-fl">
							<a href="javascript:void(0);" class="layui-btn-warm file-delete tpl-table-black-operation-del" data-group-id="2">
								<i class="mdi menu-icon mdi-delete-forever"></i> 删除
							</a>
						</div>
                </div>	
            </div>
            <div id="file-list-body" class="v-box-body">
                {{ include 'tpl-file-list' file_list }}
            </div>
            <div class="v-box-footer"></div>
        </div>
    </div>

</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list" type="text/template">
    <ul class="file-list-item">
        {{ include 'tpl-file-list-item' data }}
    </ul>
    {{ if last_page > 1 }}
    <div class="file-page-box">
        <ul class="pagination">
            {{ if current_page > 1 }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="上一页" data-page="{{ current_page - 1 }}">«</a>
            </li>
            {{ /if }}
            {{ if current_page < last_page }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="下一页" data-page="{{ current_page + 1 }}">»</a>
            </li>
            {{ /if }}
        </ul>
    </div>
    {{ /if }}
</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list-item" type="text/template">
    {{ each $data }}
    <li class="ng-scope" title="{{ $value.file_name }}" data-file-id="{{ $value.id }}"
        data-file-path="{{ $value.file_path }}">
        <div class="img-cover"
             style="background-image: url('{{ $value.file_path }}')">
        </div>
        <p class="layui-word-aux">{{ $value.file_name }}</p>
        <div class="select-mask">
            <img src="assets/user/img/chose.png">
        </div>
    </li>
    {{ /each }}
</script>

<!-- 分组元素-->
<script id="tpl-group-item" type="text/template">
    <li class="ng-scope" data-group-id="{{ group_id }}" title="{{ group_name }}">
        <a class="group-edit" href="javascript:void(0);" title="编辑分组">
            <i class="iconfont icon-bianji"></i>
        </a>
        <a class="group-name layer-text-truncate" href="javascript:void(0);">
            {{ group_name }}
        </a>
        <a class="group-delete" href="javascript:void(0);" title="删除分组">
            <i class="iconfont icon-shanchu1"></i>
        </a>
    </li>
</script>
<script id="tpl-file-item" type="text/template">
    {{ each list }}
	
    <div class="file-item">
        <a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
            <img src="{{ $value.file_path }}">
        </a>
        <input type="hidden" name="{{ name }}" value="{{ $value.file_id}}">
        <i class="iconfont icon-shanchu file-item-delete"></i>
    </div>
    {{ /each }}
</script>

<script>$(function() {

        // 切换默认上传方式
        $("input:radio[name='setting[storage][default]']").click(function(e) {
            $('.form-tab-group').removeClass('active');
            switch (e.currentTarget.value) {
            case 'qiniu':
                $('#qiniu').addClass('active');
                break;
            case 'qcloud':
                $('#qcloud').addClass('active');
                break;
            case 'aliyun':
                $('#aliyun').addClass('active');
                break;
            case 'local':
                break;
            }
        });

    });

    $(function() {
        //地址的定位
        layui.use('element',
        function() {
            var $ = layui.jquery,
            element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });</script>
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
