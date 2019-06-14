<?php /*a:3:{s:66:"/www/wwwroot/mf.iirr5.com/application/user/view/setting/agent.html";i:1556091351;s:59:"/www/wwwroot/mf.iirr5.com/application/user/view/layout.html";i:1556090954;s:83:"/www/wwwroot/mf.iirr5.com/application/user/view/layouts/_template/file_library.html";i:1556090954;}*/ ?>
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
				<div class="widget-title a m-cf">分销设置</div>
			</div>
			<form id="my-form" class="layui-form" method="post">
				<div class="widget-body layui-col-sm12 layui-col-md12 layui-col-lg12">
					<div class="layui-tab layui-tab-brief" lay-filter="test">
						<ul class="layui-tab-title">
							<li class="layui-this">基础设置</li>
							<li>佣金设置</li>
							<li>结算</li>
							<li>自定义文字</li>
							<li>页面背景图</li>
							<li>模板消息</li>
						</ul>
						<div class="layui-tab-content">
							<div class="layui-tab-item layui-show">
								<div class="layui-col-sm12 layui-col-md12">
									<label class="layui-form-label"> 是否开启分销功能 </label>
									<div class="layui-input-block">
										<label class="layui-inline ">
											<input type="radio" name="setting[basic][is_open]"  value="1" <?php if(!(empty($data['basic']['values']['is_open']) || (($data['basic']['values']['is_open'] instanceof \think\Collection || $data['basic']['values']['is_open'] instanceof \think\Paginator ) && $data['basic']['values']['is_open']->isEmpty()))): ?><?php echo $data['basic']['values']['is_open']==='1' ? 'checked'  :  ''; ?><?php endif; ?>>  开启
										</label>
										<label class="layui-inline ">
											<input type="radio" name="setting[basic][is_open]" value="2" <?php if(!(empty($data['basic']['values']['is_open']) || (($data['basic']['values']['is_open'] instanceof \think\Collection || $data['basic']['values']['is_open'] instanceof \think\Paginator ) && $data['basic']['values']['is_open']->isEmpty()))): ?><?php echo $data['basic']['values']['is_open']==='2' ? 'checked' :  ''; ?><?php endif; ?> > 关闭
										</label>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label"> 分销层级 </label>
									<div class="layui-input-block ">
										<label class="layui-inline ">
											<input type="radio" name="setting[basic][level]" value="1" <?php if(!(empty($data['basic']['values']['level']) || (($data['basic']['values']['level'] instanceof \think\Collection || $data['basic']['values']['level'] instanceof \think\Paginator ) && $data['basic']['values']['level']->isEmpty()))): ?><?php echo $data['basic']['values']['level']==='1' ? 'checked'  :  ''; ?><?php endif; ?>>
											一级分销
										</label>
										<label class="layui-inline ">
											<input type="radio" name="setting[basic][level]"  value="2" <?php if(!(empty($data['basic']['values']['level']) || (($data['basic']['values']['level'] instanceof \think\Collection || $data['basic']['values']['level'] instanceof \think\Paginator ) && $data['basic']['values']['level']->isEmpty()))): ?><?php echo $data['basic']['values']['level']==='2' ? 'checked'  :  ''; ?><?php endif; ?>>
											二级分销
										</label>
										<label class="layui-inline ">
											<input type="radio" name="setting[basic][level]" value="3" <?php if(!(empty($data['basic']['values']['level']) || (($data['basic']['values']['level'] instanceof \think\Collection || $data['basic']['values']['level'] instanceof \think\Paginator ) && $data['basic']['values']['level']->isEmpty()))): ?><?php echo $data['basic']['values']['level']==='3' ? 'checked'  :  ''; ?><?php endif; ?>>
											三级分销
										</label>
									</div>
								</div>
							</div>

							<div class="layui-tab-item">
								<div class="layui-form-item">
									<label class="layui-form-label">一级佣金比例</label>
									<div class="layui-input-block">
										<input type="number" min="0" max="100" class="layui-input" name="setting[commission][first_money]" value="<?php echo isset($data['commission']['values']['first_money']) ? htmlentities($data['commission']['values']['first_money']) : ''; ?>"  >
										<small>佣金比例范围 0% - 100%</small>
									</div>

								</div>
								<div class="layui-form-item">
									<label class="layui-form-label"> 二级佣金比例</label>
									<div class="layui-input-block">
										<input type="number" min="0" max="100" class="layui-input" name="setting[commission][second_money] "  value="<?php echo isset($data['commission']['values']['second_money']) ? htmlentities($data['commission']['values']['second_money']) : ''; ?>">
										<small>佣金比例范围 0% - 100%</small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">三级佣金比例</label>
									<div class="layui-input-block ">
										<input type="number" min="0" max="100" class="layui-input" name="setting[commission][third_money]" value="<?php echo isset($data['commission']['values']['third_money']) ? htmlentities($data['commission']['values']['third_money']) : ''; ?>"  >

										<small>佣金比例范围 0% - 100%</small>
									</div>
								</div>

							</div>
							<div class="layui-tab-item">
								<div class="layui-form-item">
									<label class="layui-form-label">结算方式</label>
									<div class="layui-input-block">
										<label class="layui-inline "><input type="radio" name="setting[settlement][pay_type][enumer_ation]" value="20" <?php if(!(empty($data['settlement']['values']['pay_type']['enumer_ation']) || (($data['settlement']['values']['pay_type']['enumer_ation'] instanceof \think\Collection || $data['settlement']['values']['pay_type']['enumer_ation'] instanceof \think\Paginator ) && $data['settlement']['values']['pay_type']['enumer_ation']->isEmpty()))): ?><?php echo $data['settlement']['values']['pay_type']['enumer_ation']==='20' ? 'checked'  :  ''; ?><?php endif; ?> title="支付宝"></label>
										<label class="layui-inline "><input type="radio" name="setting[settlement][pay_type][enumer_ation]" class="layui-inline" value="30" <?php if(!(empty($data['settlement']['values']['pay_type']['enumer_ation']) || (($data['settlement']['values']['pay_type']['enumer_ation'] instanceof \think\Collection || $data['settlement']['values']['pay_type']['enumer_ation'] instanceof \think\Paginator ) && $data['settlement']['values']['pay_type']['enumer_ation']->isEmpty()))): ?><?php echo $data['settlement']['values']['pay_type']['enumer_ation']==='30' ? 'checked'  :  ''; ?><?php endif; ?> title="银行卡"></label>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">最低提现额度</label>
									<div class="layui-input-block">
										<input type="number" min="0" class="layui-input" name="setting[settlement][min_money]" value="<?php echo isset($data['settlement']['values']['min_money']) ? htmlentities($data['settlement']['values']['min_money']) : ''; ?>"  >
									</div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="widget-head ">
									<div class="widget-title layer-fl">分销订单页面</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">页面标题</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][order][title][value]"  value="<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo isset($data['words']['values']['order']['title']['value']) ? htmlentities($data['words']['values']['order']['title']['value']) : ''; ?><?php endif; ?>">
										<small> 默认：<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo isset($data['words']['values']['order']['title']['value']) ? htmlentities($data['words']['values']['order']['title']['value']) : ' '; ?><?php endif; ?></small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">全部</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][order][words][all][value]" value="<?php echo isset($data['words']['values']['order']['words']['all']['value']) ? htmlentities($data['words']['values']['order']['words']['all']['value']) : ''; ?>" >
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">未结算</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][order][words][unsettled][value]" value="<?php echo isset($data['words']['values']['order']['words']['unsettled']['value']) ? htmlentities($data['words']['values']['order']['words']['unsettled']['value']) : ''; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">已结算</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][order][words][settled][value]" value="<?php echo isset($data['words']['values']['order']['words']['settled']['value']) ? htmlentities($data['words']['values']['order']['words']['settled']['value']) : ''; ?>">
									</div>
								</div>

								<div class="widget-head ">
									<div class="widget-title layer-fl">我的团队页面</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">
										页面标题
									</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][team][title][value]" value="<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo isset($data['words']['values']['team']['title']['value']) ? htmlentities($data['words']['values']['team']['title']['value']) : ' '; ?><?php endif; ?>">
										<small> 默认：{notempty name='data'}<?php echo isset($data['words']['values']['team']['title']['value']) ? htmlentities($data['words']['values']['team']['title']['value']) : ' '; ?></small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">团队总人数</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][team][words][total_team][value]" value="<?php echo isset($data['words']['values']['team']['words']['total_team']['value']) ? htmlentities($data['words']['values']['team']['words']['total_team']['value']) : ' '; ?>"  >
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">一级团队</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input"  name="setting[words][team][words][first][value]" value="<?php echo isset($data['words']['values']['team']['words']['first']['value']) ? htmlentities($data['words']['values']['team']['words']['first']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">二级团队</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][team][words][second][value]" value="<?php echo isset($data['words']['values']['team']['words']['second']['value']) ? htmlentities($data['words']['values']['team']['words']['second']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">三级团队</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][team][words][third][value]" value="<?php echo isset($data['words']['values']['team']['words']['third']['value']) ? htmlentities($data['words']['values']['team']['words']['third']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="widget-head ">
									<div class="widget-title layer-fl">提现明细页面</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">页面标题</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][title][value]" value="<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo isset($data['words']['values']['withdraw_list']['title']['value']) ? htmlentities($data['words']['values']['withdraw_list']['title']['value']) : ' '; ?><?php endif; ?>" >
										<small>默认：{notempty name='data'}<?php echo isset($data['words']['values']['withdraw_list']['title']['value']) ? htmlentities($data['words']['values']['withdraw_list']['title']['value']) : ' '; ?></small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">全部</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][words][all][value]" value="<?php echo isset($data['words']['values']['withdraw_list']['words']['all']['value']) ? htmlentities($data['words']['values']['withdraw_list']['words']['all']['value']) : ' '; ?>" >
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">审核中</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][words][apply_10][value]" value="<?php echo isset($data['words']['values']['withdraw_list']['words']['apply_10']['value']) ? htmlentities($data['words']['values']['withdraw_list']['words']['apply_10']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">审核通过</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][words][apply_20][value]" value="<?php echo isset($data['words']['values']['withdraw_list']['words']['apply_20']['value']) ? htmlentities($data['words']['values']['withdraw_list']['words']['apply_20']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">已打款</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][words][apply_40][value]" value="<?php echo isset($data['words']['values']['withdraw_list']['words']['apply_40']['value']) ? htmlentities($data['words']['values']['withdraw_list']['words']['apply_40']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">驳回</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_list][words][apply_30][value]" value="<?php echo isset($data['words']['values']['withdraw_list']['words']['apply_30']['value']) ? htmlentities($data['words']['values']['withdraw_list']['words']['apply_30']['value']) : ' '; ?>" >
									</div>
								</div>

								<div class="widget-head ">
									<div class="widget-title layer-fl">申请提现页面</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">页面标题</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][title][value]" value="<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo htmlentities($data['words']['values']['withdraw_apply']['title']['value']); ?> <?php endif; ?>">
										<small>默认：{notempty name='data'}<?php echo isset($data['words']['values']['withdraw_apply']['title']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['title']['value']) : ' '; ?></small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">可提现佣金</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][words][capital][value]" value="<?php echo isset($data['words']['values']['withdraw_apply']['words']['capital']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['words']['capital']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">提现金额</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][words][money][value]" value="<?php echo isset($data['words']['values']['withdraw_apply']['words']['money']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['words']['money']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">请输入要提取的金额</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][words][money_placeholder][value]" value="<?php echo isset($data['words']['values']['withdraw_apply']['words']['money_placeholder']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['words']['money_placeholder']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">最低提现佣金</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][words][min_money][value]" value="<?php echo isset($data['words']['values']['withdraw_apply']['words']['min_money']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['words']['min_money']['value']) : ' '; ?>">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">提交申请</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[words][withdraw_apply][words][submit][value]" value="<?php echo isset($data['words']['values']['withdraw_apply']['words']['submit']['value']) ? htmlentities($data['words']['values']['withdraw_apply']['words']['submit']['value']) : ' '; ?>">
									</div>
								</div>

								<div class="widget-head ">
									<div class="widget-title layer-fl">推广二维码</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">页面标题
									</label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[words][qrcode][title][value]" value="<?php if(!(empty($data['words']) || (($data['words'] instanceof \think\Collection || $data['words'] instanceof \think\Paginator ) && $data['words']->isEmpty()))): ?><?php echo isset($data['words']['values']['qrcode']['title']['value']) ? htmlentities($data['words']['values']['qrcode']['title']['value']) : ' '; ?><?php endif; ?>">
										<small>默认：{notempty name='data'}<?php echo isset($data['words']['values']['qrcode']['title']['value']) ? htmlentities($data['words']['values']['qrcode']['title']['value']) : ' '; ?></small>
									</div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="layui-form-item">
									<label class="layui-form-label">分销中心首页 </label>
									<div class="layui-input-block">
										<div class="layui-col-sm12">
											<div class="layui-input-block ">
												<button type="button" class="j-index upload-file layui-btn layui-btn-secondary layer-radius">
													<i class="mdi menu-icon mdi-plus"></i> 选择图片
												</button>
												<div class="uploader-list ">
													<?php if((!empty($data['background']['values']['index']))): ?>
													<div class="file-item">
														<a href="<?php echo htmlentities($data['background']['values']['index']); ?>"
														   title="点击查看大图"
														   target="_blank">
															<img src="<?php echo htmlentities($data['background']['values']['index']); ?>">
														</a>
														<input type="hidden" name="setting[background][index]"
															   value="<?php echo isset($data['background']['values']['index']) ? htmlentities($data['background']['values']['index']) : ' '; ?>">
														<i class="mdi menu-icon mdi-window-close file-item-delete"></i>
													</div>
													<?php endif; ?>
												</div>
											</div>
											<div class="help-block">
												<small>尺寸：宽750像素 高度不限</small>
											</div>
										</div>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">申请成为分销商页 </label>
									<div class="layui-col-sm9 ">
										<div class="layui-input-block ">
											<button type="button"
													class="j-apply upload-file layui-btn layui-btn-secondary layer-radius">
												<i class="mdi menu-icon mdi-plus"></i> 选择图片
											</button>
											<div class="uploader-list ">
												<?php if((!empty($data['background']['values']['apply']))): ?>
												<div class="file-item">
													<a href="<?php echo htmlentities($data['background']['values']['apply']); ?>"
													   title="点击查看大图"
													   target="_blank">
														<img src="<?php echo htmlentities($data['background']['values']['apply']); ?>">
													</a>
													<input type="hidden" name="setting[background][apply]"
														   value="<?php echo isset($data['background']['values']['apply']) ? htmlentities($data['background']['values']['apply']) : ' '; ?>">
													<i class="mdi menu-icon mdi-window-close file-item-delete"></i>
												</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="help-block">
											<small>尺寸：宽750像素 高度不限</small>
										</div>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">申请提现页 </label>
									<div class="layui-col-sm9 ">
										<div class="layui-input-block ">
											<button type="button"
													class="j-withdraw_apply upload-file layui-btn layui-btn-secondary layer-radius">
												<i class="mdi menu-icon mdi-plus"></i> 选择图片
											</button>
											<div class="uploader-list ">
												<?php if((!empty($data['background']['values']['withdraw_apply']))): ?>
												<div class="file-item">
													<a href="<?php echo htmlentities($data['background']['values']['withdraw_apply']); ?>"
													   title="点击查看大图"
													   target="_blank">
														<img src="<?php echo htmlentities($data['background']['values']['withdraw_apply']); ?>">
													</a>
													<input type="hidden"
														   name="setting[background][withdraw_apply]"
														   value="<?php echo isset($data['background']['values']['withdraw_apply']) ? htmlentities($data['background']['values']['withdraw_apply']) : ' '; ?>">
													<i class="mdi menu-icon mdi-window-close file-item-delete"></i>
												</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="help-block">
											<small>尺寸：宽750像素 高度不限</small>
										</div>
									</div>
								</div>
							</div>
							<div class="layui-tab-item">
								<div class="layui-form-item">
									<label class="layui-form-label">分销商入驻审核通知</label>
									<div class="layui-input-block ">
										<input type="text" class="layui-input" name="setting[template_msg][apply_tpl]" placeholder="请填写模板消息ID" value="<?php echo isset($data['template_msg']['values']['apply_tpl']) ? htmlentities($data['template_msg']['values']['apply_tpl']) : ' '; ?>">
										<small>模板编号AT0674，关键词 (申请时间、审核状态、审核时间、备注信息)</small>
										<small class="layer-margin-left-xs">
											<a href="index.php?s=/user/setting/tplmsg/get" target="_blank">如何获取模板消息ID？</a>
										</small>
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label"> 提现状态通知 </label>
									<div class="layui-input-block">
										<input type="text" class="layui-input" name="setting[template_msg][withdraw_tpl]" placeholder="请填写模板消息ID" value="<?php echo isset($data['template_msg']['values']['withdraw_tpl']) ? htmlentities($data['template_msg']['values']['withdraw_tpl']) : ' '; ?>">
										<small>模板编号AT0324，关键词 (提现时间、提现方式、提现金额、提现状态、备注)</small>
										<small class="layer-margin-left-xs">
											<a href="index.php?s=/user/setting/tplmsg/tplmsg" target="_blank">如何获取模板消息ID？</a>
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="layui-form-item">
						<button type="submit" class="j-submit layui-btn layui-btn-secondary">提交
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 图片文件列表模板 -->
<script id="tpl-file-item" type="text/template">
	{{ each list }}
	<div class="file-item">
		<a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
			<img src="{{ $value.file_path }}">
		</a>
		<input type="hidden" name="{{ name }}" value="{{ $value.file_path }}">
		<i class="mdi menu-icon mdi-window-close file-item-delete"></i>
	</div>
	{{ /each }}
</script>

<!-- 文件库弹窗 -->
<!-- 文件库模板 -->
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


<script>
    $(function () {

        // 选择图片：分销中心首页
        $('.j-index').selectImages({
            name: 'setting[background][index]'
            , multiple: false
        });

        // 选择图片：申请成为分销商页
        $('.j-apply').selectImages({
            name: 'setting[background][apply]'
            , multiple: false
        });

        // 选择图片：申请提现页
        $('.j-withdraw_apply').selectImages({
            name: 'setting[background][withdraw_apply]'
            , multiple: false
        });
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

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
