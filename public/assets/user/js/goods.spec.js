//先执行规格
	var goods_id = $(".goodsid").val();
	$("#spec_type").change(function(){        
        spec_type = $(this).val();
		spec(spec_type);
		goodstype(goods_id,spec_type);
    });
	function spec(type){
		
		$.ajax({
			type:'GET',
			data:{goods_id:goods_id,spec_type:spec_type}, 
			url:"/index.php?s=/user/item/specSelect",
			success:function(data){
				
				if(data){
				    var html = template('tpl_spec_attr',data);
				    $("#ajax_spec_data").html(html);
				    if(goods_id){
						str(data.data.items_ids);
					}
				   ajaxGetSpecInput();
				}
			}
		});		
	}
	function ajaxGetSpecInput(){
		var spec_arr={};// 用户选择的规格数组 	  
		$(".layer-btn-success").each(function(){
			var spec_id = $(this).data('spec_id');
			var item_id = $(this).data('item_id');
			
			if(!spec_arr.hasOwnProperty(spec_id))
				spec_arr[spec_id] = [];
		    spec_arr[spec_id].push(item_id);
		});
		if(JSON.stringify(spec_arr) == "{}"){
			return false;
		}
		$.ajax({
			type:'POST',
			data:{'spec_arr':spec_arr,'goods_id':goods_id},
			url:"/index.php?s=/user/item/getspecInput",
			success:function(data){ 
			 
			   //$("#goods_spec_table2").html('');
			   var html = template('tpl_spec_table',data);
			 
			   $("#goods_spec_table2").html(html);
			   hbdyg();  // 合并单元格
			}
		});
	}
	function layerbtn(obj){
	  if($(obj).hasClass('layer-btn-success')){
		   $(obj).removeClass('layer-btn-success');
		   $(obj).addClass('layer-btn-default');		   
	   }else{
		   $(obj).removeClass('layer-btn-default');
		   $(obj).addClass('layer-btn-success');		   
	   }
	   ajaxGetSpecInput();	
	}
	function str(items_ids){
		$(".spec_list").each(function(){
			var item_id =$(this).data('item_id');
			var arr = isInArray(items_ids,item_id);
			if(!arr){
				$(this).addClass('layer-btn-default');
			}else{
				$(this).addClass('layer-btn-success');
			}
		});
	}
	/**
	 * 使用循环的方式判断一个元素是否存在于一个数组中
	 * @param {Object} arr 数组
	 * @param {Object} value 元素值
	 */
	function isInArray(arr,value){
		for(var i = 0; i < arr.length; i++){
			if(value == arr[i]){
				return true;
			}
		}
		return false;
	}
	function goodstype(goods_id,spec_type){
		$.ajax({
			type:'GET',
			data:{goods_id:goods_id,type_id:spec_type}, 
			url:"/index.php?s=/user/item/GetAttrInput",
			success:function(data){
				var datas=data['data'];
				var html = template('tpl_attr_attrbute',data);
				$(".attr").html(html);
			}        
		});			                
	}
	// 合并单元格
	function hbdyg() {
		var tab = document.getElementById("spec_input_tab"); //要合并的tableID
		//var tab = $('#spec_input_tab');
		var maxCol = 2, val, count, start;  //maxCol：合并单元格作用到多少列 
		if (tab != null) {
			for (var col = maxCol ; col >= 0; col--) {
				count = 1;
				val = "";
				var tab_rows = tab.rows;
				for (var i = 0; i < tab_rows.length; i++) {
					if (val == tab.rows[i].cells[col].innerHTML) {
						count++;
					} else {
						if (count > 1) { //合并
							start = i - count;
							tab_rows[start].cells[col].rowSpan = count;
							for (var j = start + 1; j < i; j++) {
								tab_rows[j].cells[col].style.display = "none";
							}
							count = 1;
						}
						val = tab_rows[i].cells[col].innerHTML;
					}
				}
				if (count > 1) { //合并，最后几行相同的情况下
					start = i - count;
					tab_rows[start].cells[col].rowSpan = count;
					for (var j = start + 1; j < i; j++) {
						tab_rows[j].cells[col].style.display = "none";
					}
				}
			}
		}
	}

