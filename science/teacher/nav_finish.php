<?php
	$page_url = '<a href="index.php">專題指導</a> > 已結案專題';
	include("api/php/header.php");
?>
<style>
/*------------------------------------已結案專題------------------------------------*/
.finish_box{
	float: left;
	margin: 10px 15px;
}
.finish_info{
	width: 240px;
	min-height: 215px;
	padding: 5px 10px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
	cursor: pointer;
}
.finish_content{
	position: relative; 					/*----------為使用z-index-----------*/
	margin-top: -20px;
	z-index: 1;
}
.finish_content h1{
	font-size: 180%;
}
.finish_content h4{
	line-height: 8px;
}
.finish_group{
	position: relative;							/*----------為使用z-index-----------*/
	padding-top: 20px;
	margin-top: -20px;
	background-color: #F1FAFA;
	display: none;
	z-index: 1;
}
.finish_function{
	position: relative;
	top: -1px;
	left: 6px;
	width: 228px;
	height: 130px;
	padding: 10px 10px 0px 10px;
	line-height: 30px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
	display: none;
}
.finish_larrow{
	position: relative;
	top: 90px;
	left: -110px;
	cursor: pointer;
	z-index: 5;
}
.finish_rarrow{
	position: relative;
	top: 90px;
	left: 110px;
	cursor: pointer;
	z-index: 5;
}
.finish_time{
	margin: 2px 0px 4px 5px;
	float: right;
}
.finish_scroll{
	float: right;
	display: none;
}
.finish_mascot{
	float: right;
	margin-top: -30px;
}
.finish_remind{
	padding-top: 220px;
	font-size: 220%;
	font-weight: bolder;
	text-align: center;
}
.finish_notes{
	text-decoration: underline;
	color: blue;
	cursor: pointer;
}
/*---------------------------------已結案專題：NOTE---------------------------------*/
#finish_note_area{
	width: 30%;
	min-height: 340px;
	background-color: #F7EB87;
	border: 0px;
	border-radius: 0px;
}
#finish_note_form textarea{
	width: 100%;
	height: 250px;
	background-color: #F7EB87;
	resize: none;
}
</style>
<script>
$(function(){
/*------------------------------------已結案專題------------------------------------*/
	$(".finish_content").click(function(){
		$("#function" + $(this).attr('value')).toggle();
	});
	$(".finish_larrow, .finish_rarrow").click(function(){
		$("#group" + this.id).toggle();
		$("#content" + this.id).toggle();
	});
/*-------------------------------已結案專題：時間倒數-------------------------------*/
	$(".finish_time").mouseover(function(){
		$("#scroll" + this.id).show();
	});
	$(".finish_time").mouseleave(function(){
		$("#scroll" + this.id).hide();
	});
/*---------------------------------已結案專題：NOTE---------------------------------*/
	// My Notes讀取
	$(".finish_notes").click(function(){
		var project_id = $(this).attr('id');
		// console.log(project_id);
		$("textarea[name=finish_note_content]").html("");

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
					$("textarea[name=finish_note_content]").html(data[a].notes_content);
				}
				$("#finish_note_fancybox").show();
			}
		});	
	});
});
</script>
<div id="centercolumn">
	<?php
		$sql = "SELECT * FROM `project` WHERE (`t_m_id` = '".$_SESSION['UID']."' || `t_s_id` = '".$_SESSION['UID']."')&& `finish` = '0' ORDER BY `project_time` ASC";
		$qry = mysql_query($sql, $link) or die(mysql_error());
		if(mysql_num_rows($qry) > 0){		// 確定有小組
			while($row = mysql_fetch_array($qry)){
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
						$group_member = $n_row['name'];
					}
				}
				if($group_member == ""){
					$group_member = "無";
				}
				if($row['state'] == '1'){
					echo "<div class='finish_box'>".
							"<div class='finish_info'>".
								"<img src='../model/images/project_larrow.png' width='15px' class='finish_larrow' id='".$row['p_id']."'>".
								"<img src='../model/images/project_rarrow.png' width='15px' class='finish_rarrow' id='".$row['p_id']."'>".
								"<div class='finish_content' id='content".$row['p_id']."' value='".$row['p_id']."'>".
									"<img src='../model/images/project_time.png' width='12px' class='finish_time' id='".$row['p_id']."'><div class='finish_scroll' id='scroll".$row['p_id']."'>倒數時間：<span style='color: red;'>已結束</span></div>".
									"<h1 style='clear: both;'>主題：".$theme_name."</h1>".
									"<h4>研究題目：".$topic_name."</h4>".
									"<div>小組名稱：".$row['pname']."</div>".
									"<div>進度：".$stage." ".$stage_name."</div>".
								"</div>".
								"<div class='finish_group' id='group".$row['p_id']."'>".
									"<div><b>- 組長 -</b><br />".$group_chief."</div><br />".
									"<div><b>- 組員 -</b><br />".$group_member."</div>".
									"<div class='finish_mascot'><img src='".$row['mascot']."' width='70%'></div>".
								"</div>".
							"</div>".
							"<div class='finish_function' id='function".$row['p_id']."'>".
								"<div class='log_btn' value='歷史討論紀錄'><a href='project_discuss.php?p_id=".$row['p_id']."&&disabled=disabled'>歷史討論紀錄</a></div>".
								"<div class='log_btn' value='結案進度管理'><a href='project_schedule.php?p_id=".$row['p_id']."&&disabled=disabled'>結案進度管理</a></div>".
								"<div class='log_btn' value='總學習評量'><a href='project_score.php?p_id=".$row['p_id']."&&disabled=disabled'>總學習評量</a></div>".
								"<div class='finish_notes log_btn' id='".$row['p_id']."' value='My Notes'>My Notes</div>".
							"</div>".
						"</div>";
				}
			}
		}else{
			echo "<div class='finish_remind'>(尚未有結束專題小組。)</div>";
		}
	?>
	<div class="fancybox_box" id="finish_note_fancybox">
		<div class="fancybox_area" id="finish_note_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 15px 0px 10px 0px;">My Notes<img src="../model/images/project_info.png" width="25px" title="My Notes：可以記錄重要事情並備註給小組。" style="vertical-align: middle;"></h2>
		<hr />
			<form id="finish_note_form">
				<input type="text" name="project_id" style="display: none;">
				<textarea name='finish_note_content'></textarea>
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>
