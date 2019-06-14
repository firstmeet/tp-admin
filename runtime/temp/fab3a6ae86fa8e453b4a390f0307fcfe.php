<?php /*a:2:{s:47:"D:\www\tp\application\user\view\user\index.html";i:1556090954;s:43:"D:\www\tp\application\user\view\layout.html";i:1556090954;}*/ ?>
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
    <div class="layui-col-md12 layui-col-xs12">
		<div class="widget-head">
			<div class="widget-title">用户列表</div>
		</div>
	</div>
	<div class="layui-col-md12 layui-col-xs12 widget-body ">
		<form class="layui-form"  method="post" action="<?php echo url("","",true,false);?>"> 
		    <div class="layui-u-sm-12 layui-u-md-12 layui-col-lg12">
			    <div class="layui-form-item "> 
			        <div class="layui-col-md4 layui-col-flex">				
						<label class="layui-form-label">性别：</label>
					   <?php echo htmlentities($gender = $request->get('gender')); ?>
						<select class="form-control" name="gender"
								data-layer-selected="{btnSize: 'sm', placeholder: '性别'}">
							<option value="-1"
								<?php echo $gender==='-1' ? 'selected'  :  ''; ?>>全部
							</option>
							<option value="1"
								<?php echo $gender==='1' ? 'selected'  :  ''; ?>>男
							</option>
							<option value="2"
								<?php echo $gender==='2' ? 'selected'  :  ''; ?>>女
							</option>
							<option value="0"
								<?php echo $gender==='0' ? 'selected'  :  ''; ?>>未知
							</option>
						</select>
					</div>
					<div class="layui-col-md3">
						<label class="layui-form-label">微信昵称：</label>
						<div class="layui-input-inline">
							<input name="nickName" type="text"  class="layui-input" placeholder="请输入微信昵称" value="<?php echo htmlentities($request->get('nickName ')); ?>">	
						</div>
					</div>
					<div class="layui-col-md3 laui-col-width" >
						<button class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
					</div>
				</div>	
			</div>
		</form>
		<div class="layui-col-md12" >
			<table class="layui-table">
				<thead>
					<tr>
					   <th>ID</th>
						<th>头像</th>
						<th>昵称</th>
						<th>性别</th>
						<th>省市</th>
						<th>上级</th>
						<th>消费</th>
						<th>余额</th>
						<th>积分</th>
						<th>注册时间</th>
						<th>操作</th>
					</tr> 
				</thead>
				<tbody>
					<?php if((!$list->isEmpty())): foreach($list as $item): ?>
					<tr>
						<td><?php echo htmlentities($item['user_id']); ?></td>
						<td>
							<a href="<?php echo htmlentities($item['avatarUrl']); ?>" title="点击查看大图" target="_blank">
								<img src="<?php echo htmlentities($item['avatarUrl']); ?>" width="50" height="50" alt="">
							</a>
						</td>
						<td><?php echo htmlentities($item['nickName']); ?></td>
						<td><?php echo htmlentities($item['gender']); ?></td>
						<td><?php echo htmlentities($item['country']); ?><?php echo !empty($item['province']) ? htmlentities($item['province']) : ''; ?><?php echo !empty($item['city']) ? htmlentities($item['city']) : '--'; ?></td>
						<td><?php echo !empty($item['users']['nickName']) ? htmlentities($item['users']['nickName']) : '无'; ?></td>
						<td><?php echo htmlentities($item['shop_money']); ?></td>
						<td><?php echo htmlentities($item['money']); ?></td>
						<td><?php echo htmlentities($item['integral']); ?></td>
						<td><?php echo htmlentities($item['create_time']); ?></td>
						<td>
							<div class="tpl-table-black-operation">
								<a class="tpl-table-black-operation-warning" href="<?php echo url('order/allList',
									['user_id' => $item['user_id']]); ?>"
								   class="tpl-table-black-operation-del"
								   data-id="<?php echo htmlentities($item['user_id']); ?>">
									<i class="mdi menu-icon mdi-border-color"></i> 订单
								</a>
								<a class="tpl-table-black-operation-success" href="<?php echo url('item/comment',
									['user_id' => $item['user_id']]); ?>"
								   class="tpl-table-black-operation-del"
								   data-id="<?php echo htmlentities($item['user_id']); ?>">
									<i class="mdi menu-icon mdi-grease-pencil"></i> 评价
								</a>
								<a class="tpl-table-black-operation-primary" href="<?php echo url('user/index',
									['pid' => $item['user_id']]); ?>"
								   class="tpl-table-black-operation-del"
								   data-id="<?php echo htmlentities($item['user_id']); ?>">
									<i class="mdi menu-icon mdi-telegram"></i> 推荐
								</a>
								<a class="tpl-table-black-operation-primary" href="<?php echo url('user/profit',
									['uid' => $item['user_id']]); ?>"
								   class="tpl-table-black-operation-del"
								   data-id="<?php echo htmlentities($item['user_id']); ?>">
									<i class="mdi menu-icon mdi-telegram"></i> 收益
								</a>
								 <a href="javascript:void(0);"
								   class="item-delete tpl-table-black-operation-del"
								   data-id="<?php echo htmlentities($item['user_id']); ?>">
									<i class="mdi menu-icon mdi-pencil-lock"></i>  冻结
								</a>
							</div>
						</td>
					</tr>
					<?php endforeach; else: ?>
						<tr>
							<td colspan="12" class="layer-text-center" >暂无记录</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<div class="layui-col-md12 layui-col-xs12">
				<div class=""><?php echo $page; ?> </div>
				<div class="pagination-total">
					<div class="">总记录：<?php echo htmlentities($list->total()); ?></div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>  
<script>
    $(function () {
        // 删除元素
        var url = "index.php?s=/user/user/delete";
        $('.item-delete').delete('user_id', url, '确定要冻结此用户吗？');

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
