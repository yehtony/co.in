$(function(){
/*-------------------------------------專題指導-------------------------------------*/
	$("#index_btn").click(function(){
		window.location.href = "/co.in/science/teacher/";
	});
	// 功能按鈕
	$("#news_btn").click(function(){
		$.ajax({
			url : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			data : {
				type : 'read_news',
				action : 'news_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				$("#news_bubble").hide();
				$("#news_table").toggle();
			}
		});
	});
	// 更多消息
	$("#news_more").click(function(){
		window.location.href = "/co.in/science/teacher/func_news.php";
	});
	$("#func_btn").click(function(){
		$("#func_table").toggle();
	});
	// 教師指導日誌點擊顯示及收合
	var w1 = $(".guide_scroll").width();

	$(".guide_tab").click(function(){ // 滑鼠點擊時
		if($(".guide_scroll").css('left') == '-'+ w1 +'px'){
			$(".guide_scroll").animate({left:'0px'}, 1000 ,'swing');
		}else{
			$(".guide_scroll").animate({left:'-'+ w1 +'px'}, 1000 ,'swing');
		}
	});
	// 工具包點擊顯示及收合
	var w2 = $(".tool_scroll").width();

	$(".tool_tab").click(function(){ // 滑鼠點擊時
		if($(".tool_scroll").css('right') == '-'+ w2 +'px'){
			$(".tool_scroll").animate({right:'0px'}, 500 ,'swing');
		}else{
			$(".tool_scroll").animate({right:'-'+ w2 +'px'}, 500 ,'swing');
		}
	});
/*-------------------------------------發送廣播-------------------------------------*/
	$(".help_btn").click(function(){
		$("#help_new_fancybox").show();
	});
	$("#help_add").click(function(){
		if($("#help_new_form select[name=help_group]").val() == "default" || $("#help_new_form select[name=help_group]").val() == "NULL" || $("#help_new_form select[name=help_type]").val() == "default"){
			alert("【系統】尚未選擇小組或類型。");
		}else if($("#help_new_form input").val() == "" || $("#help_new_form textarea").val() == ""){
			alert("【系統】尚未填寫主題與內容。");
		}else{
			$("#help_new_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_help",
					action : "help_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】廣播發送成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".help_reply").click(function(){
		var help_id = $(this).parent().parent().attr('id');
		// console.log(help_id);
		$("#help_reply").show();
		$('#help_reply_content').html("");

		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_help",
				help_id : help_id,
				action : "help_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					$('input[name=help_id]').val(data[a].help_id);	// 將結果顯示出來
					$('#help_date').html(data[a].help_time);	
					$('#help_people').html(data[a].help_uid);
					$('#help_type').html(data[a].help_type);
					$('#help_title').html(data[a].help_title);
					$('#help_description').html(data[a].help_description);

					// 判斷檔案是否為空值
					if(data[a].help_filename != undefined){
						$('#help_filename').html("<a href='"+ data[a].help_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].help_filename +"</a>");
					}
					if(data[a].help_r_content != undefined){
						$("#help_reply").hide();
						$("<p>"+ data[a].help_r_uid +"："+ data[a].help_r_content +"</p>").appendTo("#help_reply_content");
					}
					if(data[a].help_r_filename != undefined){
						$("<a href='"+ data[a].help_r_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].help_r_filename +"</a>").appendTo("#help_reply_content");
					}
				}
				$("#help_read_fancybox").show();
			}
		});	
	});
	$("#help_submit").click(function(){
		var help_id = $('input[name=help_id]').val();
		// console.log(help_id);
		if($("textarea[name=help_reply]").val() == ""){
			alert("【系統】尚未填寫回覆內容。");
		}else{
			$("#help_read_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "reply_help",
					help_id : help_id,
					action : "help_update"
				},
				error : function(){
					alert("【系統】回覆失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】回覆成功！");
					window.location.reload();
				}
			});
		}
	});
/*-----------------------------------審核任務詳情-----------------------------------*/
	// 審核詳情
	var tmp = 1;													// 問題順序

	$(".examine_detail").click(function(){
		var p_id = $(this).parent().parent().attr('id');			// 專題ID
		var stage = $(this).parent().parent().attr('class');		// 階段
		// var q_id = $(this).attr('value');						// 問題ID
		var order = 1;															
		console.log(p_id, stage, order);
		// 2-1研究問題表格
		$(".question_read_tr ~ tr").html("");
		// 2-2研究構想表
		$(".question_material").html("");
		$(".question_steps").html("");
		// 2-3研究紀錄表
		$(".research_record_tr ~ tr").html("");
		// 2-4總嘗試性實驗
		$(".research_pilot_tr ~ tr").html("");
		// 3-1實驗日誌
		$(".research_experiment_tr ~ tr").html("");
		// 3-2實驗分析
		$(".research_analysis_tr ~ tr").html("");
		// 3-3實驗結果
		$(".research_result_tr ~ tr").html("");
		// 4-1實驗討論
		$(".research_discussion_tr ~ tr").html("");
		// 4-2研究結論
		// var num = 1;
		$(".research_conclusion_area").html("");
		// 5-1研究報告
		$(".research_complete_area").html("");
		// 5-2研究海報
		$(".research_report_area").html("");
		// 5-3研究影片
		$(".research_vedio_area").html("");
		// 5-4研究問與答
		$(".research_qna_area").html("");

		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type : 'get_examine',
				p_id : p_id,
				stage : stage,
				order : order,
				action: 'examine_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
					if(stage == '1-1'){
						// 研究主題
						$(".theme_read_name").html(data[a].theme_name);
						$(".theme_read_user").html(data[a].theme_user);
						$(".theme_read_src").html(data[a].theme_src);
						$(".theme_read_description").html(data[a].theme_description);
						if(data[a].theme_filename != undefined){
							$(".theme_read_filename").html("<a href="+ data[a].theme_fileurl +" download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].theme_filename +"</a>");
						}else{
							$(".theme_read_filename").html("無檔案");
						}
						$(".theme_read_time").html(data[a].theme_time);
						// 研究題目
						$(".topic_read_name").html(data[a].topic_name);
						$(".topic_read_user").html(data[a].topic_user);
						$(".topic_read_description").html(data[a].topic_description);
						if(data[a].topic_data != ""){
							$(".topic_read_data").html(data[a].topic_data);
						}else{
							$(".topic_read_data").html("無參考資料");
						}
						if(data[a].topic_filename != undefined){
							$(".topic_read_filename").html("<a href="+ data[a].topic_fileurl +" download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].topic_filename +"</a>");
						}else{
							$(".topic_read_filename").html("無檔案");
						}
						$(".topic_read_time").html(data[a].topic_time);
					}else if(stage == '2-1'){
						// 研究問題
						$("<tr><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'>"+ data[a].question_independent +"</td><td align='center'>"+ data[a].question_dependent +"</td></tr>").appendTo(".question_read_table");
					}else if(stage == '2-2'){
						// 研究構想表
						$(".question_name").html(data[a].question_name);
						$(".question_assume").html(data[a].question_assume);
						$(".question_independent").html(data[a].question_independent);
						$(".question_dependent").html(data[a].question_dependent);
						$(".question_record").html(data[a].record_name);
						// 材料與工具
						if(data[a].material_name != undefined){
							$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
							  "</div>").appendTo(".question_material");
						}
						// 研究步驟
						if(data[a].steps_name != undefined){
							var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

							$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td>"+ data[a].steps_name +"</td></tr></table>").appendTo(".question_steps");
						}
						// 傳值給左右鍵
						$(".research_idea_table").attr("id", data[a].question_number);
						$(".research_idea_table").attr("value", p_id +"|"+ stage +"|"+ data[a].question_id);
					}else if(stage == '2-3'){
						// 研究紀錄表
	 					$("<tr id='"+ data[a].question_id +"' value='"+ data[a].question_order +"'><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'><button class='research_idea_view'>瀏覽</button></td><td align='center'><a href='"+ data[a].question_fileurl +"' download='實驗紀錄表"+ data[a].question_order +"'>下載</a></td></tr>").appendTo(".research_record_table");
					}else if(stage == '2-4'){
						// 研究嘗試性實驗
						$("<tr id='"+ data[a].question_id +"' value='"+ data[a].question_order +"'><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'><button class='research_idea_view'>瀏覽</button></td><td align='center'>"+ data[a].question_result +"</td><td align='center'><button class='research_pilot_view'>瀏覽</button></td></tr>").appendTo(".research_pilot_table");
					}else if(stage == '3-1'){
						// 研究實驗日誌
						$("<tr id='"+ data[a].question_id +"' value='"+ data[a].question_order +"'><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'><button class='research_idea_view'>瀏覽</button></td><td align='center'><a href='"+ data[a].question_record +"' download>下載</a></td><td align='center'>"+ data[a].question_result +"</td><td align='center'><button class='research_experiment_view'>瀏覽</button></td></tr>").appendTo(".research_experiment_table");
					}else if(stage == '3-2'){
						// 研究實驗分析
						$("<tr id='"+ data[a].question_id +"' value='"+ data[a].question_order +"'><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'><button class='research_idea_view'>瀏覽</button></td><td align='center'><button class='research_experiment_view'>瀏覽</button></td><td align='center'><a href='"+ data[a].question_fileurl +"' download>下載</a></td></tr>").appendTo(".research_analysis_table");
					}else if(stage == '3-3'){
						// 研究實驗分析
						$("<tr id='"+ data[a].question_id +"' value='"+ data[a].question_order +"'><td>"+ data[a].question_name +"</td><td>"+ data[a].question_assume +"</td><td align='center'><button class='research_idea_view'>瀏覽</button></td><td align='center'><button class='research_experiment_view'>瀏覽</button></td><td align='center'><a href='"+ data[a].question_fileurl1 +"' download>下載</a></td><td align='center'><a href='"+ data[a].question_fileurl2 +"' download>下載</a></td></tr>").appendTo(".research_result_table");
					}else if(stage == '4-1'){
						// 研究實驗討論
						if(data[a].discussion_type == '0'){
							$("<tr><td>一般性</td><td id='discussion"+ data[a].discussion_id +"'>"+ data[a].discussion_related +"</td><td>"+ data[a].discussion_description +"</td></tr>").appendTo(".research_discussion_table");
						}else if(data[a].discussion_type == '1' && data[a].type == 'topic'){
							$("<tr><td>綜合性</td><td id='discussion"+ data[a].discussion_id +"'></td><td>"+ data[a].discussion_description +"</td></tr>").appendTo(".research_discussion_table");			
						}else if(data[a].type == 'question'){	// 相關性研究(全部)
							$("<div>"+ data[a].discussion_related +"</div>").appendTo('#discussion' + data[a].discussion_id);
						}
					}else if(stage == '4-2'){
						// 研究實驗結論
						$("<table class='research_conclusion_table' border='1' cellspacing='0'>"+
							"<tr id='"+ data[a].conclusion_id +"'>"+
								"<th width='20%'>"+ order +"</th>"+
								"<td width='80%'>"+ data[a].conclusion_content +"</td>"+
							"</tr>"+
						"</table>").appendTo(".research_conclusion_area");
						order++;
					}else if(stage == '5-1'){
						// 下載研究報告
						$("<table width='100%' border='1'>"+
							"<tr>"+
								"<th width='20%'>"+ data[a].complete_pname +"</th>"+
								"<td width='80%'><a href='"+ data[a].complete_fileurl +"' download>作品報告下載</a></td>"+
							"</tr>"+
						"</table>").appendTo(".research_complete_area");
					}else if(stage == '5-2'){
						// 觀看作品報告
						$("<table class='research_review_table'>"+
							"<tr>"+
								"<td style='border: 0px;'></td><td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189"+ data[a].report_fileurl0 +"&embedded=true' style='width: 265px; height: 90px;' frameborder='0'></iframe></td><td style='border: 0px;'></td>"+
							"</tr>"+
							"<tr>"+
								"<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189"+ data[a].report_fileurl1 +"&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>"+
								"<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189"+ data[a].report_fileurl2 +"&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>"+
								"<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189"+ data[a].report_fileurl3 +"&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>"+
							"</tr>"+
						"</table>").appendTo(".research_report_area");
					}else if(stage == '5-3'){
						// 觀看作品影片
						$(data[a].vedio_fileurl).appendTo(".research_vedio_area");
					}else if(stage == '5-4'){
						// 觀看問與答
						$("<div><h4>Q"+ data[a].qna_order +". "+ data[a].qna_question +"</h4><p style='margin-left: 35px;'>"+ data[a].qna_answer +"</p></div>").appendTo(".research_qna_area");
					}
				}
				$("#examine_detail_fancybox"+ stage).show();
				// 瀏覽研究構想表
				$(".research_idea_view").click(function(){
					var order = $(this).parent().parent().attr('value');
					// console.log(p_id, stage, order);
					$(".question_material").html(""); // 一開始則清空
					$(".question_steps").html("");

					$.ajax({
						url  : "/co.in/science/teacher/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data: {
							type : 'get_examine',
							p_id : p_id,
							stage : '2-2',
							order : order,
							action: 'examine_update'
						},
						error: function(){
							alert("【警告】讀取失敗！請檢查網絡連線問題！");
							return;
						},
						success: function(data){
							for(var a in data){
								// 研究構想表
								$(".question_name").html(data[a].question_name);
								$(".question_assume").html(data[a].question_assume);
								$(".question_independent").html(data[a].question_independent);
								$(".question_dependent").html(data[a].question_dependent);
								$(".question_record").html(data[a].record_name);
								// 材料與工具
								if(data[a].material_name != undefined){
									$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
										  "</div>").appendTo(".question_material");
								}
								// 研究步驟
								if(data[a].steps_name != undefined){
									var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

									$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td>"+ data[a].steps_name +"</td></tr></table>").appendTo(".question_steps");
								}
							}
							$("#examine_detail_fancybox"+ stage).hide();
							$("#examine_idea_fancybox").show();

							$("#examine_idea_submit").click(function(){
								$("#examine_idea_fancybox").hide();
								$("#examine_detail_fancybox"+ stage).show();
							});
						}
					});
				});
				// 瀏覽嘗試性實驗
				$(".research_pilot_view").click(function(){
					var order = $(this).parent().parent().attr('value');
					console.log(p_id, stage, order);
					$.ajax({
						url  : "/co.in/science/teacher/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data: {
							type : 'get_examine',
							p_id : p_id,
							stage : '2-4-p',
							order : order,
							action: 'examine_update'
						},
						error: function(){
							alert("【警告】讀取失敗！請檢查網絡連線問題！");
							return;
						},
						success: function(data){
							for(var a in data){
								// 研究嘗試性實驗
								$(".pilot_read_question").html(data[a].question_name);
								$(".pilot_read_assume").html(data[a].question_assume);
								$(".pilot_read_result").html(data[a].question_result);
								$(".pilot_read_description").html(data[a].question_description);
								$(".pilot_read_attention").html(data[a].question_attention);
								$(".pilot_read_fixed").html(data[a].question_fixed);

								if(data[a].question_fileurl != undefined){
									$(".pilot_read_fileurl").html("<a href="+ data[a].question_fileurl +" download>下載</a>");
								}else{
									$(".pilot_read_fileurl").html("無檔案");
								}
							}
							$("#examine_detail_fancybox"+ stage).hide();
							$("#examine_detail_fancybox2-4-p").show();

							$("#examine_pilot_submit").click(function(){
								$("#examine_detail_fancybox2-4-p").hide();
								$("#examine_detail_fancybox"+ stage).show();
							});
						}
					});
				});
				// 瀏覽實驗日誌
				$(".research_experiment_view").click(function(){
					var order = $(this).parent().parent().attr('value');
					// console.log(p_id, stage, order);
					$.ajax({
						url  : "/co.in/science/teacher/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data: {
							type : 'get_examine',
							p_id : p_id,
							stage : '3-1-e',
							order : order,
							action: 'examine_update'
						},
						error: function(){
							alert("【警告】讀取失敗！請檢查網絡連線問題！");
							return;
						},
						success: function(data){
							for(var a in data){
								// 研究嘗試性實驗
								$(".experiment_read_question").html(data[a].question_name);
								$(".experiment_read_assume").html(data[a].question_assume);
								$(".experiment_read_independent").html(data[a].question_independent);
								$(".experiment_read_dependent").html(data[a].question_dependent);
								$(".experiment_read_date").html(data[a].question_date);
								$(".experiment_read_result").html(data[a].question_result);
								$(".experiment_read_description").html(data[a].question_description);

								if(data[a].question_fileurl != undefined){
									$(".experiment_read_fileurl").attr("href", data[a].question_fileurl);
								}else{
									$(".experiment_read_fileurl").removeAttr("href");
								}
							}
							$("#examine_detail_fancybox"+ stage).hide();
							$("#examine_experiment_fancybox").show();

							$("#examine_experiment_submit").click(function(){
								$("#examine_experiment_fancybox").hide();
								$("#examine_detail_fancybox"+ stage).show();
							});
						}
					});
				});
			}
		});
	});
	// 審核詳情(嘗試性實驗)
	$(".examine_pilot").click(function(){
		var p_id = $(this).parent().parent().attr('id');					// 專題ID
		var stage = $(this).parent().parent().attr('class').substr(0, 3);	// 階段，擷取字串
		// var q_id = $(this).attr('value');								// 問題ID
		var order = $(this).parent().parent().attr('class').substr(4, 1);
		console.log(p_id, stage, order);
		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type : 'get_examine',
				p_id : p_id,
				stage : '2-4-p',
				order : order,
				action: 'examine_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
					// 研究嘗試性實驗
					$(".pilot_read_question").html(data[a].question_name);
					$(".pilot_read_assume").html(data[a].question_assume);
					$(".pilot_read_result").html(data[a].question_result);
					$(".pilot_read_description").html(data[a].question_description);
					$(".pilot_read_attention").html(data[a].question_attention);
					$(".pilot_read_fixed").html(data[a].question_fixed);

					if(data[a].question_fileurl != undefined){
						$(".pilot_read_fileurl").html("<a href="+ data[a].question_fileurl +" download>下載</a>");
					}else{
						$(".pilot_read_fileurl").html("無檔案");
					}
				}
				$("#examine_detail_fancybox2-4-p").show();
			}
		});
	});
	// 開起教師提問單
	$(".examine_qna").click(function(){
		var p_id = $(this).parent().parent().attr('id');			// 專題ID

		$("#qna_form input[name=project_id]").attr("value", p_id);

		$("#examine_qna_fancybox").show();
	});
	var counter = 2;
	// 新增動態
	$("#qna_add").click(function(){
		if(counter > 6){
			alert("【系統】目前最多十個問題，如有問題可以聯絡管理員。");
			return false;
		}
		var newQna = $(document.createElement('div')).attr("id", "qna_area"+ counter);
			newQna.after().html('<textarea type="text" name="qna_write'+ counter +'" placeholder="問題#'+ counter +'">');

			newQna.appendTo("#qna_form");

			counter++;
	});
	// 移除動態
	$("#qna_remove").click(function(){
		if(counter == 2){
			alert("【系統】最少需要一個問題。");
			return false;
		}
		counter--;

		$("#qna_area" + counter).remove();
	});
	// 動態取值
	$("#examine_qna_submit").click(function(){
		var p_id = $("input[name=project_id]").val();
		var qnalist = [];				// 提問單題目
		var check = "true";				// 是否全填寫

		$("#qna_form textarea").each(function(){
			qnalist.push($(this).val());

			if($(this).val() == ""){
				check = "false";
			};
		});
		console.log(p_id, qnalist, check);
		
		if(check == "false"){
			alert("【系統】請確認提問單是否以填寫完成？");
		}else{
			var x = confirm("【系統】確定送出提問單後，就無法修改囉？");
			if(x){
				$("#qna_form").ajaxSubmit({
					url  : "/co.in/science/teacher/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_qna",
						p_id : p_id,
						qnalist : qnalist,
						action : "qna_update"
					},
					error : function(){
						alert("【系統】送出失敗！請再重試一次！");
						return;
					},
					success : function(data){
						alert("【系統】提問單提交成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 審核確定
	$(".examine_detail_submit, .examine_pilot_submit").click(function(){
		$(".fancybox_box").hide();
	});
	// 審核通過OR不通過
	$(".examine_pass, .examine_unpass").click(function(){
		var p_id = $(this).parent().parent().attr('id');				// 專題ID
		var stage = $(this).parent().parent().attr('class');			// 階段
		var pass = $(this).attr('value');
		// console.log(p_id, stage, pass);
		$("input[name=examine_pass_project]").val(p_id);
		$("input[name=examine_pass_stage]").val(stage);
		$("input[name=examine_pass_pass]").val(pass);

		if(pass == 'pass'){
			$(".examine_pass_back").hide();
		}else{
			$(".examine_pass_back").show();
		}
		$("#examine_pass_fancybox").show();
	});
	// 審核通過送出
	$("#examine_pass_submit").click(function(){
		var p_id = $("input[name=examine_pass_project]").val();			// 專題ID
		var stage = $("input[name=examine_pass_stage]").val();			// 階段
		var pass = $("input[name=examine_pass_pass]").val();
		// console.log(p_id, stage, pass);
		if($("select[name=examine_pass_back]").val() == "default" && pass == "unpass"){
			alert("【系統】請選擇退回階段！")
		}else{
			var x = confirm("【系統】確定此小組階段"+ stage +"審核結果？");
			if(x){
				$("#examine_pass_form").ajaxSubmit({
					url  : "/co.in/science/teacher/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data: {
						type : 'check_examine',
						p_id : p_id,
						stage : stage,
						pass : pass,
						action : 'examine_update'
					},
					error: function(){
						alert("【警告】讀取失敗！請檢查網絡連線問題！");
						return;
					},
					success: function(data){
						alert("【系統】已送出審核通知！！");
						window.location.reload();
						return;
					}
				});
			}
		}
	});
	// 更多審核
	$("#examine_more").click(function(){
		window.location.href = "/co.in/science/teacher/project_examine.php";
	});
/*-----------------------------------教師指導手冊-----------------------------------*/
	$('.guide_menu li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');
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
	$(".guide_menu li li").click(function(){
		var g_id = $(this).attr('id');

		$.ajax({
			url: "/co.in/science/teacher/api/php/science.php",
			type: "POST",
			data: {
				type : 'read_guide',
				g_id : g_id,
				action: 'guide_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				$('.guide_book_content').html(data);	// 將結果顯示出來

				$(".guide_hint").mouseover(function(){
					var hint = $(this).attr('value');
					$(".guide_hint_area").html("<h5><img src='/co.in/science/model/images/guide_light.png' width='18px' style='vertical-align: middle;'>閱讀提示：</h5>");
					$(".guide_hint_area").append(hint);
				});
			}
		});
	});
	$(".guide_hint").mouseover(function(){
		var hint = $(this).attr('value');
		$(".guide_hint_area").html("<h5><img src='/co.in/science/model/images/guide_light.png' width='18px' style='vertical-align: middle;'>閱讀提示：</h5>");
		$(".guide_hint_area").append(hint);

	});
/*-------------------------------小組協作空間：共用區-------------------------------*/
	$(".fancybox_rarrow").click(function(){
		var str = $(this).next().children().next().next().attr('value');
		var splitstr = str.split("|");
		var p_id = splitstr[0];												// 專題ID
		var stage = splitstr[1];											// 階段
		var order = tmp + 1;
		var number = $(this).next().children().next().next().attr('id');	// 最後一題題數
		console.log(p_id, stage, order, number);
		
		if(order > number){
			alert("【系統】超過研究構想表數囉！！")
		}else{
			// 2-2研究構想表
			$(".question_material").html("");
			$(".question_steps").html("");

			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data: {
					type : 'get_examine',
					p_id : p_id,
					stage : stage,
					order : order,
					action: 'examine_update'
				},
				error: function(){
					alert("【警告】讀取失敗！請檢查網絡連線問題！");
					return;
				},
				success: function(data){
					for(var a in data){
						// 研究構想表
						$(".question_name").html(data[a].question_name);
						$(".question_assume").html(data[a].question_assume);
						$(".question_independent").html(data[a].question_independent);
						$(".question_dependent").html(data[a].question_dependent);
						$(".question_record").html(data[a].record_name);
						// 材料與工具
						if(data[a].material_name != undefined){
							$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
								  "</div>").appendTo(".question_material");
						}
						// 研究步驟
						if(data[a].steps_name != undefined){
							var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

							$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td>"+ data[a].steps_name +"</td></tr></table>").appendTo(".question_steps");
						}
					}
					$("#examine_detail_fancybox"+ stage).show();
					tmp++;
				}
			});
		}
	});

	$(".fancybox_larrow").click(function(){
		var str = $(this).next().next().children().next().next().attr('value');
		var splitstr = str.split("|");
		var p_id = splitstr[0];													// 專題ID
		var stage = splitstr[1];												// 階段
		var order = tmp-1;
		var number = 0;
		console.log(p_id, stage, order, number);
		
		if(order == number){
			alert("【系統】少於研究構想表數囉！！");
		}else{
			// 2-2研究構想表
			$(".question_material").html("");
			$(".question_steps").html("");

			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data: {
					type : 'get_examine',
					p_id : p_id,
					stage : stage,
					order : order,
					action: 'examine_update'
				},
				error: function(){
					alert("【警告】讀取失敗！請檢查網絡連線問題！");
					return;
				},
				success: function(data){
					for(var a in data){
						// 研究構想表
						$(".question_name").html(data[a].question_name);
						$(".question_assume").html(data[a].question_assume);
						$(".question_independent").html(data[a].question_independent);
						$(".question_dependent").html(data[a].question_dependent);
						$(".question_record").html(data[a].record_name);
						// 材料與工具
						if(data[a].material_name != undefined){
							$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
								  "</div>").appendTo(".question_material");
						}
						// 研究步驟
						if(data[a].steps_name != undefined){
							var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

							$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td>"+ data[a].steps_name +"</td></tr></table>").appendTo(".question_steps");
						}
					}
					$("#examine_detail_fancybox"+ stage).show();
					tmp--;
				}
			});
		}
	});

	$(".fancybox_cancel").click(function(){
		$(".fancybox_box").hide();
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
			case '2':  // 教師首頁
				document.getElementById('video_frame').src='//www.youtube.com/embed/BVqo6iFK9tg';
				break;
			case '3':  // 教師手冊
				document.getElementById('video_frame').src='//www.youtube.com/embed/wQmVx7KLe1E';
				break;
			case '4':  // 專題管理
				document.getElementById('video_frame').src='//www.youtube.com/embed/SYPLvr36LDc';
				break;
			case '5':  // 教師日誌
				document.getElementById('video_frame').src='//www.youtube.com/embed/ndJGanSwFXE';
				break;
			case '6':  // 已結案專題
				document.getElementById('video_frame').src='//www.youtube.com/embed/56nEv789L_g';
				break;
			case '7':  // 觀摩專題作品
				document.getElementById('video_frame').src='//www.youtube.com/embed/xnQ67-JQXDY';
				break;
			case '8':  // 建議與回報
				document.getElementById('video_frame').src='//www.youtube.com/embed/E9zLalUabBU';
				break;
			case '9':  // 常用網站
				document.getElementById('video_frame').src='//www.youtube.com/embed/vdw7FX-SRUo';
				break;
		}
	});
/*-------------------------------------登出系統-------------------------------------*/
	$("#logout_btn").click(function(){
		var x = confirm("請問是否要登出？");
		if(x){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				data : {
					action : 'logout'
				},
				error : function(e, status){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("歡迎再次使用，再見.....T^T");
					window.location.href = /co.in/; //登出按鈕
				}
			});
		}
	});
/*----------------------------------紀錄使用者LOG----------------------------------*/
	$(".log_btn").click(function(){
		var log = $(this).attr('value');
		console.log(log);
		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
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