<?php
	$page_url = '<a href="index.php">專題指導</a> > 專題管理';
	include("api/php/header.php");
?>
<style>
/*---------------------------------專題管理：進行中---------------------------------*/
.project_btn{
	width: 100%;
	text-align: right;
}
.project_box{
	float: left;
	margin: 10px 15px;
}
.project_info{
	width: 240px;
	min-height: 250px;
	padding: 5px 10px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
	overflow: auto;
	cursor: pointer;
}
.project_content{
	position: relative; 						/*----------為使用z-index-----------*/
	margin-top: -20px;
/*	background-color: red;*/
	z-index: 1;
}
.project_content h1{
	font-size: 180%;
}
.project_content h4{
	line-height: 8px;
}
.project_group{
	position: relative;							/*----------為使用z-index-----------*/
	padding-top: 20px;
	margin-top: -20px;
	background-color: #F1FAFA;
/*	background-color: blue;*/
	display: none;
	z-index: 1;
}
.project_function{
	position: relative;
	top: -1px;
	left: 6px;
	width: 228px;
	height: 160px;
	padding: 10px 10px 0px 10px;
	line-height: 30px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
	display: none;
}
.project_larrow{
	position: relative;
	top: 90px;
	left: -110px;
	cursor: pointer;
	z-index: 5;
}
.project_rarrow{
	position: relative;
	top: 90px;
	left: 110px;
	cursor: pointer;
	z-index: 5;
}
.project_time{
	margin: 2px 0px 4px 5px;
	float: right;
}
.project_scroll{
	float: right;
	display: none;
}
.project_rate{
	float: left;
	margin: 10px 16px;
	font-size: 10px;
	line-height: 8px;
}
.project_point{
	width: 20px;
	height: 20px;
	margin-left: 10px;
	font-size: 12px;
	color: #000000;
	text-align: center;
	border-radius: 10px;
	cursor: default;
}
.project_mascot{
	float: right;
	margin-top: -30px;
}
.project_remind{
	padding-top: 220px;
	font-size: 220%;
	font-weight: bolder;
	text-align: center;
}
.project_notes{
	text-decoration: underline;
	color: blue;
	cursor: pointer;
}
/*---------------------------------專題管理：申請中---------------------------------*/
.apply_info{
	width: 240px;
	min-height: 200px;
	padding: 20px 10px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
}
.apply_info h3{
	font-size: 130%;
	line-height: 8px;
	color: red;
}
.apply_info div{
	line-height: 30px;
}
.apply_correct, .apply_canceal{
	text-decoration: underline;
	color: blue;
	cursor: pointer;
}
/*----------------------------------專題管理：設定----------------------------------*/
#project_set_area{
	width: 55%;
}
.project_new_box, .project_group_box{
	float: left;
	width: 38%;
	height: 200px;
	padding: 20px 15px;
	margin: 20px 25px;
	color: #000000;
	border: 1px solid #000000;
}
.project_new_box:hover, .project_group_box:hover{
	background-color: #eee;
}
/*----------------------------------專題管理：NOTE----------------------------------*/
#project_note_area{
	width: 30%;
	min-height: 340px;
	background-color: #F7EB87;
	border: 0px;
	border-radius: 0px;
}
#project_note_form textarea{
	width: 100%;
	height: 250px;
	background-color: #F7EB87;
	resize: none;
}
</style>
<script>
$(function(){
/*---------------------------------專題管理：進行中---------------------------------*/
	$(".project_content").click(function(){
		$("#function" + $(this).attr('value')).toggle();
	});
	$(".project_larrow, .project_rarrow").click(function(){
		$("#group" + this.id).toggle();
		$("#content" + this.id).toggle();
	});
/*---------------------------------專題管理：申請中---------------------------------*/
	$(".apply_correct").click(function(){
		var project_id = $(this).attr('id');

		var x = confirm("【系統】確定接受此小組申請？");
		if(x){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "apply_project",
					project_id : project_id,
					action : "project_update"
				},
				error : function(e, status){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("【系統】已接受此小組申請。");
					window.location.reload();
				}
			});
		}
	});
	$(".apply_canceal").click(function(){
		var project_id = $(this).attr('id');

		var x = confirm("【系統】確定拒絕此小組申請？");
		if(x){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "reject_project",
					project_id : project_id,
					action : "project_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("【系統】已拒絕此小組申請。");
					window.location.reload();
				}
			});
		}
	});
/*--------------------------------專題管理：時間倒數--------------------------------*/
	$(".project_time").mouseover(function(){
		$("#scroll" + this.id).show();
	});
	$(".project_time").mouseleave(function(){
		$("#scroll" + this.id).hide();
	});
/*----------------------------------專題管理：設定----------------------------------*/
	$(".project_set").click(function(){
		$("#project_set_fancybox").show();
	});
/*----------------------------------專題管理：NOTE----------------------------------*/
	// My Notes讀取
	$(".project_notes").click(function(){
		var project_id = $(this).attr('id');
		// console.log(project_id);
		$("textarea[name=project_note_content]").html("");

		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_notes",
				project_id : project_id,
				action : "notes_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					$("input[name=project_id]").val(data[a].notes_pid);
					$("textarea[name=project_note_content]").html(data[a].notes_content);
				}
				$("#project_note_fancybox").show();
			}
		});	
	});
	// My Notes儲存
	$("textarea[name=project_note_content]").blur(function(){
		$("#project_note_form").ajaxSubmit({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "write_notes",
				action : "notes_update"
			},
			error : function(){
				alert("【系統】送出失敗！！請再重試一次！！");
				return;
			},
			success : function (data) {
				return;
			}
		});
	});
	$(".fancybox_cancel").click(function(){
		$("textarea[name=project_note_content]").html("");
	});
});
</script>
<div id="centercolumn">
	<div class="project_btn">
		<button class="project_set log_btn" value="新專案小組設定"><img src="../model/images/project_set.png" width="22px" style="vertical-align: middle;">新專案小組設定</button>
	</div>
	<?php
		$sql = "SELECT * FROM `project` WHERE (`t_m_id` = '".$_SESSION['UID']."' || `t_s_id` = '".$_SESSION['UID']."')&& `finish` = '1' ORDER BY `project_time` ASC";
		$qry = mysql_query($sql, $link) or die(mysql_error());
		if(mysql_num_rows($qry) > 0){		// 確定有小組
			while($row = mysql_fetch_array($qry)){
				// 倒數時間
				$exp_time = "(尚未設定)";
				$stage_arr = array("1-1", "1-2", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4");
				for($i = 0; $i < count($stage_arr); $i++){
					if($row['stage'] == $stage_arr[$i]){
						$t_sql = "SELECT * FROM `project_schedule` WHERE `p_id`= '".$row['p_id']."'";
						$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
						while($t_row = mysql_fetch_array($t_qry)){
							if($t_row['exp'.$stage_arr[$i]] != "0000-00-00"){
								if($i == '0'){
									$seconds = (strtotime($t_row['exp'.$stage_arr[$i+1]])-strtotime('today GMT'));
								}else{
									$seconds = (strtotime($t_row['exp'.$stage_arr[$i]])-strtotime('today GMT'));
								}
										
								$exp_time = floor($seconds/(3600*24))."天";					// 天
								$exp_time .= floor(($seconds%(3600*24))/3600)."時";			// 時
								$exp_time .= floor((($seconds%(3600*24))%3600)/60)."分";	// 分
								$exp_time .= floor((($seconds%(3600*24))%3600)%60)."秒";	// 秒
							}
						}
					}
				}
				// 抓取階段名稱
				$s_sql = "SELECT `name` FROM `stage` WHERE `stage` = '".$row['stage']."' limit 0, 1";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				if(mysql_num_rows($s_qry) > 0){
					while($s_row = mysql_fetch_array($s_qry)){
						$stage = $row['stage'];
						$stage_name = $s_row['name'];
					}
				}else{
					$stage = '';
					$stage_name = '已完成所有專題任務';
				}
				// 抓取主題
				$theme_name = '(暫無)';
				$a_sql = "SELECT `theme` FROM `research_theme`  WHERE `p_id` = '".$row['p_id']."' AND `research` = '1'";
				$a_qry = mysql_query($a_sql, $link) or die(mysql_error());
				while($a_row = mysql_fetch_array($a_qry)){
					$theme_name = $a_row['theme'];
				}
				// 抓取題目
				$topic_name = '(尚未決定)';
				$b_sql = "SELECT `topic` FROM `research_topic`  WHERE `p_id` = '".$row['p_id']."' AND `research` = '1'";
				$b_qry = mysql_query($b_sql, $link) or die(mysql_error());
				while($b_row = mysql_fetch_array($b_qry)){
					$topic_name = $b_row['topic'];
				}
				// 抓取小組表現
				$p_sql = "SELECT * FROM `project_perform` WHERE `p_id` = '".$row['p_id']."' limit 0, 1";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				while($p_row = mysql_fetch_array($p_qry)){
					$times_login = floor((strtotime('today GMT')-strtotime($p_row['times_login']))/(3600*24));
					$times_discuss = floor((strtotime('today GMT')-strtotime($p_row['times_discuss']))/(3600*24));
					$times_examine = floor((strtotime('today GMT')-strtotime($p_row['times_examine']))/(3600*24));
				}
				// 抓取小組成員
				$group_chief = "";
				$group_member = "";

				$g_sql = "SELECT `s_id`, `chief` FROM `project_group` WHERE `p_id` = '".$row['p_id']."'";
				$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
				while($g_row = mysql_fetch_array($g_qry)){
					$n_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$g_row['s_id']."'";
					$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
					$n_row = mysql_fetch_array($n_qry);
					if($g_row['chief'] == '1'){
						$group_chief = $n_row['name'];
					}else{
						$group_member .= $n_row['name']."<br />";
					}
				}
				if($group_member == ""){
					$group_member = "無";
				}
				if($row['state'] == '0'){	
					echo "<div class='project_box'>".
							"<div class='project_info'>".
								"<img src='../model/images/project_larrow.png' width='15px' class='project_larrow' id='".$row['p_id']."'>".
								"<img src='../model/images/project_rarrow.png' width='15px' class='project_rarrow' id='".$row['p_id']."'>".
								"<div class='project_content' id='content".$row['p_id']."' value='".$row['p_id']."'>".
									"<img src='../model/images/project_time.png' width='12px' class='project_time' id='".$row['p_id']."'><div class='project_scroll' id='scroll".$row['p_id']."'>倒數時間：".$exp_time."</div>".
									"<h1 style='clear: both;'>主題：".$theme_name."</h1>".
									"<h4>研究題目：".$topic_name."</h4>".
									"<div>小組名稱：".$row['pname']."</div>".
									"<div>進度：".$stage." ".$stage_name."</div>";
							if($times_login <= 5){
								echo "<div class='project_rate' style='color: #71C235;' title='小組都有乖乖上線！'><div class='project_point' style='border: 5px solid #71C235;'></div><br/>登入次數</div>";
							}else if($times_login > 8){
								echo "<div class='project_rate' style='color: #F30E0E;'  title='小組都不知道跑去哪了？'><div class='project_point' style='border: 5px solid #F30E0E;'></div><br/>登入次數</div>";
							}else{
								echo "<div class='project_rate' style='color: #FF7E00;' title='小組可能需要關心一下囉！'><div class='project_point' style='border: 5px solid #FF7E00;'></div><br/>登入次數</div>";
							}
							if($times_discuss <= 5){
								echo "<div class='project_rate' style='color: #71C235;' title='小組討論次數頻繁。'><div class='project_point' style='border: 5px solid #71C235;'></div><br/>討論頻率</div>";
							}else if($times_discuss > 8){
								echo "<div class='project_rate' style='color: #F30E0E;' title='小組...是沒人要講話了？'><div class='project_point' style='border: 5px solid #F30E0E;'></div><br/>討論頻率</div>";
							}else{
								echo "<div class='project_rate' style='color: #FF7E00;' title='小組可能需要老師幫忙協調討論？'><div class='project_point' style='border: 5px solid #FF7E00;'></div><br/>討論頻率</div>";
							}
							if($times_examine <= 5){
								echo "<div class='project_rate' style='color: #71C235;' title='小組都有乖乖繳交作業！'><div class='project_point' style='border: 5px solid #71C235;'></div><br/>專題進度</div>";
							}else if($times_examine > 8){
								echo "<div class='project_rate' style='color: #F30E0E;' title='小組作業好像拿去墊便當了？'><div class='project_point' style='border: 5px solid #F30E0E;'></div><br/>專題進度</div>";
							}else{
								echo "<div class='project_rate' style='color: #FF7E00;' title='小組作業需要督促一下囉！'><div class='project_point' style='border: 5px solid #FF7E00;'></div><br/>專題進度</div>";
							}
						echo "</div>".
								"<div class='project_group' id='group".$row['p_id']."'>".
									"<div><b>- 組長 -</b><br />".$group_chief."</div><br />".
									"<div><b>- 組員 -</b><br />".$group_member."</div>".
									"<div class='project_mascot'><img src='".$row['mascot']."' width='70%'></div>".
								"</div>".
							"</div>".
							"<div class='project_function' id='function".$row['p_id']."'>".
								"<div class='log_btn' value='參與小組討論'><a href='project_discuss.php?p_id=".$row['p_id']."'>參與小組討論</a></div>".
								"<div class='log_btn' value='小組進度管理'><a href='project_schedule.php?p_id=".$row['p_id']."''>小組進度管理</a></div>".
								"<div class='log_btn' value='小組任務編輯'><a href='project_task.php?p_id=".$row['p_id']."''>小組任務編輯</a></div>".
								"<div class='log_btn' value='學習評量與結案'><a href='project_assess.php?p_id=".$row['p_id']."''>學習評量與結案</a></div>".
								"<div class='project_notes log_btn' id='".$row['p_id']."' value='My Notes'>My Notes</div>".
							"</div>".
						"</div>";
				}else if($row['state'] == '2'){
					echo "<div class='project_box'>".
							"<div class='apply_info'>".
								"<h3>老師，請指導我們！！</h3>".
								"<div>小組名稱：".$row['pname']."</div>".
								"<div>組長：".$group_chief."</div>".
								"<div>組員：".$group_member."</div>".
								"<div class='log_btn' value='接受專題申請'><span class='apply_correct' id='".$row['p_id']."'>接受申請，開始專題指導設定</span></div>".
								"<div class='log_btn' value='拒絕專題申請'><span class='apply_canceal' id='".$row['p_id']."'>拒絕申請</span></div>".
							"</div>".
						 "</div>";
				}
			}
		}else{
			echo "<div class='project_remind'>(尚未有小組申請。)</div>";
		}
	?>
	<div class="fancybox_box" id="project_set_fancybox">
		<div class="fancybox_area" id="project_set_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新專案小組設定 -</h2>
			<p style="text-indent: 30px;">在此可以先選擇「匯入學生名單」將學生批次匯入進系統中，或選擇「創立新專題小組」讓學生可以更快進入專題實作。</p>
			<a href="project_new.php"><div class="project_new_box log_btn" value="匯入學生名單">
				<h4 align="center">匯入學生名單</h4>
				將學生名單批次匯入系統當中，快速幫學生建立帳號。
			</div></a>
			<a href="project_group.php"><div class="project_group_box log_btn" value="創立新專題小組">
				<h4 align="center">創立新專題小組</h4>
				將已匯入的學生名單，創立新專題小組，讓學生可以更快的進入專題實作。。
			</div></a>
		</div>
	</div>
	<div class="fancybox_box" id="project_note_fancybox">
		<div class="fancybox_area" id="project_note_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 15px 0px 10px 0px;">My Notes<img src="../model/images/project_info.png" width="25px" title="My Notes：可以記錄重要事情並備註給小組。" style="vertical-align: middle;"></h2>
		<hr />
			<form id="project_note_form">
				<input type="text" name="project_id" style="display: none;">
				<textarea name='project_note_content'></textarea>
			</form>
			<span class="fancybox_mark">(p.s 請直接按 X 關掉即可^_^。)</span>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>