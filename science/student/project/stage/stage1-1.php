<style>
/*----------------------------------小組工作室：1-1----------------------------------*/
.theme_box h2{
	margin-left: 25px;
}
.theme_edit, .theme_discuss{
	float: left;
	margin-left: 18px;
	font-size: 12px;
	color: #808080;
	cursor: pointer;
}
.theme_new{
	float: left;
	width: 200px;
	min-height: 160px;
	margin: 5px 8px;
	padding: 0px 10px 10px 10px;
	line-height: 120px;								/*------------置中關鍵-----------*/
	text-align: center;
	border: 1px solid #000000;
	color: #1C90FD;
	cursor: pointer;
}
.topic_box h2{
	margin-left: 2px;
}
.topic_edit, .topic_discuss, .topic_related{
	float: left;
	margin-left: 18px;
	font-size: 12px;
	color: #808080;
	cursor: pointer;
}
.topic_new{
	float: left;
	width: 200px;
	height: 210px;
	min-height: 160px;
	margin: 5px 8px;
	padding: 0px 10px 10px 10px;
	text-align: center;
	border: 1px solid #000000;
	line-height: 180px;								/*------------置中關鍵-----------*/
	color: #1C90FD;
	cursor: pointer;
}
/*---------------------------------新增研究主題：1-1---------------------------------*/
#theme_new_area textarea{
	width: 68%;
	height: 120px;
	resize: none; 									/*------------不可拉縮------------*/
}
/*---------------------------------新增研究題目：1-1---------------------------------*/
#topic_new_area textarea{
	width: 70%;
	height: 120px;
	resize: none; 								  /*------------不可拉縮------------*/
}
.data_block{
	width: 89%;
	min-height: 100px;
	margin-left: 5px;
	border: 1px #AAAAAA solid;
}
.data_choosed{
	padding: 3px 5px 3px 5px;
	margin: 5px 5px 5px 5px;
	font-size: 14px;
	text-align: center;
	border: rgb(165,165,165) 1px solid;
	border-radius: 3px;
	display: inline-block;
}
.data_deleted{
	margin-left: 5px;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：1-1----------------------------------*/
	$(".theme_discuss").click(function(){
		// console.log($(this).attr('value'));						// 抓取標題
		var response = ($(this).attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("提出主題 - " + title);
		$("select[name=discuss_stage]").val("1-1");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
	$(".topic_discuss").click(function(){
		// console.log($(this).attr('value'));						// 抓取標題
		var response = ($(this).attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("提出題目 - " + title);
		$("select[name=discuss_stage]").val("1-1");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
	$(".topic_related").click(function(){
		window.location.href = "../project/database.php?stage=1-1";
	});
/*---------------------------------編輯研究主題：1-1---------------------------------*/
	$(".theme_new").click(function(){
		$("#theme_new_fancybox").show();
	});
	$("#theme_add").click(function(){
		if($("#theme_new_form input[name=theme_name]").val() == "" || $("#theme_new_form textarea").val() == ""){
			alert("【系統】尚未填寫完成。");
		}else{
			$("#theme_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_theme",
					action : "stage1-1_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】研究主題新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".theme_check").click(function(){
		var theme_id = $(this).parent().attr('id');
		
		if($("#check_theme" + theme_id).attr("src") == '../../model/images/project_uncheck.png'){
			var x = confirm("【系統】請問是否決定為研究主題？");
			if(x == true){
				$(".theme_check").attr("src","../../model/images/project_uncheck.png");
				$("#check_theme" + theme_id).attr("src","../../model/images/project_check.png");
				$.ajax({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_theme",
						theme_id : theme_id,
						action : "stage1-1_update"
					},
					error : function(e, status){
						alert("【系統】網路發生異常！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						$("#theme_save").attr("disabled", false);
						return;
					}
				});	
			}
		}else{
			var x = confirm("【系統】請問是否決定取消此研究主題？");
			if(x){
				$(".theme_check").attr("src","../../model/images/project_uncheck.png");
				$.ajax({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "uncheck_theme",
						theme_id : theme_id,
						action : "stage1-1_update"
					},
					error : function(e, status){
						alert("【系統】網路發生異常！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						$("#theme_save").attr("disabled", true);
						return;
					}
				});	
			}
		}
	});
	$("#theme_save").click(function(){
		$("#theme_check_fancybox").show();
	});
	$("#theme_check_submit").click(function(){
		var checknum = 8;				// 檢核表的題數
		var checklist = [];				// 檢核表的答案
		var check = "true";				// 是否全檢核過
		$("#theme_check_form input:radio:checked").each(function(){	// 將檢核表的答案存入checkList
			checklist.push($(this).val());
			// 判斷是否有否
			if($(this).val() != '1'){
				check = "false";
			}
		});
		if(checklist.length < checknum){
			alert("【系統】檢核表尚未填寫完成。");
		}else if(check == "false"){
			alert("【系統】請重新確認上傳主題是否合格。");
		}else{
			var x = confirm("【系統】確定送出主題後，就無法修改囉？");
			if(x){
				$("#theme_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_check_theme",
						check_num : checknum,
						action : "stage1-1_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data){
						alert("【系統】主題審核表送出成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	$(".theme_edit").click(function(){
		var theme_id = $(this).parent().attr('id');			// 抓取主題ID
		// console.log(theme_id);
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'get_theme',
				theme_id : theme_id,
				action: 'stage1-1_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
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
				}
				$("#theme_read_fancybox").show();
			}
		});
	});
/*---------------------------------編輯研究題目：1-1---------------------------------*/
	$(".topic_new").click(function(){
		$("#topic_new_fancybox").show();
	});
	// 增加相關資源
	$(".topic_data").change(function(){
		var data_id = $(this).val();
		// console.log(data_id);
		if(data_id == 'new'){
			window.location.href = "../project/database.php?stage=1-1";
			// 還原相關資源為預設值
			$(".topic_data").val('default');
		}
	});
	// 點擊[加入資源]，加入相關資源
	$(".data_add").click(function(){
		var add_id = $(".topic_data").val();
		var add_name = $(".topic_data").find("option[value='"+ add_id +"']").text();

		if(add_id != "" && add_id != "default" && add_id != "new"){
			$("<div class='data_choosed' value='"+ add_id +"'>"+ add_name +
				"<sapn class='data_deleted'>[刪除]</sapn>"+
				"</div>").appendTo(".data_block");
			$(".topic_data option[value='"+ add_id +"']").hide();
			$(".topic_data").val("default");
		}
		// 點擊資源裡的[刪除]，刪除相關資源
		$(".data_deleted").click(function(){
			var del_id = $(this).parent().attr("value");
			var del_name = $(this).parent().text();
			$(".topic_data option[value='"+ add_id +"']").show();
			$(this).parent().remove();
		});
	});
	$("#topic_add").click(function(){
		var data_id = [];				// 相關資料(至少三種)

		$("#topic_new_form .data_block .data_choosed").each(function(){	// 至少三種資源
			data_id.push($(this).attr("value"));
		});
		if($("#topic_new_form input[name=topic_name]").val() == "" || $("#topic_new_form textarea").val() == ""){
			alert("【系統】尚未填寫完成。");
		}else if(data_id.length < 3){
			alert("【系統】請至少列出三種資源。");
		}else{
			$("#topic_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_topic",
					data_id : data_id,
					action : "stage1-2_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】研究題目新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".topic_check").click(function(){
		var topic_id = $(this).parent().attr('id');
		// console.log($(this).parent().attr('id'));

		if($("#check_topic" + topic_id).attr("src") == '../../model/images/project_uncheck.png'){
			var x = confirm("【系統】請問是否決定為研究題目？");
			if(x == true){
				$(".topic_check").attr("src","../../model/images/project_uncheck.png");
				$("#check_topic" + topic_id).attr("src","../../model/images/project_check.png");
				$.ajax({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_topic",
						topic_id : topic_id,
						action : "stage1-2_update"
					},
					error : function(e, status){
						alert("【系統】網路發生異常！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						$("#topic_submit").attr("disabled", false);
						// alert("【系統】更改成功！");
						return;
					}
				});	
			}
		}else{
			var x = confirm("【系統】請問是否決定取消此研究題目？");
			if(x){
				$(".topic_check").attr("src","../../model/images/project_uncheck.png");
				$.ajax({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "uncheck_topic",
						topic_id : topic_id,
						action : "stage1-1_update"
					},
					error : function(e, status){
						alert("【系統】網路發生異常！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						$("#topic_submit").attr("disabled", true);
						return;
					}
				});	
			}
		}
	});
	$(".topic_edit").click(function(){
		var topic_id = $(this).parent().attr('id');			// 抓取題目ID

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'get_topic',
				topic_id : topic_id,
				action: 'stage1-2_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				for(var a in data){
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
				}
				$("#topic_read_fancybox").show();
			}
		});	
	});
/*----------------------------------繳交審核表：1-1----------------------------------*/
	$("#topic_submit").click(function(){
		$("#topic_check_fancybox").show();
	});
	$("#topic_check_submit").click(function(){
		var checknum = 7;				// 檢核表的題數
		var checklist = [];				// 檢核表的答案
		var check = "true";				// 是否全檢核過
		$("#topic_check_form input:radio:checked").each(function(){	// 將檢核表的答案存入checkList
			checklist.push($(this).val());
			// 判斷是否有否
			if($(this).val() != '1'){
				check = "false";
			}
		});
		if(checklist.length < checknum){
			alert("【系統】檢核表尚未填寫完成。");
		}else if(check == "false"){
			alert("【系統】請重新確認上傳主題是否合格。");
		}else{
			var x = confirm("【系統】確定送出題目後，就無法修改囉？");
			if(x){
				$("#topic_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_check_topic",
						check_num : checknum,
						action : "stage1-2_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data) {
						alert("【系統】題目審核表送出成功！");
						window.location.reload();
					}
				});
			}
		}
	});
});
</script>
<section>
	<?php
		// 判斷主題是否已確定--------------------------------------------------------------------------
		$active1 = 'active';				// 主題(初始)
		$active2 = '';						// 題目(初始)

		$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '1-1'";
		$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
		if(mysql_num_rows($l_qry) > 0){
			$active1 = '';					// 主題
			$active2 = 'active';			// 題目
		}
			mysql_query($l_sql, $link) or die(mysql_error());
		// 判斷主題與題目是否繳交給老師---------------------------------------------------------------
		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '1-2'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現
			$display2 = '';					// 消失
		}
			mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<div class="steps_menu">
			<ul>
				<li class='has-sub <?php echo $active1; ?>'><a href='#'>第一步：決定研究主題</a>
					<ul>
						<li class='sub-sub'><a href='#'>
							<?php
								if($_SESSION['chief'] == '1'){ // 組長
									$b_sql = "SELECT `research` FROM `research_theme` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
									$b_qry = mysql_query($b_sql, $link) or die(mysql_error());
									if(mysql_num_rows($b_qry) > 0){
										echo "<button class='steps_next' id='theme_save'>儲存，進入下一步</button>";
									}else{
										echo "<button class='steps_next' id='theme_save' disabled='true'>儲存，進入下一步</button>";
									}
									mysql_query($b_sql, $link) or die(mysql_error());
								}
							?>
							<h1 id="title">提出研究主題</h1>
							<p style="text-indent: 2em;">請在「新增研究主題」中，提出你有興趣的研究主題，並提供完整的資訊分享給同組的夥伴，<br />並在與小組討論完後，勾選出確定的研究主題，儲存並進行下一步驟。</p>
							<p class="steps_tips">[提醒：請組長按下 <img src="../../model/images/project_uncheck.png" width="20px" style="vertical-align: bottom;"> 確認主題，使其變為 <img src="../../model/images/project_check.png" width="20px" style="vertical-align: bottom;">，即可進入下一步。]</p>
							<?php
								$sql = "SELECT * FROM `research_theme` WHERE `p_id`= '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										if($_SESSION['chief'] == '1'){ // 組長
											if($row['research'] == '0'){
												$img_src = '../../model/images/project_uncheck.png';
											}else{
												$img_src = '../../model/images/project_check.png';
											}
											echo "<div class='steps_box theme_box' id='".$row['t_id']."'>
													<img src='".$img_src."' width='25px' class='steps_check theme_check' id='check_theme".$row['t_id']."'>
													<h2>".$row['theme']."</h2>
													<div class='theme_edit'><img src='../../model/images/project_edit.png' width='50px'><br />觀看研究主題</div>
													<div class='theme_discuss' value='".$row['theme']."|".$row['description']."|".$row['fileurl']."'><img src='../../model/images/project_discuss.png' width='50px'><br />討論研究主題</div>
												  </div>";
										}else{ // 組員
											echo "<div class='steps_box theme_box' id='".$row['t_id']."'>
													<h2 style='margin-left: -10px;'>".$row['theme']."</h2>
													<div class='theme_edit'><img src='../../model/images/project_edit.png' width='50px'><br />觀看研究主題</div>
													<div class='theme_discuss' value='".$row['theme']."|".$row['description']."|".$row['fileurl']."'><img src='../../model/images/project_discuss.png' width='50px'><br />討論研究主題</div>
												  </div>";
										}
									}
									echo "<div class='theme_new'>
											<h1><img src='../../model/images/project_add.png' width='28px'>新增研究主題</h1>
										  </div>";
								}else{
									echo "<div class='theme_new'>
											<h1><img src='../../model/images/project_add.png' width='28px'>新增研究主題</h1>
										  </div>";
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</a></li>
					</ul>
				</li>
				<li class='has-sub <?php echo $active2; ?>'><a href='#'>第二步：決定研究題目</a>
					<ul>
						<li class='sub-sub'><a href='#'>
							<?php
								if($_SESSION['chief'] == '1'){ // 組長
									$b_sql = "SELECT `research` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
									$b_qry = mysql_query($b_sql, $link) or die(mysql_error());
									if(mysql_num_rows($b_qry) > 0){
										echo "<button class='steps_submit' id='topic_submit'>繳交本階段作業</button>";
									}else{
										echo "<button class='steps_submit' id='topic_submit' disabled='true'>繳交本階段作業</button>";
									}
									mysql_query($b_sql, $link) or die(mysql_error());
								}
							?>
							<h1 id="title">提出研究題目</h1>

							<p style="text-indent: 2em;">請在「新增研究題目」中，根據剛剛所想的研究主題，蒐集相關資料，並與組員討論出合適的研究題目，勾選完儲存後送出給老師。</p>
							<p class="steps_tips">[提醒：請組長按下 <img src="../../model/images/project_uncheck.png" width="20px" style="vertical-align: bottom;"> 確認主題，使其變為 <img src="../../model/images/project_check.png" width="20px" style="vertical-align: bottom;">，即可進入完成。]</p>
							<?php
								$t_sql = "SELECT `theme` FROM `research_theme` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
								$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
								while($t_row = mysql_fetch_array($t_qry)){
									echo "<h3>研究主題：".$t_row['theme']."</h3>";
								}
								mysql_query($t_sql, $link) or die(mysql_error());
							?>
							<?php
								$sql = "SELECT * FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										if($_SESSION['chief'] == '1'){	// 組長
											if($row['research'] == '0'){
												$img_src = '../../model/images/project_uncheck.png';
											}else{
												$img_src = '../../model/images/project_check.png';
											}
											echo "<div class='steps_box topic_box' id='".$row['t_id']."'>
													<img src='".$img_src."' width='25px' class='steps_check topic_check' id='check_topic".$row['t_id']."'>
													<h2>".$row['topic']."</h2>
													<div class='topic_edit'><img src='../../model/images/project_edit.png' width='50px'><br />觀看研究題目</div>
													<div class='topic_discuss' value='".$row['topic']."|".$row['description']."'><img src='../../model/images/project_discuss.png' width='50px'><br />討論研究題目</div>
													<div class='topic_related'><img src='../../model/images/project_related.png' width='50px'><br />蒐集相關資料</div>
												  </div>";
										}else{	// 組員
											echo "<div class='steps_box topic_box' id='".$row['t_id']."'>
													<h2 style='margin-left: -10px;'>".$row['topic']."</h2>
													<div class='topic_edit'><img src='../../model/images/project_edit.png' width='50px'><br />觀看研究題目</div>
													<div class='topic_discuss' value='".$row['topic']."|".$row['description']."'><img src='../../model/images/project_discuss.png' width='50px'><br />討論研究題目</div>
													<div class='topic_related'><img src='../../model/images/project_related.png' width='50px'><br />蒐集相關資料</div>
												  </div>";

										}
									}
									echo "<div class='topic_new'>
											<h1><img src='../../model/images/project_add.png' width='28px'>新增研究題目</h1>
										  </div>";
								}else{
									echo "<div class='topic_new'>
											<h1><img src='../../model/images/project_add.png' width='28px'>新增研究題目</h1>
										  </div>";
								}
							?>
						</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="fancybox_box" id="theme_new_fancybox">
			<div class="fancybox_area" id="theme_new_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 新增研究主題 -</h2>
				<form id="theme_new_form" method="post">
					<p>
						<label>提議主題：</label>
						<input type="text" name="theme_name">
					</p>
					<p>
						<label>主題來源：</label>
						<select name="theme_src">
							<option value="1">生活中</option>
							<option value="2">課本中</option>
							<option value="3">參考別人題目</option>
						</select>
					</p>
					<p>
						<label>附加檔案：</label>
						<input type="file" name="files">
					</p>
					<p>
						<label>提議的原因：</label>
						<textarea name="theme_reason"></textarea>
					</p>
					<input type="button" class="fancybox_btn" id="theme_add" value="新增主題">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="topic_new_fancybox">
			<div class="fancybox_area" id="topic_new_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 新增研究題目 -</h2>
				<form id="topic_new_form" method="post">
					<p>
						<label>提議題目：</label>
						<input type="text" name="topic_name">
					</p>
					<p>
						<label>提議原因：</label>
						<textarea name="topic_reason"></textarea>
					</p>
					<p>
						<label>列出相關資料：</label>
						<select class="topic_data">
							<option value="default">請選擇相關資源</option>
							<option value="new" style="background-color: #eee;">+ 新增相關資源</option>
							<?php
								$sql = "SELECT `d_id`, `title` FROM `database` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['d_id']."'>".$row['title']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</select>
						<input type="button" class="data_add" value="加入資源">
						<div class="data_block"></div>
					</p>
					<p>
						<label>附加檔案：</label>
						<input type="file" name="files">
					</p>
					<input type="button" class="fancybox_btn" id="topic_add" value="新增題目">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="theme_read_fancybox">
			<div class="fancybox_area" id="theme_read_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究主題 -</h2>
				<form id="theme_read_form" method="post">
					<p>
						<label>提議主題：</label><span class="theme_read_name"></span>
					</p>
					<p>
						<label>提議人：</label><span class="theme_read_user"></span>
					</p>
					<p>
						<label>主題來源：</label><span class="theme_read_src"></span>
					</p>
					<p>
						<label>提議的原因：</label><span class="theme_read_description"></span>
					</p>
					<p>
						<label>附加檔案：</label><span class="theme_read_filename"></span>
					</p>
					<p>
						<label>提議時間：</label><span class="theme_read_time"></span>
					</p>
					<!-- <input type="button" class="fancybox_btn" id="theme_delete" value="刪除"> -->
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="topic_read_fancybox">
			<div class="fancybox_area" id="topic_read_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究題目 -</h2>
				<form id="topic_read_form" method="post">
					<p>
						<label>提議題目：</label><span class="topic_read_name"></span>
					</p>
					<p>
						<label>提議人：</label><span class="topic_read_user"></span>
					</p>
					<p>
						<label>相關資源：</label><span class="topic_read_data"></span>
					</p>
					<p>
						<label>提議的原因：</label><span class="topic_read_description"></span>
					</p>
					<p>
						<label>附加檔案：</label><span class="topic_read_filename"></span>
					</p>
					<p>
						<label>提議時間：</label><span class="topic_read_time"></span>
					</p>
					<!-- <input type="button" class="fancybox_btn" id="topic_delete" value="刪除"> -->
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="theme_check_fancybox">
			<div class="fancybox_area" id="theme_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 1-1《決定主題》檢核表 -</h2>
				<form id="theme_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 這個主題跟自然科學或數學有關？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們至少可以找到三個跟這個主題有關的資料？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 我們能輕易的獲得研究材料及設備嗎？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 進行相關的研究不會有安全的問題？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 這個主題的相關研究不會傷害小動物？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 可以找到請教的人？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 研究主題足夠有趣嗎？能不能在幾個月內都一直覺得有趣？</td>
							<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
						</tr>
						<tr>
							<td>8. 我們可以在限定的時間內完成這個主題的相關研究？</td>
							<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
						</tr>
					</table>
					<div class="fancybox_mark">小提醒：送出後便不能再修改和討論了。</div>
					<input type="button" class="fancybox_btn" id="theme_check_submit" value="送出">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="topic_check_fancybox">
			<div class="fancybox_area" id="topic_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 1-2《決定題目》檢核表 -</h2>
				<form id="topic_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 在全國科展近五年的得獎作品中沒有完全一樣的題目？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們有沒有避免在階段指引中所列出的不好的科學題目呢？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 這個題目對我們來說，不會太難？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 我們可以取得或自製相關的研究器材？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 研究材料容易取得？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 要做的實驗是安全的？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 我們可以找到測量或紀錄的方法？</td>
							<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
						</tr>
					</table>
					<div class="fancybox_mark">小提醒：送出後便不能再修改和討論了。</div>
					<input type="button" class="fancybox_btn" id="topic_check_submit" value="送出">
				</form>
			</div>
		</div>
	</div>
	<div class="<?php echo $display2; ?>">
		<h1>完成的學習任務，已經提交給老師！等待老師審核。</h1>
		<p style="text-indent: 2em;">在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button> 撰寫【個人日誌】和【反思日誌】來回想自己在此階段學習到了什麼。^_^</p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>	
</section>