<?php /*a:2:{s:50:"D:\www\tp\application\user\view\order\etcsale.html";i:1556090954;s:43:"D:\www\tp\application\user\view\layout.html";i:1556090954;}*/ ?>
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
				<div class="widget-title ">待处理售后</div>
			</div>
			<div class="layui-col-md12 layui-col-xs12 widget-body">
				<!-- 工具栏 -->
				<div class="page_toolbar layer-margin-bottom-xs layer-cf">
					<form id="form-search" class="layui-form" action="">
						<input type="hidden" name="s" value="/<?php echo htmlentities($request->pathinfo()); ?>">
						<input type="hidden" name="dataType" value="<?php echo htmlentities($dataType); ?>">
						<div class="layui-col-sm12 layui-col-md12">
							<div class="layer ">
								<div class="layui-col-md3 layui-form-item">
									<label class="layui-form-label">订单号：</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="order_no" placeholder="请输入订单号" value="<?php echo htmlentities($request->get('order_no')); ?>">
									</div>
								</div>							
								<div class="layui-col-md2 laui-col-width">
									<button type="submit" class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
								</div>
								<div class="layui-form-group">
									<a class="j-export layui-btn "
									   href="javascript:void(0);">
										<i class="mdi menu-icon mdi-logout"></i>订单导出
									</a>
									<?php if($dataType === 'delivery'): ?>
										<a class="j-export layui-btn "
										   href="<?php echo url('order/give'); ?>">
											<i class="mdi menu-icon mdi-export"></i>批量发货
										</a>
									<?php endif; ?>
								</div>
							 </div>	
						 </div>	
				</div>
					</form>

				<div class="layui-col-md12" >
					<table width="100%" class="layui-table">
						<thead>
						<tr>
							<th>商品信息</th>
							<th>单价/数量</th>
							<th>实付款</th>
							<th>买家</th>
							<th>售后方式</th>
							<th>操作</th>
							
						  
						</tr>
						</thead>
						<tbody>
						<?php if((!$list->isEmpty())): foreach($list as $order): ?>
							<tr> 
								<td class="layer-text-middle layer-text-left" colspan="6" style="border-bottom:none;">
									<span class="layer-margin-right-lg"> <?php echo htmlentities($order['create_time']); ?></span>
									<span class="layer-margin-right-lg">订单号：<?php echo htmlentities($order['order_no']); ?></span>
								</td>
						   </tr>
						  
							<?php foreach($order['goods'] as $i=>$goods): ?>
								<tr>
									<td class="goods-detail layer-text-middle">
										<div class="goods-image">
											<img src="<?php echo htmlentities($goods['image']); ?>" alt="">
										</div>
										<div class="goods-info">
											<p class="goods-title"><?php echo htmlentities($goods['name']); ?></p>
											<p class="goods-spec layer-link-muted">
												<?php echo htmlentities($goods['goods_attr']); ?>
											</p>
										</div>
									</td>
									<td class="layer-text-middle">
										<p>￥<?php echo htmlentities($goods['goods_price']); ?></p>
										<p>×<?php echo htmlentities($goods['total_num']); ?></p>
									</td>
									
									<?php if(($i == 0 )): ?>
									
										<td class="layer-text-middle" rowspan="{count($order['goods'])}">
											<p>￥<?php echo htmlentities($order['pay_price']); ?></p>
											<p class="layer-link-muted">(含运费：￥<?php echo htmlentities($order['express_price']); ?>)</p>
										</td>
										<td class="layer-text-middle" rowspan="{count($order['goods'])}">
											<p><?php echo htmlentities($order['user']['nickName']); ?></p>
											<p class="layer-link-muted">(用户id：<?php echo htmlentities($order['user']['user_id']); ?>)</p>
										</td>
										
										<td class="layer-text-middle" rowspan="{count($order['goods'])}">
											<p>退货</p>
									
											
										</td>
										 <td class="layer-text-middle" rowspan="{count($order['goods'])}">
											<div class="tpl-table-black-operation">
												<a class="tpl-table-black-operation-green"
												   href="<?php echo url('order/saleDetail', ['order_id' => $order['order_id']]); ?>">
													售后详情</a>
												<?php if(($order['pay_status']['value'] === 20
													&& $order['delivery_status']['value'] === 10)): ?>
													<a class="tpl-table-black-operation"
													   href="<?php echo url('order/detail#delivery',
														   ['order_id' => $order['order_id']]); ?>">
														去发货</a>
												<?php endif; ?>
											</div>
											
										</td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>
						<?php endforeach; else: ?>
							<tr>
								<td colspan="6" class="layer-text-center">暂无记录</td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="layui-col-lg12 layer-cf">
					<div class="layer-fr"><?php echo $list; ?> </div>
					<div class="layer-fr pagination-total layer-margin-right">
						<div class="layer-vertical-align-middle">总记录：<?php echo htmlentities($list->total()); ?></div>
					</div>
				</div>
            </div>
				</div>
        </div>
    </div>
</div>
<script>

    $(function () {

        /**
         * 订单导出
         */
        $('.j-export').click(function () {
	
            var data = {};
            var formData = $('#form-search').serializeArray();
            $.each(formData, function () {
                this.name !== 's' && (data[this.name] = this.value);
            });
            window.location = "<?php echo url('operate/export'); ?>" + '&' + $.urlEncode(data);
        });

    });

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
