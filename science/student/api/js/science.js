$(function(){
/*------------------------------------登入小組------------------------------------*/
	$(".project_enter").click(function(){
		var str = $(this).attr("value");
		var splitstr = str.split("|");
		var p_id = splitstr[0];
		var state = splitstr[1];
		var chief = splitstr[2];

		console.log(p_id, state, chief);
		if(state == '0'){ // 指定選擇進行中的專案，判斷要去哪個階段
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				data : {
					p_id 	: p_id,
					action 	: 'project_enter'
				},
				error : function(e, status){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
				},
				success : function(data){
					// console.log(data);
					window.location.href = data;
				}
			});
		}else if(state == '1'){
			alert("【系統】本專題已完成。");
		}else if(state == '2'){
			alert("【系統】等候老師回應中...");
		}else if(state == '3' && chief == '1'){
			alert("【系統】請重新選擇老師...");
			$("input[name=project_id]").attr('value', p_id);
			$("#re_teacher_fancybox").show();
		}else if(state == '3' && chief == '0'){
			alert("【系統】請組長重新選擇老師...");
		}
	});
/*------------------------------------創立小組------------------------------------*/
	$(".project_new").click(function(){
		$("#project_fancybox").show();
	});
	// 點擊[加入組員]，加入專題組員
	$(".group_add").click(function(){
		var add_id = $(".project_group").val();
		var add_name = $(".project_group").find("option[value='"+ add_id +"']").text();
		var group_id = [];				// 專題組員(如多於三個人則可能無法參加科學專題)

		$("#project_form .group_block .group_choosed").each(function(){
			group_id.push($(this).attr("value"));
		});

		if(group_id.length > 5){	// 最多六個人(暫)
			$(".fancybox_mark").text("*超過六個組員，可能會違反科展相關規定，如未參加請跳過此提醒。");
		}

		if(add_id != "" && add_id != "default" && add_id != "null"){
			$("<div class='group_choosed' value='"+ add_id +"'>"+ add_name +
				"<sapn class='group_deleted'>[刪除]</sapn>"+
				"</div>").appendTo(".group_block");
			$(".project_group option[value='"+ add_id +"']").hide();
			$(".project_group").val("default");
		}
		// 點擊組員裡的[刪除]，刪除專題組員
		$(".group_deleted").click(function() {
			var del_id = $(this).parent().attr("value");
			var del_name = $(this).parent().text();
			$(".project_group option[value='"+ add_id +"']").show();
			$(this).parent().remove();

			if(group_id.length < 7){
				$(".fancybox_mark").text("");
			}
		});
	});
	// 點擊吉祥物
	$("select[name=project_mascot]").change(function(){
		if(this.value != "default"){
			$(".project_img").html("<img src='/co.in/science/model/images/"+ this.value +"'>");
		}else{
			$(".project_img").html("");	// 清空圖片
		}
	});
	// 按下創立小組
	$("#project_create").click(function(){
		var pname = $("input[name=project_pname]").val();
		var theme = $("select[name=project_theme]").val();
		var mascot = $("select[name=project_mascot]").val();
		var group_id = [];				// 專題組員(如多於三個人則可能無法參加科學專題)
		var teacher_id = $("select[name=project_teacher]").val();

		$("#project_form .group_block .group_choosed").each(function(){	// 最多三個人(暫)
			group_id.push($(this).attr("value"));
		});

		if(pname == ""){
			alert("【系統】請填寫小組名稱。");
		}else if(theme == "default"){
			alert("【系統】請選擇學科。");
		}else if(teacher_id == "default"){
			alert("【系統】請選擇指導老師。");
		}else if(mascot == "default"){
			alert("【系統】請選擇吉祥物。");
		}else{
			// console.log(group_id);
			$("#project_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					group_id : group_id,
					action : "project_create"
				},
				error : function(){
					alert("【系統】創立失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】新專題小組創立成功！！");
					window.location.reload();
				}
			});
		}
	});
	// 按下給老師一些話、跨校指導
	$(".project_said, .project_cross").click(function(){
		alert("【系統】系統尚未建置。");
	});
	// 按下重新選擇老師
	$("#re_teacher").click(function(){
		var p_id = $("input[name=project_id]").val();
		var re_teacher = $("select[name=project_re_teacher]").val();
		// console.log(p_id, re_teacher);
		if(re_teacher == 'default'){
			alert("【系統】尚未重新選擇指導老師。");
		}else{
			$.ajax({
				url	 : "/co.in/science/student/api/php/science.php", 			// 絕對位址
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					p_id : p_id,
					re_teacher : re_teacher,
					action : 'project_re_teacher'
				},
				error: function(){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success: function(data){
					alert("【系統】重新選擇老師送出成功！");
					window.location.reload();
				}
			});
		}
	});
/*------------------------------------小組首頁------------------------------------*/
	$("#index_btn").click(function(){
		$.ajax({
			url : "/co.in/science/student/api/php/science.php",
			type : "POST",
			data : {
				action : 'project_out'
			},
			error: function(){
				alert("【警告】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success: function(data){
				window.location.href = "/co.in/science/student/entrance.php";
				return;
			}
		});
	});
	// 最新消息
	$("#news_btn").click(function(){
		$.ajax({
			url : "/co.in/science/student/api/php/science.php",
			type : "POST",
			data : {
				type : 'read_news',
				action : 'news_update'
			},
			error: function(){
				alert("【警告】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success: function(data){
				$("#news_bubble").hide();
				$("#news_table").toggle();
				return;
			}
		});
	});
	// 更多消息
	$("#news_more").click(function(){
		window.location.href = "/co.in/science/student/func_news.php";
	});
	// 功能按鈕
	$("#func_btn").click(function(){
		$("#func_table").toggle();
	});
	// 任務地圖點擊顯示及收合
	var w1 = $(".guide_scroll").width();

	$(".guide_tab").click(function(){ // 滑鼠點擊時
		if($(".guide_scroll").css('left') == '-'+ w1 +'px')
		{
			$(".guide_scroll").animate({left:'0px'}, 1000 ,'swing');
		}else{
			$(".guide_scroll").animate({left:'-'+ w1 +'px'}, 1000 ,'swing');
		}
	});
	// 工具包點擊顯示及收合
	var w2 = $(".tool_scroll").width();

	$(".tool_tab").click(function(){ //滑鼠點擊時
		if($(".tool_scroll").css('right') == '-'+ w2 +'px')
		{
			$(".tool_scroll").animate({right:'0px'}, 500 ,'swing');
		}else{
			$(".tool_scroll").animate({right:'-'+ w2 +'px'}, 500 ,'swing');
		}
	});
/*------------------------------------前往求助------------------------------------*/
	$(".help_btn").click(function(){
		window.location.href = "tool_suggest.php";
	});
/*---------------------------------待完成專題任務---------------------------------*/
	$(".stage_title").click(function(){
		$(".stage_describe").toggle();
	});
	$(".stage_go").click(function(){
		// window.location.href = "project/?stage=" + this.id;
		window.location.href = "nav_project.php";
	});
/*------------------------------------任務地圖------------------------------------*/
	$(".guide_map_list li").click(function(){
		var stage = $(this).text();

		if($(this).attr("style") != undefined){	// 判斷是否有亮
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : 'read_guide',
					stage : stage,
					action : 'guide_update'
				},
				error : function(e, status){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						if(data[a].guide_stage != undefined){
							$(".guide_stage").html(data[a].guide_stage +" "+ data[a].guide_name);
							$(".guide_result").html(data[a].guide_result);
							$(".guide_comment").html(data[a].guide_comment);
							$(".guide_complete").attr("id", data[a].guide_stage);
						}
					}
					$(".guide_box").attr("id", "guide_box_"+ stage);
				}
			});
		}
	});
	$(".guide_complete").click(function(){
		alert("【系統】系統尚未建置。");
	});
	$(".guide_examine").click(function(){
		$("#guide_examine_fancybox").show();
	});
	$("#guide_examine_check").click(function(){
		$("#guide_examine_fancybox").hide();
	});
/*----------------------------小組協作空間：待完成任務----------------------------*/
	// $(".stage_title_small").click(function(){
	// 	$(".stage_describe_small").toggle();
	// });
	$(".stage_guide").click(function(){
		$("#research_guide_fancybox").show();
	});
/*-----------------------------小組協作空間：協作區域-----------------------------*/
	$(".function_stage li").click(function(){
		var stage = $(this).parent().attr('id');
		// 換頁(討論區、工作室、資料庫)
		window.location.href = "../project/" + this.id + ".php?stage="+ stage;
	});
/*-----------------------------小組協作空間：活動指引-----------------------------*/
	$("#research_guide_tabs").tabs();

	$("#research_guide_submit").click(function(){
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : 'check_guide',
				action : 'guide_update'
			},
			error : function(e, status){
				alert("【警告】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				$(".fancybox_box").hide();
				return;
			}
		});
	});
/*------------------------------小組協作空間：討論區------------------------------*/
	$("#page_stage1").show();
	$("#stage1").addClass('show_stage').removeClass('stage');
	$("#stage1").css("background-color", "#BDBDBD");
	// 按鈕陣列
	var stage_arr = ["stage1", "stage2", "stage3", "stage4", "stage5"];
	// 設定清單的出現和隱藏
	for (var i = stage_arr.length - 1; i >= 0; i--) {
		// 設定按鈕的變化
		$("#" + stage_arr[i]).click(function() {
			var stage_id = this.id;
			if(!$("." + stage_id).hasClass("show_stage")){
				$(".show_stage").removeClass('show_stage').addClass('stage');
				$("#" + stage_id).addClass('show_stage').removeClass('stage');
				// 按鈕變色
				$(".discuss_stage li").css("background-color", "#F1FAFA");	
				$("#" + stage_id).css("background-color", "#BDBDBD");
			}
		});
		// 設定畫面的出現和隱藏
		$("#" + stage_arr[i]).click(function(){
			var stage_id = this.id;
			if(!$("." + stage_id).hasClass("show_stage")){
				$(".discuss_pages").hide();
				$("#page_" + stage_id).show();
			}
		});
	};
	// 開起新增討論串
	$(".discuss_box_new").click(function(){
		$("#discuss_new_fancybox").show();
	});
	// 新增討論串
	$("#discuss_add").click(function(){
		var stage = $("select[name=discuss_stage]").val();

		if($("input[name=discuss_title]").val() == ''){
			alert("【系統】討論主題尚未填寫。");
		}else if($("select[name=discuss_stage]").val() == 'default'){
			alert("【系統】請選擇討論階段。");
		}else{
			$("#discuss_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_discuss",
					action : "discuss_update"
				},
				error : function(){
					alert("【系統】新增失敗！請確認是否已有此討論串或網路是否順暢！？");
					return;
				},
				success : function(data){
					alert("【系統】討論串新增成功！");
					window.location.href = "../project/discuss.php?stage="+ stage;
				}
			});
		}
	});
	// 對討論串按讚
	$(".discuss_good").click(function(){
		var discuss_id = $(this).parent().parent().parent().attr('id');		// 討論ID

		if($(this).attr("value") == '0'){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "good_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#good" + data[a].discuss_id).attr("value", 1);
						$("#good" + data[a].discuss_id).attr("src","../../model/images/discuss_good.png");
						$("#number" + data[a].discuss_id).text(data[a].discuss_number);
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "ungood_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#good" + data[a].discuss_id).attr("value", 0);
						$("#good" + data[a].discuss_id).attr("src","../../model/images/discuss_ungood.png");
						$("#number" + data[a].discuss_id).text(data[a].discuss_number);
						return;
					}
				}
			});
		}
	});
	// 對討論串註記星號
	$(".discuss_star").click(function(){
		var discuss_id = $(this).parent().parent().parent().attr('id');		// 討論ID

		if($(this).attr("value") == '0'){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "star_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#star" + data[a].discuss_id).attr("value", 1);
						$("#star" + data[a].discuss_id).attr("src","../../model/images/discuss_star.png");
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "unstar_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#star" + data[a].discuss_id).attr("value", 0);
						$("#star" + data[a].discuss_id).attr("src","../../model/images/discuss_unstar.png");
						return;
					}
				}
			});
		}
	});
	// 對討論串做書籤
	$(".discuss_bookmark").click(function(){
		var discuss_id = $(this).parent().attr('id');		// 討論ID
		console.log(discuss_id);
		if($(this).attr("value") == '0'){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "bookmark_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#bookmark" + data[a].discuss_id).attr("value", 1);
						$("#bookmark" + data[a].discuss_id).attr("src","../../model/images/discuss_bookmark.png");
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "unbookmark_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#bookmark" + data[a].discuss_id).attr("value", 0);
						$("#bookmark" + data[a].discuss_id).attr("src","../../model/images/discuss_unbookmark.png");
						return;
					}
				}
			});
		}
	});
	// 開啟討論區
	$(".discuss_chat").click(function(){
		var discuss_id = $(this).parent().parent().parent().attr('id');		// 討論ID
		// console.log(discuss_id);
		$("#discuss_reply").show();
		$('#discuss_reply_content').html("");

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type : 'get_discuss',
				discuss_id : discuss_id,
				action : 'discuss_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
					$("input[name=discuss_id]").val(data[a].discuss_id);
					$("#discuss_user").html(data[a].discuss_user);
					$("#discuss_title").html(data[a].discuss_title);
					$("#discuss_description").html(data[a].discuss_description);
					// 有無附加檔案
					if(data[a].discuss_filename != undefined){
						$("#discuss_filename").html("<a href="+ data[a].discuss_fileurl +" download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].discuss_filename +"</a>");
					}else{
						$("#discuss_filename").html("無檔案");
					}
					
					if(data[a].discuss_r_content != undefined){
						$("#discuss_reply").hide();
						$("<p>"+ data[a].discuss_r_uid +"："+ data[a].discuss_r_content +"</p>").appendTo("#discuss_reply_content");
					}
					if(data[a].discuss_r_filename != undefined){
						$("<a href='"+ data[a].discuss_r_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].discuss_r_filename +"</a>").appendTo("#discuss_reply_content");
					}
				}
				$("#discuss_read_fancybox").show();
			}
		});
	});
	// 回覆討論
	$("#discuss_read_add").click(function(){
		var discuss_id = $("input[name=discuss_id]").val();		// 討論ID
		// console.log(discuss_id);
		$("input[name=discuss_read_content]").html("");

		$("#discuss_reply").show();

		if($("input[name=discuss_read_content]").val() == ''){
			alert("【系統】尚未填寫回覆內容。");
		}else{
			$("#discuss_read_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "reply_discuss",
					discuss_id : discuss_id,
					action : "discuss_update"
				},
				error : function(){
					alert("【系統】回覆失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("#discuss_reply").hide();
						$("#discuss_reply_content").append("<p>"+ data[a].discuss_r_uid +"："+ data[a].discuss_r_content +"</p>");
						
						if(data[a].discuss_r_filename != undefined){
							$("<a href='"+ data[a].discuss_r_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].discuss_r_filename +"</a>").appendTo("#discuss_reply_content");
						}
					}
				}
			});
		}
	});
/*------------------------------小組協作空間：工作室------------------------------*/
	$('.steps_menu li.active').addClass('open').children('ul').show();
	$('.steps_menu > ul > li.active > a').css("background-color", "#77DF88;");
	$('.steps_menu li.sub-sub>a').removeAttr('href');	// 全部
	$('.steps_menu li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');	// 按壓，去除#
		// list變色
		$('.steps_menu > ul > li > a').css("background-color", "#59B791;");
		$(this).css("background-color", "#77DF88;");

		var element = $(this).parent('li');
		if(element.hasClass('open')){
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp(200);
		}else{
			element.addClass('open');
			element.children('ul').slideDown(200);
			element.siblings('li').children('ul').slideUp(200);
			element.siblings('li').removeClass('open');
			element.siblings('li').find('li').removeClass('open');
			element.siblings('li').find('ul').slideUp(200);
		}
	});
/*------------------------------小組協作空間：資料庫------------------------------*/
	$("#page_database1").show();
	$("#database1").addClass('show_database').removeClass('database');
	$("#database1").css("background-color", "#BDBDBD");
	// 按鈕陣列
	var database_arr = ["database1", "database2", "database3", "database4", "database5"];
	// 設定清單的出現和隱藏
	for (var i = database_arr.length - 1; i >= 0; i--){
		// 設定按鈕的變化
		$("#" + database_arr[i]).click(function(){
			var database_id = this.id;
			if(!$("." + database_id).hasClass("show_database")){
				$(".show_database").removeClass('show_database').addClass('database');
				$("#" + database_id).addClass('show_database').removeClass('database');
				// 按鈕變色
				$(".database_types li").css("background-color", "#F1FAFA");	
				$("#" + database_id).css("background-color", "#BDBDBD");
			}
		});
		// 設定畫面的出現和隱藏
		$("#" + database_arr[i]).click(function(){
			var database_id = this.id;
			// console.log(database_id);
			if(!$("." + database_id).hasClass("show_database")){
				$(".database_pages").hide();
				$("#page_" + database_id).show();
			}
		});
	};
	// 新增資料庫
	$(".database_new").click(function(){
		$("#database_new_fancybox").show();
	});
	// 選擇資源類型
	$("select[name=database_new_types]").change(function(){
		var database_types = $(this).val();
		// console.log(database_types);
		if(database_types == 'default'){
			$(".database_area").hide();
		}else{
			$(".database_area").hide();
			$("#database_area"+ database_types).show();
		}
	});
	// 增加關鍵字
	$(".database_new_kwd").change(function(){
		var kwd_id = $(this).val();
		// console.log(kwd_id);
		if(kwd_id == 'new'){
			$(".kwd_new_area").show();
		}
	});
	$(".kwd_new").click(function(){
		var kwd_type = $("select[name=database_new_types]").val();	// 位置
		var kwd_name = $("#kwd_name"+ kwd_type).val();
		// console.log(kwd_name);
		if(kwd_name != ""){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_kwd",
					kwd_name : kwd_name,
					action : "database_update"
				},
				error : function(){
					alert("【系統】新增失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					for(var a in data){
						$("<option value='"+ data[a].kwd_id +"'>"+ data[a].kwd_name +"</option>").appendTo(".database_new_kwd");
					}
					// 隱藏新增
					$(".kwd_new_area").hide();
					$("#kwd_name"+ kwd_type).val("");
					$(".database_new_kwd").val("default");

					alert("【系統】關鍵字新增成功！");
				}
			});
		}
	});
	// 點擊[加入]，加入關鍵字
	$(".kwd_add").click(function(){
		var kwd_type = $("select[name=database_new_types]").val();	// 位置
		var kwd_id = $("#database_new_kwd"+ kwd_type).val();
		var kwd_name = $("#database_new_kwd"+ kwd_type).find("option[value='"+ kwd_id +"']").text();
		// console.log(kwd_type, kwd_id, kwd_name);
		if(kwd_id != "default" && kwd_id != "new"){
			$("<div class='kwd_choosed' id='kwd_choosed"+ kwd_type +"' value='"+ kwd_id +"'>"+ kwd_name +
				"<sapn class='kwd_deleted' id='kwd_deleted"+ kwd_type +"'>[刪除]</sapn>"+
				"</div>").appendTo("#kwd_block"+ kwd_type);
			$("#database_new_kwd"+ kwd_type +" option[value='"+ kwd_id +"']").hide();
			$("#database_new_kwd"+ kwd_type).val("default");
		}
		// 點擊資料庫裡的[刪除]，刪除關鍵字
		$(".kwd_deleted").click(function() {
			var kwd_id = $(this).parent().attr("value");
			var kwd_name = $(this).parent().text();
			// console.log(kwd_type, kwd_id, kwd_name);
			$("#database_new_kwd"+ kwd_type +" option[value='"+ kwd_id +"']").show();
			$(this).parent().remove();
		});
	});
	$("#database_add").click(function(){
		var kwd_type = $("select[name=database_new_types]").val();	// 位置
		var kwd_id = [];				// 關鍵字(至少三種)

		$("#database_new_form #kwd_block"+ kwd_type +" #kwd_choosed"+ kwd_type).each(function(){	// 至少三種關鍵字
			kwd_id.push($(this).attr("value"));
		});
		// console.log(kwd_type, kwd_id);
		if($("#database_new_form input[name=database_new_title"+ kwd_type +"]").val() == "" || $("#database_new_form input[name=database_new_name"+ kwd_type +"]").val() == "" || $("#database_new_form input[name=database_new_authors"+ kwd_type +"]").val() == ""){
			alert("【系統】資料庫尚未填寫完成。");
		}else if(kwd_id.length < 3){
			alert("【系統】請至少列出三種關鍵字。");
		}else{
			$("#database_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_database",
					kwd_id : kwd_id,
					kwd_type : kwd_type,
					action : "database_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】資料庫新增成功！");
					window.location.reload();
				}
			});
		}
	});
/*----------------------------------系統使用說明----------------------------------*/
	$("#system_btn").click(function(){
		$("#system_use_fancybox").show();
	});
	$(".system_menu li").click(function(){
		var str = $(this).attr('value');
		switch(str){
			case '1':  // 歡迎
				document.getElementById('video_frame').src='//www.youtube.com/embed/SxiW3lyGDW0';
				break;
			case '2':  // 學生首頁
				document.getElementById('video_frame').src='//www.youtube.com/embed/pNYZzg-ktkY';
				break;
			case '3':  // 申請小組
				document.getElementById('video_frame').src='//www.youtube.com/embed/3Uc2wSkQags';
				break;
			case '4':  // 小組協作空間
				document.getElementById('video_frame').src='//www.youtube.com/embed/sQyM1fRmT7o';
				break;
			case '5':  // 任務地圖
				document.getElementById('video_frame').src='//www.youtube.com/embed/YDdGOBtmuXw';
				break;
			case '6':  // 日誌專區
				document.getElementById('video_frame').src='//www.youtube.com/embed/tgV1M5nwBes';
				break;
			case '7':  // 觀摩作品
				document.getElementById('video_frame').src='//www.youtube.com/embed/K7b8cbSljgQ';
				break;
			case '8':  // 學生求助
				document.getElementById('video_frame').src='//www.youtube.com/embed/IQVDA6vnayQ';
				break;
			case '9':  // 常用網站
				document.getElementById('video_frame').src='//www.youtube.com/embed/EANL-7eCP8U';
				break;
		}
	});
/*------------------------------小組協作空間：共用區------------------------------*/
	$(".fancybox_cancel").click(function(){
		$(".fancybox_box").hide();
	});
/*------------------------------------登出系統------------------------------------*/
	$("#logout_btn").click(function(){
		var x = confirm("【系統】請問是否要登出？");
		if(x){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				data : {
					action : 'logout'
				},
				error : function(e, status){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("【系統】歡迎再次使用，再見.....T^T");
					window.location.href = /co.in/; // 登出按鈕
				}
			});
		}
	});
/*----------------------------------紀錄使用者LOG----------------------------------*/
	$(".log_btn").click(function(){
		var log = $(this).attr('value');
		console.log(log);
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			data : {
				type : "record_log",
				log : log,
				action : "log_update"
			},
			error : function(){
				// alert("【系統】網路發生異常！請檢查網路連線狀況！");  // 一直跳錯誤
				return;
			},
			success : function(data){
				return;
			}
		});
	});
});