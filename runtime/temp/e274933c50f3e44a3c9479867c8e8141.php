<?php /*a:2:{s:46:"D:\www\tp\application\user\view\tpl\index.html";i:1556090954;s:43:"D:\www\tp\application\user\view\layout.html";i:1556090954;}*/ ?>
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
				<div class="widget-title">页面设计</div>
			</div>
			<div class="widget-body layui-col-sm12 layui-col-md12 layui-col-lg12">
				<div class="layui-col-sm12 layui-col-md6 layui-col-lg6">
					<div class="layui-form-item">       
						<div class="">
							<a class="layui-btn "
							   href="<?php echo url('tpl/edit'); ?>">
								<span class="mdi menu-icon mdi-plus"></span> 新增
							</a>
						</div>
					</div>
				</div>
				<div class="layui-col-md12" >
						<table width="100%" class="layui-table">
							<thead>
							<tr>
							<th>页面ID</th>
							<th width="25%">页面名称</th>
							<th>页面类型</th>
							<th>添加时间</th>
							<th>更新时间</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						<?php if(!$list->isEmpty()): foreach($list  as $item): ?>
							<tr>
								<td class="layer-text-middle"><?php echo htmlentities($item['page_id']); ?></td>
								<td class="layer-text-middle">
									<span><?php echo htmlentities($item['page_name']); ?></span>
								</td>
								<td class="layer-text-middle">
									<?php if($item['page_type'] == 10): ?>
										<span class="layer-badge layer-badge-warning">商城首页</span>
									<?php elseif($item['page_type'] == 20): ?>
										<span class="layer-badge layer-badge-secondary">自定义页</span>
									<?php endif; ?>
								</td>
								<td class="layer-text-middle"><?php echo htmlentities($item['create_time']); ?></td>
								<td class="layer-text-middle"><?php echo htmlentities($item['update_time']); ?></td>
								<td class="layer-text-middle">
									<div class="tpl-table-black-operation">
											<a href="<?php echo url('tpl/edit', ['page_id' => $item['page_id']]); ?>">
												<i class="mdi menu-icon mdi-pencil"></i> 编辑
											</a>
									   <?php if($item['page_type'] == 20): ?>
												<a href="javascript:;"
												   class="item-delete tpl-table-black-operation-del"
												   data-id="<?php echo htmlentities($item['page_id']); ?>">
													<i class="mdi menu-icon mdi-delete-forever"></i> 删除
												</a>
												<a href="javascript:;"
												   class="j-setHome tpl-table-black-operation-green"
												   data-id="<?php echo htmlentities($item['page_id']); ?>">
													<i class="iconfont icon-home"></i> 设为首页
												</a>
										<?php endif; ?>
									</div>
								</td>
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
    </div>
</div>
<script>
    $(function () {

        // 删除元素
        $('.item-delete').delete('page_id', "<?= url('tpl/delete') ?>");

        // 设为首页
        $('.j-setHome').click(function () {
            var pageId = $(this).attr('data-id');
            layer.confirm('确定要将此页面设置为默认首页吗？', function (index) {
                $.post("<?= url('tpl/sethome') ?>", {page_id: pageId}, function (result) {
                    result.code === 1 ? $.show_success(result.msg, result.url)
                        : $.show_error(result.msg);
                });
                layer.close(index);
            });
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
