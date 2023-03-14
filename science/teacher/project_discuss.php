<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 參與小組討論';
	include("api/php/header.php");
?>
<style>
/*--------------------------------專題管理：參與討論--------------------------------*/
.discuss_select{
	margin-left: 155px;
	font-weight: bolder;
	cursor: pointer;
}
.discuss_select img{
	margin-left: 25px;
	vertical-align: middle;						/*-----------圖文對齊-----------*/
}
.discuss_stage{
	margin-left: -38px;
	list-style-type: none;
}
.discuss_stage li{
	float: left;
	padding: 10px 18px;
	margin: 0px -1px 15px 0px;
	background-color: #eee;
	border: 1px solid #000000;
	cursor: pointer;
}
.discuss_stage li:hover{
	background-color: #B8BBC0;
}
.discuss_pages{
	width: 70%;
	height: 480px;
	padding: 15px;
	margin: 0 auto;
	border: 1px solid #000000;
	overflow-y: scroll;
	display: none;
}
.discuss_box_new{
	height: 8%;
	padding-top: 5px;
	font-size: 26px;
	font-weight: bolder;
	color: #1C90FD;
	text-align: center;
	border: 1px solid #000000;
	cursor: pointer;
}
.discuss_box{
	height: 120px;
	padding-left: 5px;
	margin-top: 10px;
	border: 1px solid #000000;
}
.discuss_type{
	background-color: #E3687D;
}
.discuss_indent{
	text-indent: 30px;
}
.discuss_bookmark{
	clear: both;
	float: right;
	cursor: pointer;
/*	background-color: red;*/
}
.discuss_btn{
	margin-top: -20px;
	float: right;
	cursor: pointer;
	/*background-color: red;*/
}
.discuss_number{
	position: relative;
	top: -8px;
	left: -5px;
	font-size: 12px;
	font-weight: bolder;
	color: #205080;
}
#discuss_new_area textarea{
	width: 70%;
	height: 100px;
	resize: none; 								/*------------不可拉縮------------*/
}
#discuss_filename{
	margin-top: 8px;
}
#discuss_reply{
	line-height: 10px;
	text-align: center;
}
</style>
<script>
$(function(){
/*-------------------------------小組協作空間：討論區-------------------------------*/
	$("#page_stage1").show();
	$("#stage1").addClass('show_stage').removeClass('stage');
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
		if($("input[name=discuss_title]").val() == ''){
			alert("【系統】討論主題尚未填寫。");
		}else if($("select[name=discuss_stage]").val() == 'default'){
			alert("【系統】請選擇討論階段。");
		}else{
			$("#discuss_new_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
					action : "discuss_update"
				},
				error : function(){
					alert("【系統】新增失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】討論串新增成功！");
					window.location.reload();
				}
			});
		}
	});
	// 對討論串按讚
	$(".discuss_good").click(function(){
		var discuss_id = $(this).parent().parent().parent().attr('id');		// 討論ID

		if($(this).attr("value") == '0'){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "good_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#good" + data[a].discuss_id).attr("src","../model/images/discuss_good.png");
						$("#number" + data[a].discuss_id).text(data[a].discuss_number);
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "ungood_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#good" + data[a].discuss_id).attr("src","../model/images/discuss_ungood.png");
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
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "star_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#star" + data[a].discuss_id).attr("src","../model/images/discuss_star.png");
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "unstar_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#star" + data[a].discuss_id).attr("src","../model/images/discuss_unstar.png");
						return;
					}
				}
			});
		}
	});
	// 對討論串做書籤
	$(".discuss_bookmark").click(function(){
		var discuss_id = $(this).parent().attr('id');		// 討論ID
		// console.log(discuss_id);
		if($(this).attr("value") == '0'){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "bookmark_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#bookmark" + data[a].discuss_id).attr("src","../model/images/discuss_bookmark.png");
						return;
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "unbookmark_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
						$("#bookmark" + data[a].discuss_id).attr("src","../model/images/discuss_unbookmark.png");
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
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type : 'get_discuss',
				p_id : <?php echo $_GET['p_id']; ?>,
				discuss_id : discuss_id,
				action : 'discuss_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
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
						$("#discuss_filename").html("<a href="+ data[a].discuss_fileurl +" download>"+ data[a].discuss_filename +"</a>");
					}else{
						$("#discuss_filename").html("無檔案");
					}
					
					if(data[a].discuss_r_content != undefined){
						$("#discuss_reply").hide();
						$("<p>"+ data[a].discuss_r_uid +"："+ data[a].discuss_r_content +"</p>").appendTo("#discuss_reply_content");
					}
					if(data[a].discuss_r_filename != undefined){
						$("<a href='"+ data[a].discuss_r_fileurl +"' download>"+ data[a].discuss_r_filename +"</a>").appendTo("#discuss_reply_content");
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
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "reply_discuss",
					p_id : <?php echo $_GET['p_id']; ?>,
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
							$("<a href='"+ data[a].discuss_r_fileurl +"' download>"+ data[a].discuss_r_filename +"</a>").appendTo("#discuss_reply_content");
						}
					}
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<fieldset>
		<legend>小組討論區</legend>
		<div id="page_discuss" class="function_pages">
			<div class="discuss_select">
				過濾器：<select>
							<option value="all">全部討論串</option>
							<option value="star">打星號</option>
							<option value="new">最新討論</option>
						</select>
				<span><img src='../model/images/discuss_down.png' width='40px'>最新向上</span>
				<span><img src='../model/images/discuss_hot.png' width='40px'>最熱門</span>
				<span><img src='../model/images/discuss_setting.png' width='40px'>追蹤討論串設定</span>
				<ul class="discuss_stage">
					<li id="stage1">第一階段：提問</li>
					<li id="stage2">第二階段：規劃</li>
					<li id="stage3">第三階段：執行</li>
					<li id="stage4">第四階段：形成結論</li>
					<li id="stage5">第五階段：報告與展示</li>
				</ul>
			</div>
			<div id="page_stage1" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串">+ 新增討論串</div>
				<div class="discuss_area1"></div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_GET['p_id']."' AND (`stage`= '1-1' OR `stage`= '1-2') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
								// 按讚
								if($good == '0'){
									$good_img = 'ungood';
								}else{
									$good_img = 'good';
								}
								// 星號
								if($star == '0'){
									$star_img = 'unstar';
								}else{
									$star_img = 'star';
								}
								// 追蹤
								if($bookmark == '0'){
									$bookmark_img = 'unbookmark';
								}else{
									$bookmark_img = 'bookmark';
								}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							echo "<div class='discuss_box' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage2" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串">+ 新增討論串</div>
				<div class="discuss_area2"></div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_GET['p_id']."' AND (`stage`= '2-1' OR `stage`= '2-2' OR `stage`= '2-3' OR `stage`= '2-4') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
								// 按讚
								if($good == '0'){
									$good_img = 'ungood';
								}else{
									$good_img = 'good';
								}
								// 星號
								if($star == '0'){
									$star_img = 'unstar';
								}else{
									$star_img = 'star';
								}
								// 追蹤
								if($bookmark == '0'){
									$bookmark_img = 'unbookmark';
								}else{
									$bookmark_img = 'bookmark';
								}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							echo "<div class='discuss_box' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage3" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串">+ 新增討論串</div>
				<div class="discuss_area3"></div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_GET['p_id']."' AND (`stage`= '3-1' OR `stage`= '3-2' OR `stage`= '3-3') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
								// 按讚
								if($good == '0'){
									$good_img = 'ungood';
								}else{
									$good_img = 'good';
								}
								// 星號
								if($star == '0'){
									$star_img = 'unstar';
								}else{
									$star_img = 'star';
								}
								// 追蹤
								if($bookmark == '0'){
									$bookmark_img = 'unbookmark';
								}else{
									$bookmark_img = 'bookmark';
								}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							echo "<div class='discuss_box' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage4" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串">+ 新增討論串</div>
				<div class="discuss_area4"></div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_GET['p_id']."' AND (`stage`= '4-1' OR `stage`= '4-2') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
								// 按讚
								if($good == '0'){
									$good_img = 'ungood';
								}else{
									$good_img = 'good';
								}
								// 星號
								if($star == '0'){
									$star_img = 'unstar';
								}else{
									$star_img = 'star';
								}
								// 追蹤
								if($bookmark == '0'){
									$bookmark_img = 'unbookmark';
								}else{
									$bookmark_img = 'bookmark';
								}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							echo "<div class='discuss_box' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage5" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串">+ 新增討論串</div>
				<div class="discuss_area5"></div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_GET['p_id']."' AND (`stage`= '5-1' OR `stage`= '5-2' OR `stage`= '5-3' OR `stage`= '5-4') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
								// 按讚
								if($good == '0'){
									$good_img = 'ungood';
								}else{
									$good_img = 'good';
								}
								// 星號
								if($star == '0'){
									$star_img = 'unstar';
								}else{
									$star_img = 'star';
								}
								// 追蹤
								if($bookmark == '0'){
									$bookmark_img = 'unbookmark';
								}else{
									$bookmark_img = 'bookmark';
								}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							echo "<div class='discuss_box' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
		</div>
	</fieldset>
	<div class="fancybox_box" id="discuss_new_fancybox">
		<div class="fancybox_area" id="discuss_new_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新增討論串 -</h2>
			<form id="discuss_new_form" method="post">
				<p>
					<label>討論主題：</label>
					<input type="text" name="discuss_title">
				</p>
				<p>
					<label>討論階段：</label>
					<select name="discuss_stage">
						<option value="default">請選擇討論階段...</option>
						<?php
							$sql = "SELECT `stage`, `name` FROM `stage`";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['stage']."'>".$row['stage']." ".$row['name']."</option>";
								}
							}
							mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<p>
					<label>討論種類：</label>
					<select name="discuss_type">
						<option value="0">一般討論</option>
						<option value="1">主題討論</option>
					</select>
				</p>
				<p>
					<label>討論說明：</label>
					<textarea name="discuss_description"></textarea>
				</p>
				<p>
					<label>附加檔案：</label>
					<input type="file" name="files">
				</p>
				<input type="button" class="fancybox_btn" id="discuss_add" value="新增討論">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="discuss_read_fancybox">
		<div class="fancybox_area" id="discuss_read_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 主題 -</h2>
			<p>發言人：<span id="discuss_user"></span></p>
			<p>標題：<span id="discuss_title"></span></p>
			<p>內容：<span id="discuss_description"></span></p>
			<p>附件：<span id="discuss_filename"></span></p>
		<hr />
			<p id="discuss_reply">目前無任何回應</p>
			<p><span id="discuss_reply_content"></span></p>
		<hr />
			<form id="discuss_read_form">
				<p style="display: none;">討論ID：<input type="text" name="discuss_id" /></p>
				<p><select name="discuss_read_type">
					<option value="1">提出個人意見</option>
					<option value="2">提出不同的意見</option>
					<option value="3">提出理由</option>
					<option value="4">提供佐證資料</option>
					<option value="5">舉例</option>
					<option value="6">做總結</option>
				</select>
				<input type="text" name="discuss_read_content" size="35" />
				<input type="button" class="fancybox_btn" id="discuss_read_add" value="回覆" /></p>
				<p id="discuss_filename">附加檔案：<input type="file" name="files" /></p>
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>