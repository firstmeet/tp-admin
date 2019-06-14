
		/** 以下 商品分类相关 js*/
	$(document).ready(function(){
		layui.config({
			base: 'assets/extends/'
		}).extend({
			treeSelect: 'treeSelect'
		});	
		layui.use(['treeSelect','form'], function () {
			var treeSelect= layui.treeSelect;
		 treeSelect.render({
            // 选择器
            elem: '#tree',
            // 数据
            data: 'index.php?s=/user/item/getJsTree.html',
            // 异步加载方式：get/post，默认get
            type: 'get',
            // 占位符
            placeholder: '请选择分类',
            // 是否开启搜索功能：true/false，默认false
            search: true,
            style: {
                folder: {
                    enable: false
                },
                line: {
                    enable: true
                }
            },
            // 点击回调
            click: function(d){
               $(".catid").val(d.current.id);
            },
            // 加载完成后的回调函数
            success: function (d) {
                //console.log(d);
				treeSelect.checkNode('tree',cat_id)
            }
        });
	})
})
