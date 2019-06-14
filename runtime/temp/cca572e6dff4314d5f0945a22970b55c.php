<?php /*a:2:{s:51:"D:\www\tp\application\user\view\item\spec\save.html";i:1556090954;s:43:"D:\www\tp\application\user\view\layout.html";i:1556090954;}*/ ?>
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
			<form id="my-form" class="layui-form" enctype="multipart/form-data" method="post">
				<div class="widget-head">
					<div class="widget-title">
						商品规格
					</div>
				</div>
			<div class="widget-body layui-col-lg12">
					<div class="layui-form-item">
						<label class="layui-form-label">规格名称 </label>
						<div class="layui-col-sm7 layer-midd-left">
							<input type="text" class="layui-input" name="spec[name]"  value="<?php echo isset($spec['name']) ? htmlentities($spec['name']) : ''; ?>" required>
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label form-require">所属商品类型： </label>
						<div class="layui-col-sm7 layer-midd-left">
							<select name="spec[type_id]" class="form-control"data-am-selected="{searchBox: 1, btnSize: 'sm'}">
								<option value="0">请选择</option>
								<?php if(isset($type)): foreach($type as $first): ?>
								<option <?php if(!(empty($spec['type_id']) || (($spec['type_id'] instanceof \think\Collection || $spec['type_id'] instanceof \think\Paginator ) && $spec['type_id']->isEmpty()))): if(($spec['type_id']) == ($first['id'])): ?> selected = "selected" <?php endif; ?><?php endif; ?>value="<?php echo isset($first['id']) ? htmlentities($first['id']) : ''; ?>"><?php echo htmlentities($first['name']); ?></option>
								<?php endforeach; ?><?php endif; ?>
							  </select>
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label form-require">规格项 </label>
						<div class="layui-col-sm7 layer-midd-left">
							<textarea name='spec[items]' rows="6" class="layui-textarea"><?php echo isset($spec['items']) ? htmlentities($spec['items']) : ''; ?></textarea>
							<small>一行为一个规格项</small>
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label form-require">分类排序 </label>
						<div class="layui-col-sm7 layer-midd-left">
							<input type="number" class="layui-input" name="category[sort_order]" value="100" required>
							<small>数字越小越靠前</small>
						</div>
					</div>
				 <div>
					 <input type="hidden" name="spec[id]" value="<?php echo isset($spec['id']) ? htmlentities($spec['id']) : ''; ?>">
				 </div>
					<div class="layui-form-item">
						<div class="layui-input-block">
							<button type="submit" class="j-submit layui-btn ">提交
							</button>
						</div>
					</div>
			</div>
			</form>
        </div>
    </div>
</div>
<script>
    $(function ()
    {
        /**         * 表单验证提交
         *  * @type {*}
         *  */
    $('#my-form').superForm();	})
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
