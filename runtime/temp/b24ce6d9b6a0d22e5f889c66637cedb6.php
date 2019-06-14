<?php /*a:2:{s:47:"D:\www\tp\application\user\view\item\index.html";i:1556090954;s:43:"D:\www\tp\application\user\view\layout.html";i:1556090954;}*/ ?>
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
				<div class="widget-title">出售中的商品</div>
			</div>
			<div class="layui-col-md12 layui-col-xs12 widget-body ">	
				<div class="layui-form-item">
					<form class="layui-form" method="post" action="">
						<input type="hidden" name="s" value="/<?php echo htmlentities($request->pathinfo()); ?>">
						<div class="layui-col-sm12 layui-col-md12">
							<div class="layui">
								<div class="layui-col-md3">
									<label class="layui-form-label">商品名称：</label>
									<div class="layui-col-md6 ">
										<input type="text" class="layui-input" name="name" placeholder="请输入商品名称" value="<?php echo htmlentities($request->get('name')); ?>">
									</div>
								</div>
								<div class="layui-col-md4 layui-col-flex">
									<label class="layui-form-label">商品分类：</label>
									<span style="display:none;"><?php echo $category_id = $request->get('category_id')?: null; ?></span>
									<select class="form-control" name="category_id" data-layer-selected="{searchBox: 1, btnSize: 'sm',  placeholder: '商品分类', maxHeight: 400}">
										<option value="">全部分类</option>
									  <?php if(isset($catgory)): ?>:
										<?php foreach($catgory as $first): ?>
											<option value="<?php echo htmlentities($first['id']); ?>"><?php echo htmlentities($first['name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
									</select>
								</div>
								<div class="layui-col-md2">
									<button class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
								</div>
							</div>
						</div>
						<div class="layui-col-sm12 layui-col-md12">
							<div class="layui-col-md12 layer-midd-left gr-top">
								<div class="layui-form-item3">
									<div class="layui-col-md1">
										<a class="layui-btn"  href="<?php echo url('item/edit'); ?>"> <i class="mdi menu-icon mdi-plus"></i>新增</a>
									</div>
									<div class="layui-col-md1">
										<a class="layui-btn"
										   href="<?php echo url('item/index',['status'=>1]); ?>">
											<i class="layui-icon"></i>上架
										</a>
									</div>
									<div class="layui-col-md1">
										<a class="layui-btn"
										   href="<?php echo url('item/index',['status'=>2]); ?>">
											<i class="layui-icon"></i> 下架
										</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="layui-col-md12" >
					<table width="100%" class="layui-table">
						<thead>
						<tr>
							<th>商品ID</th>
							<th width="20%">商品名称</th>
							<th>分类</th>
							<th>价格</th>
							<th>库存</th>
							<th>上架</th>
							<th>添加时间</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						<?php if(!$list->isEmpty()): foreach($list as $item): ?>
							<tr>
								<td><?php echo htmlentities($item['goods_id']); ?></td>
								<td>
									 <p class="item-title"><?php echo htmlentities($item['goods_name']); ?></p>
								</td>
								<td>
								<?php echo htmlentities($item['category']['name']); ?>
								</td>

								<td><?php if(!(empty($item['sku']) || (($item['sku'] instanceof \think\Collection || $item['sku'] instanceof \think\Paginator ) && $item['sku']->isEmpty()))): ?><?php echo htmlentities($item['sku'][0]['shop_price']); ?><?php endif; ?></td>
								<td><?php if(!(empty($item['sku']) || (($item['sku'] instanceof \think\Collection || $item['sku'] instanceof \think\Paginator ) && $item['sku']->isEmpty()))): ?><?php echo htmlentities($item['sku'][0]['store_count']); ?><?php endif; ?></td>
								<td	style="width:10%">
									   <span class="j-state layui-badge x-cur-p layer-badge-<?php echo $item['is_on_sale']==1 ? 'success'   :  'warning'; ?>" data-id="<?php echo htmlentities($item['goods_id']); ?>"><?php if($item['is_on_sale'] == 1): ?>上架<?php else: ?>下架<?php endif; ?></span>
								</td>
								<td><?php echo htmlentities($item['create_time']); ?></td>
								<td>
									<div class="tpl-table-black-operation">
										<a class="tpl-table-black-operation-primary" href="<?php echo url('item/comment',
											['goods_id' => $item['goods_id']]); ?>">
											<i class="mdi menu-icon mdi-grease-pencil"></i> 评价
										</a>
										<a href="<?php echo url('item/edit',
											['goods_id' => $item['goods_id']]); ?>">
											<i class="mdi menu-icon mdi-pencil"></i> 编辑
										</a>
										<a href="javascript:;" class="item-delete tpl-table-black-operation-del"
										   data-id="<?php echo htmlentities($item['goods_id']); ?>">
											<i class="mdi menu-icon mdi-delete-forever"></i> 删除
										</a>
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
				<div class="layui-col-lg12">
					<div class="layui-fr"><?php echo $list; ?></div>
					<div class="layui-fr pagination-total layer-margin-right">
						<div class="layer-vertical-align-middle">总记录：<?php echo htmlentities($list->total()); ?></div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<script>
    $(function () {
		 $('.j-state')._get('goods_id', "<?php echo url('item/state'); ?>",'');
        // 删除元素
        $('.item-delete').delete('id', "<?php echo url('item/delete'); ?>",'','Item');

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
