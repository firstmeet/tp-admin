<?php /*a:2:{s:60:"/mnt/d/www/tp/application/user/view/item/category/index.html";i:1556090954;s:47:"/mnt/d/www/tp/application/user/view/layout.html";i:1556090954;}*/ ?>
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
				<div class="widget-title layer-cf">商品分类</div>
			</div>
			<div class="layui-col-md12 layui-col-xs12 widget-body ">  
					<div class="layer-form-group">
							<div class="layer-btn-group layer-btn-group-xs">
								<a class="layui-btn layer-btn-default layer-btn-success layer-radius"
								   href="<?php echo url('item/editCategory'); ?>">
									<span class="mdi menu-icon mdi-plus"></span> 新增
								</a>
							</div>
					</div>
				<div class="layui-col-sm12 ceshi">
					<!-- <table id="table1" width="100%" lay-filter="table1" class="layer-table layer-table-compact layer-table-striped tpl-table-black ">
					</table> -->
					<table class="layui-table layui-form" id="tree-table" lay-size="sm"></table>
					<input type="hidden" data-id="" class="del_id">
				</div>
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(function () {
		layui.config({
			 base: 'assets/extends/'
		})
		layui.use(['treeTable','layer','code','form'],function(){
			var o = layui.$,
				form = layui.form,
				layer = layui.layer,
				treeTable = layui.treeTable;
			var	re = treeTable.render({
				elem: '#tree-table',
				url:"<?php echo url('item/categoryAjax'); ?>",
				icon_key: 'title',
				is_checkbox: false,
				cols: [
					{
						key: 'id',
						title: 'ID',
						width: '5%',
						align: 'center',
					},
					{
						key: 'title',
						title: '名称',
						width: '35%',
						 template: function(item){
							return ''+item.name+'';
						} 
					},
					{
						key: 'pid',
						title: '父ID',
						width: '10%',
						align: 'center',
					},
					{
						title: '操作',
						align: 'center',
						template: function(item){
							return '<a href="/index.php?s=/user/item/editcategory/id/'+item['id']+'" class="layui-btn layui-btn-primary layui-btn-xs "  lay-event="edit"> <i class="mdi menu-icon mdi-pencil"></i>修改</a><a class="layui-btn layui-btn-danger layui-btn-xs item-delete" lay-event="del" onclick="del('+item.id+')"  data_id="'+item.id+'"><i class="mdi menu-icon mdi-delete-forever"></i>删除</a>';
						}
					}
				]
			});
		})
		})
		function del(id){
			var url = "<?php echo url('item/delete'); ?>";
			$('.item-delete').delete('id', url,'','Category',id);
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
