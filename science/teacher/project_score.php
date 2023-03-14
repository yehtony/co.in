<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > <a href="project_assess.php?p_id='.$_GET['p_id'].'">學習評量與結案</a> > 學習成果評量';
	include("api/php/header.php");
?>
<style>
/*--------------------------------專題管理：成果評量--------------------------------*/
.score_type{
	list-style-type: none;
}
.score_type li{
	float: left;
	width: 100px;
	padding: 5px;
	font-size: 22px;
	font-weight: bolder;
	text-align: center;
	border: 1px solid #4C4C4C;
	border-radius: 10px 10px 0px 0px;
	background-color: #F1FAFA;
	cursor: pointer;
}
.score_type li:hover{
	background-color: #5B9BD5;
}
.score_pages{
	clear: both;
	padding: 5px 40px 30px 40px;
	border: 1px solid #000000;
	overflow: auto;							/*-----------自動增長關鍵-----------*/
	display: none;
}
.fancybox_area textarea{
	width: 100%;
	height: 80px;
	resize: none; 								/*------------不可拉縮------------*/
}
/*--------------------------------專題管理：小組成績--------------------------------*/
.group_grade_area fieldset{
	width: 95%;
	min-height: 35px;
	margin-bottom: 20px;
	border: #4C4C4C 1px solid;
	background-color: #ECECEC;
	
	box-shadow: 5px 5px 0px #4a4a4a;
	-moz-box-shadow: 5px 5px 0px #4a4a4a;
	-webkit-box-shadow: 5px 5px 0px #4a4a4a;
}
.group_grade_area fieldset h3{
	margin: 0px 0px 10px 0px;
}
.group_grade_area legend{
	width: 150px;
	text-align: center;
	line-height: 30px;
	border: #4C4C4C 1px solid;
	background-color: #ECECEC;
}
.group_grade_area label{
	margin-left: 35px;
}
.group_grade_area li{
	display: inline-block;
}
.group_grade_complete{
	padding: 10px 15px;
	margin-left: 35px;
	background-color: #D2D3CD;
	border: 1px solid #515346;
	border-radius: 5px;
	cursor: pointer;
}
.group_grade_complete a{
	color: #000000;
	text-decoration: none;
}
.group_grade_report{
	padding: 5px 8px;
	margin-right: 5px;
	background-color: #D2D3CD;
	border: 1px solid #515346;
	border-radius: 5px;
	cursor: pointer;
}
.group_grade_report a{
	color: #000000;
	text-decoration: none;
}	
.group_grade_area input[type=button]{
	float: right;
	font-size: 18px;
}
.grade_diary_group li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: 0px 10px 10px 10px;
	font-size: 18px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
#score_vedio_area{
	width: 45%;
}
#score_qna_area{
	width: 45%;
}
/*--------------------------------專題管理：個人成績--------------------------------*/
.personal_grade_area select{
	margin-top: 30px;
	font-size: 18px;
}
.personal_grade_area table{
	width: 100%;
	margin-top: 30px;
	text-align: center;

	box-shadow: 5px 5px 0px #4a4a4a;
	-moz-box-shadow: 5px 5px 0px #4a4a4a;
	-webkit-box-shadow: 5px 5px 0px #4a4a4a;
}
.personal_grade_area th{
	line-height: 40px;
	border: 1px #FFFFFF solid;
	background-color: #D6D6DA;
}
.personal_grade_area td{
	padding-top: 5px;
	padding-bottom: 10px;
	border: 1px #FFFFFF solid;
	background-color: #ECECEC;
}
.personal_grade_area li{
	display: inline-block;
}
.personal_grade_area input[type=button]{
	float: right;
	margin-top: 20px;
	font-size: 18px;
}
.grade_diary_personalI,.grade_diary_personalII,.grade_diary_personalIII,.grade_diary_personalIV,.grade_diary_personalV, .grade_diary_personalVI{
	text-align: left;
}
.grade_diary_personalI li,.grade_diary_personalII li,.grade_diary_personalIII li,.grade_diary_personalIV li,.grade_diary_personalV li, .grade_diary_personalVI li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: -8px 8px -8px 0px;
	font-size: 18px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
/*---------------------------------專題管理：總成績---------------------------------*/
.total_grade_area fieldset{
	width: 95%;
	min-height: 35px;
	margin-bottom: 20px;
	border: #4C4C4C 1px solid;
	background-color: #ECECEC;
	
	box-shadow: 5px 5px 0px #4a4a4a;
	-moz-box-shadow: 5px 5px 0px #4a4a4a;
	-webkit-box-shadow: 5px 5px 0px #4a4a4a;
}
.total_grade_area legend{
	width: 150px;
	text-align: center;
	line-height: 30px;
	border: #4C4C4C 1px solid;
	background-color: #ECECEC;
}
.total_grade_area label{
	margin-left: 35px;
}
.total_grade_area input[type=button]{
	float: right;
	font-size: 18px;
}
.total_grade_area table{
	width: 98%;
	text-align: center;
	border-collapse: collapse;
}
.total_grade_area th{
	padding: 5px 0px;
	border: 1px solid #000000;
}
.total_grade_area td{
	padding: 5px 0px;
	border: 1px solid #000000;
}
.total_grade_area button{
	float: right;
	margin: 20px 20px 0px 0px;
	font-size: 18px;
}
</style>
<script>
$(function(){
/*--------------------------------專題管理：成果評量--------------------------------*/
	$("#page_group").show();
	$("#group").addClass('show_pages').removeClass('pages');
	// 按鈕陣列
	var pages_arr = ["group", "personal", "total"];
	// 設定清單的出現和隱藏
	for(var i = pages_arr.length - 1; i >= 0; i--){
		// 設定按鈕的變化
		$("#" + pages_arr[i]).click(function(){
			var pages_id = this.id;
			if(!$("." + pages_id).hasClass("show_pages")){
				$(".show_pages").removeClass('show_pages').addClass('pages');
				$("#" + pages_id).addClass('show_pages').removeClass('pages');
				// 按鈕變色
				$(".score_type li").css("background-color", "#F1FAFA");	
				$("#" + pages_id).css("background-color", "#5B9BD5");
			}
		});
		// 設定畫面的出現和隱藏
		$("#" + pages_arr[i]).click(function(){
			var pages_id = this.id;
			if(!$("." + pages_id).hasClass("show_pages")){
				$(".score_pages").hide();
				$("#page_" + pages_id).show();
			}
		});
	};
/*--------------------------------專題管理：小組成績--------------------------------*/
	$(".group_percent_submit").click(function(){
		$("#group_percent_form").ajaxSubmit({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "percent_group",
				p_id : <?php echo $_GET['p_id']; ?>,
				action : "score_update"
			},
			error : function(){
				alert("【系統】送出失敗！！請再重試一次！！");
				return;
			},
			success : function(data){
				alert("【系統】小組[% 設定]儲存成功！");
				window.location.reload();
			}
		});
	});
	$(".group_score_submit").click(function(){
		$("#group_score_form").ajaxSubmit({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "score_group",
				p_id : <?php echo $_GET['p_id']; ?>,
				action : "score_update"
			},
			error : function(){
				alert("【系統】送出失敗！！請再重試一次！！");
				return;
			},
			success : function(data){
				alert("【系統】小組[評分]儲存成功！");
				window.location.reload();
			}
		});
	});
	// 小組日誌
	$(".diary_group_view").click(function(){
		var diary_id = $(this).attr('id');

		$("input[name=diary_group_date]").val("");
		$("textarea[name=diary_group_problem]").val("");
		$("textarea[name=diary_group_conclusion]").val("");
		$("textarea[name=diary_group_future]").val("");
		$(".diary_group_file").html("");

		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'view_group',
				diary_id: diary_id,
				p_id : <?php echo $_GET['p_id']; ?>,
				action: 'diary_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				for(var a in data){
					$("input[name=diary_group_date]").val(data[a].diary_date);
					$("textarea[name=diary_group_problem]").val(data[a].diary_content1);
					$("textarea[name=diary_group_conclusion]").val(data[a].diary_content2);
					$("textarea[name=diary_group_future]").val(data[a].diary_content3);

					if(data[a].diary_filename != undefined){
						$(".diary_group_file").html("<a href='"+ data[a].diary_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].diary_filename +"</a>");
					}else{
						$(".diary_group_file").html("未上傳檔案");
					}

					$(".diary_group_file").show();
					$("input[type=file]").hide();
					$("#diary_group_fancybox").show();
				}
			}
		});
	});
	$("#diary_group_check").click(function(){
		$("#diary_group_fancybox").hide();
	});
	// 作品影片
	$("#score_vedio_view").click(function(){
		$("#score_vedio_fancybox").show();
	});
	$("#score_vedio_submit").click(function(){
		$("#score_vedio_fancybox").hide();
	});
	// 問與答
	$("#score_qna_view").click(function(){
		$("#score_qna_fancybox").show();
	});
	$("#score_qna_submit").click(function(){
		$("#score_qna_fancybox").hide();
	});
/*--------------------------------專題管理：個人成績--------------------------------*/
	$("select[name='group_member']").change(function(){
		var group_id = $(this).val();
		// console.log(group_id);
		$(".grade_diary_personalI").html("");		// 清空
		$(".grade_diary_personalII").html("");
		$(".grade_diary_personalIII").html("");
		$(".grade_diary_personalIV").html("");
		$(".grade_diary_personalV").html("");
		$(".grade_diary_personalVI").html("");
		
		$.ajax({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_personal",
				p_id : <?php echo $_GET['p_id']; ?>,
				u_id : group_id,
				action : "score_update"
			},
			error : function(){
				alert("【系統】送出失敗！！請再重試一次！！");
				return;
			},
			success : function(data){
				for(var a in data){
					if(data[a].diary_id != undefined){
						if(data[a].diary_stage == '1-1' || data[a].diary_stage == '1-2'){
							$(".grade_diary_personalI").append("<li class='diary_personal_view' id='"+ data[a].diary_id +"'>"+ data[a].diary_date +"</li>");
						}else if(data[a].diary_stage == '2-1' || data[a].diary_stage == '2-2' || data[a].diary_stage == '2-3' || data[a].diary_stage == '2-4'){
							$(".grade_diary_personalII").append("<li class='diary_personal_view' id='"+ data[a].diary_id +"'>"+ data[a].diary_date +"</li>");
						}else if(data[a].diary_stage == '3-1' || data[a].diary_stage == '3-2' || data[a].diary_stage == '3-3'){
							$(".grade_diary_personalIII").append("<li class='diary_personal_view' id='"+ data[a].diary_id +"'>"+ data[a].diary_date +"</li>");
						}else if(data[a].diary_stage == '4-1' || data[a].diary_stage == '4-2'){
							$(".grade_diary_personalIV").append("<li class='diary_personal_view' id='"+ data[a].diary_id +"'>"+ data[a].diary_date +"</li>");
						}else{
							$(".grade_diary_personalV").append("<li class='diary_personal_view' id='"+ data[a].diary_id +"'>"+ data[a].diary_date +"</li>");
						}
					}
					$("input[name=stageI_grade").val(data[a].score_score1);
					$("input[name=stageII_grade").val(data[a].score_score2);
					$("input[name=stageIII_grade").val(data[a].score_score3);
					$("input[name=stageIV_grade").val(data[a].score_score4);
					$("input[name=stageV_grade").val(data[a].score_score5);
					$("input[name=stageVI_grade").val(data[a].score_final);
				}
				$("#personal_grade_form input").attr("disabled", false);

				// 個人日誌
				$(".diary_personal_view").click(function(){
					var diary_id = $(this).attr('id');

					$("input[name=diary_personal_date]").val("");
					$("textarea[name=diary_personal_progress]").val("");
					$("textarea[name=diary_personal_discuss]").val("");
					$("textarea[name=diary_personal_learn]").val("");
					$("textarea[name=diary_personal_future]").val("");
					$(".diary_personal_file").html("");

					$.ajax({
						url  : "/co.in/science/teacher/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data: {
							type: 'view_personal',
							diary_id: diary_id,
							p_id : <?php echo $_GET['p_id']; ?>,
							action: 'diary_update'
						},
						error: function(){
							alert("【警告】讀取失敗，請檢查網絡連線問題。");
							return;
						},
						success: function(data){
							for(var a in data){
								$("input[name=diary_personal_date]").val(data[a].diary_date);
								$("textarea[name=diary_personal_progress]").val(data[a].diary_content1);
								$("textarea[name=diary_personal_discuss]").val(data[a].diary_content2);
								$("textarea[name=diary_personal_learn]").val(data[a].diary_content3);
								$("textarea[name=diary_personal_future]").val(data[a].diary_content3);

								if(data[a].diary_filename != undefined){
									$(".diary_personal_file").html("<a href='"+ data[a].diary_fileurl +"' download>"+ data[a].diary_filename +"</a>");
								}else{
									$(".diary_personal_file").html("未上傳檔案");
								}

								$(".diary_personal_file").show();
								$("input[type=file]").hide();
								$("#diary_personal_fancybox").show();
							}
						}
					});
				});
			}
		});
	});
	$(".personal_grade_submit").click(function(){
		var group_id = $("select[name='group_member']").val();
		// console.log(group_id);
		if(group_id == "default"){
			alert("【系統】請選擇小組組員。");
		}else{
			$("#personal_grade_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "score_personal",
					p_id : <?php echo $_GET['p_id']; ?>,
					s_id : group_id,
					action : "score_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】個人評量儲存成功！");
					window.location.reload();
				}
			});
		}
	});
	$("#diary_personal_check").click(function(){
		$("#diary_personal_fancybox").hide();
	});
/*--------------------------------專題管理：小組成績--------------------------------*/
	$(".total_grade_submit").click(function(){
		$("#total_grade_form").ajaxSubmit({
			url  : "/co.in/science/teacher/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "total_grade",
				p_id : <?php echo $_GET['p_id']; ?>,
				action : "score_update"
			},
			error : function(){
				alert("【系統】送出失敗！！請再重試一次！！");
				return;
			},
			success : function(data){
				alert("【系統】總成績[% 設定]儲存成功！");
				window.location.reload();
			}
		});
	});
});
</script>
<div id="centercolumn">
	<?php
		// 判斷是否已結案
		if(!isset($_GET['disabled'])){
			$_GET['disabled'] = "";
		}
	?>
	<h1 id="title">- 學習成果評量 -</h1>
	<ul class="score_type">
		<li class="log_btn" id="group" value="小組評量" style="background-color: #5B9BD5;">小組評量</li>
		<li class="log_btn" id="personal" value="個人評量">個人評量</li>
		<li class="log_btn" id="total" value="總成績">總成績</li>
	</ul>
	<div id="page_group" class="score_pages">
		<?php
			$p_sql = "SELECT `pname` FROM `project` WHERE `p_id`= '".$_GET['p_id']."'";
			$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
			while($p_row = mysql_fetch_array($p_qry)){
				echo "<h3>小組名稱：".$p_row['pname']."</h3>";
			}
				mysql_query($p_sql, $link) or die(mysql_error());
		?>
		<div class="group_grade_area">
			<fieldset class="grade_field">
				<legend>過程評分</legend>
				<h3>小組日誌</h3>
				<ul class="grade_diary_group">
				<?php
					$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_GET['p_id']."' && `type` = '2' ORDER BY `date` ASC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<li class='diary_group_view' id='".$row['d_id']."'>".$row['date']."</li>";
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
				</ul>
			</fieldset>
			<fieldset class="grade_field">
				<legend>作品評分</legend>
				<ul>
					<?php
						// 作品報告書
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-1'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_complete' style='background-color: #FFD966;'><a href='".$row['fileurl']."' download>作品報告書</a></li>";
							}
						}else{
							echo "<li class='group_grade_complete'>作品報告書</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());

						// 作品海報
						echo "<li>".
								"<div style='text-align: center; margin-left: 30px;'>作品海報</div>".
							 	"<ul>";

						// 作品海報(上)
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-2' AND `order` = '0'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_report' style='background-color: #FFD966;'><a href='".$row['fileurl']."' download>上</a></li>";
							}
						}else{
							echo "<li class='group_grade_report'>上</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());

						// 作品海報(左)
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-2' AND `order` = '1'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_report' style='background-color: #FFD966;'><a href='".$row['fileurl']."' download>左</a></li>";
							}
						}else{
							echo "<li class='group_grade_report'>左</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());

						// 作品海報(中)
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-2' AND `order` = '2'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_report' style='background-color: #FFD966;'><a href='".$row['fileurl']."' download>中</a></li>";
							}
						}else{
							echo "<li class='group_grade_report'>中</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());

						// 作品海報(右)
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-2' AND `order` = '3'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_report' id='".$row['r_id']."' style='background-color: #FFD966;'><a href='".$row['fileurl']."' download>右</a></li>";
							}
						}else{
							echo "<li class='group_grade_report'>右</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());

						echo "</ul></li>";

						// 作品影片
						$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-3'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<li class='group_grade_complete' id='score_vedio_view' value='".$row['r_id']."' style='background-color: #FFD966;'>作品影片</li>";
							}
						}else{
							echo "<li class='group_grade_complete'>作品影片</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());	

						// 問與答
						$sql = "SELECT * FROM `research_qna` WHERE `p_id` = '".$_GET['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							echo "<li class='group_grade_complete' id='score_qna_view' value='".$row['p_id']."' style='background-color: #FFD966;'>問與答</li>";
						}else{
							echo "<li class='group_grade_complete'>問與答</li>";
						}
							mysql_query($sql, $link) or die(mysql_error());			 
					?>
				</ul>
			</fieldset>
			<fieldset class="grade_field">
				<legend>% 設定</legend>
				<form id="group_percent_form">
					<?php 
						$sql = "SELECT * FROM `score_group` WHERE `p_id`= '".$_GET['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<label>過程成績：</label><input type='number' name='process_grade' min='0' max='100' value='".$row['per_process']."' ".$_GET['disabled']." /> %".
								 "<label>作品成績：</label><input type='number' name='complete_grade' min='0' max='100' value='".$row['per_report']."' ".$_GET['disabled']." /> %";
							}
						}
					?>
					<span style="margin-left: 20px; color: red;">(兩者相加必需是100%)</span>
					<input type='button' class="group_percent_submit" value="儲存" <?php echo $_GET['disabled']; ?> />
				</form>
			</fieldset>
			<fieldset class="grade_field">
				<legend>評分</legend>
				<form id="group_score_form">
					<?php 
						$sql = "SELECT * FROM `score_group` WHERE `p_id`= '".$_GET['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<label>過程成績：</label><input type='number' name='process_grade' min='0' max='100' value='".$row['process_score']."' ".$_GET['disabled']." /> 分".
								 "<label>作品成績：</label><input type='number' name='complete_grade' min='0' max='100' value='".$row['report_score']."' ".$_GET['disabled']." /> 分";
							}
						}
					?>
					<input type='button' class="group_score_submit" value="儲存" <?php echo $_GET['disabled']; ?> />
				</form>
			</fieldset>
		</div>
	</div>
	<div id="page_personal" class="score_pages">
		<div class="personal_grade_area">
			<select name="group_member">
				<option value="default">請選擇小組成員</option>
				<?php
					$g_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id`= '".$_GET['p_id']."'";
					$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
					while($g_row = mysql_fetch_array($g_qry)){
						// 抓取組員名稱----------------------------------------------------------------
						$m_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$g_row['s_id']."'";
						$m_qry = mysql_query($m_sql, $link) or die(mysql_error());
						$m_row = mysql_fetch_array($m_qry);
							$name = $m_row['name'];

						echo "<option id='1' value='".$g_row['s_id']."'>".$name."</option>";
					}
						mysql_query($g_sql, $link) or die(mysql_error());
				?>
			</select><span style="margin-left: 20px; color: red;"> (各階段分數皆占五分之一)</span>
			<form id="personal_grade_form">
				<table>
					<tr>
						<th width="20%">階段名稱</th>
						<th width="60%">個人日誌</th>
						<th width="10%">反思日誌</th>
						<th width="10%">評分</th>
					</tr>
					<tr>
						<td>形成問題</td>
						<td><ul class="grade_diary_personalI"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageI_grade" min="0" max="100" disabled/> 分</td>
					</tr>
					<tr>
						<td>計畫</td>
						<td><ul class="grade_diary_personalII"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageII_grade" min="0" max="100" disabled/> 分</td>
					</tr>
					<tr>
						<td>執行</td>
						<td><ul class="grade_diary_personalIII"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageIII_grade" min="0" max="100" disabled/> 分</td>
					</tr>
					<tr>
						<td>行程結論</td>
						<td><ul class="grade_diary_personalIV"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageIV_grade" min="0" max="100" disabled/> 分</td>
					</tr>
					<tr>
						<td>報告與展示</td>
						<td><ul class="grade_diary_personalV"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageV_grade" min="0" max="100" disabled/> 分</td>
					</tr>
					<tr>
						<td>個人平均分數</td>
						<td><ul class="grade_diary_personalVI"></ul></td>
						<td><button>瀏覽</button></td>
						<td><input type="number" name="stageVI_grade" min="0" max="100" disabled/> 分</td>
					</tr>
				</table>
				<input type='button' class="personal_grade_submit" value="儲存" <?php echo $_GET['disabled']; ?>>
			</form>	
		</div>
	</div>
	<div id="page_total" class="score_pages">
		<?php
			$p_sql = "SELECT `pname` FROM `project` WHERE `p_id`= '".$_GET['p_id']."'";
			$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
			while($p_row = mysql_fetch_array($p_qry)){
				echo "<h3>小組名稱：".$p_row['pname']."</h3>";
			}
				mysql_query($p_sql, $link) or die(mysql_error());
		?>
		<div class="total_grade_area">
			<fieldset class="grade_field">
				<legend>% 設定</legend>
				<form id="total_grade_form">
					<?php 
						$sql = "SELECT * FROM `score_group` WHERE `p_id`= '".$_GET['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<label>小組成績：</label><input type='number' name='group_grade' min='0' max='100' value='".$row['per_group']."' ".$_GET['disabled']." /> %".
								 "<label>個人成績：</label><input type='number' name='personal_grade' min='0' max='100' value='".$row['per_personal']."' ".$_GET['disabled']." /> %";
							}
						}
					?>
					<input type='button' class="total_grade_submit" value="儲存" <?php echo $_GET['disabled']; ?> />
				</form>
			</fieldset>
			<table>
				<tr>
					<th width="8%" rowspan="2">學生<br>姓名</th>
					<th colspan="2">小組評量</th>
					<th colspan="5">個人評量</th>
					<th width="8%" rowspan="2">總成績</th>
					<th width="8%" rowspan="2">最後<br>成績</th>
				</tr>
				<tr>
					<th width="8%">過程<br>成績</th>
					<th width="8%">作品<br>成績</th>
					<th width="8%">第一<br>階段</th>
					<th width="8%">第二<br>階段</th>
					<th width="8%">第三<br>階段</th>
					<th width="8%">第四<br>階段</th>
					<th width="8%">第五<br>階段</th>
				</tr>
				<?php 
					$sql = "SELECT * FROM `project_group` WHERE `p_id`= '".$_GET['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取學生名稱
							$u_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$u_qry = mysql_query($u_sql, $link) or die(mysql_error());
							$u_row = mysql_fetch_array($u_qry);
								$name = $u_row['name'];
							// 小組評量成績
							$g_sql = "SELECT * FROM `score_group` WHERE `p_id` = '".$_GET['p_id']."'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$process_score = ($g_row['per_process'] / 100)*$g_row['process_score'];
								$report_score = ($g_row['per_report'] / 100)*$g_row['report_score'];

							// 個人評量成績
							$p_sql = "SELECT * FROM `score_personal` WHERE `p_id` = '".$_GET['p_id']."' AND `s_id` = '".$row["s_id"]."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$tatol_personal = ($p_row['score_1']+$p_row['score_2']+$p_row['score_3']+$p_row['score_4']+$p_row['score_5'])/5;

							$final_score = ($g_row['per_group'] / 100)*($process_score+$report_score)+($g_row['per_personal'] / 100)*$tatol_personal;

							echo "<tr>".
									"<td>".$name."</td>".
									"<td>".$process_score."</td>".
									"<td>".$report_score."</td>".
									"<td>".$p_row['score_1']."</td>".
									"<td>".$p_row['score_2']."</td>".
									"<td>".$p_row['score_3']."</td>".
									"<td>".$p_row['score_4']."</td>".
									"<td>".$p_row['score_5']."</td>".
									"<td>".($process_score+$report_score+$tatol_personal)."</td>".
									"<td>".$final_score."</td>".
								 "</tr>";
						}
					}
				?>
			</table>
			<span style="color: red;">(按下送出後便無法再修改最後成績)</span>
			<button <?php echo $_GET['disabled']; ?>>送出</button>
			<button <?php echo $_GET['disabled']; ?>>儲存</button>
		</div>
	</div>
	<div class="fancybox_box" id="diary_group_fancybox">
		<div class="fancybox_area" id="diary_group_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看小組日誌 -</h2>
			<form id="diary_group_form" method="post">
				<p>
					<label>討論的時間：</label>
					<input type="date" name="diary_group_date">
				</p>
				<p>
					<label>討論的問題：</label>
					<textarea name="diary_group_problem" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>討論的結論：</label>
					<textarea name="diary_group_conclusion" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>後續應進行之工作：</label>
					<textarea name="diary_group_future" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>錄音檔：</label>
					<input type="file" name="files" />
					<span class="diary_group_file"></span>
				</p>
				<input type="button" class="fancybox_btn" id="diary_group_check" value="確定">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_personal_fancybox">
		<div class="fancybox_area" id="diary_personal_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看個人日誌 -</h2>
			<form id="diary_personal_form" method="post">
				<p>
					<label>討論的時間：</label>
					<input type="date" name="diary_personal_date">
				</p>
				<p>
					<label>目前研究進度已經進展到哪裡了？</label>
					<textarea name="diary_personal_progress" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>我和同學討論了什麼？</label>
					<textarea name="diary_personal_discuss" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>我學習到什麼？尤其哪些是以前沒學過的？</label>
					<textarea name="diary_personal_learn" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>接下來，我想要如何進行科展？</label>
					<textarea name="diary_personal_future" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>錄音檔：</label>
					<input type="file" name="files" />
					<span class="diary_personal_file"></span>
				</p>
				<input type="button" class="fancybox_btn" id="diary_personal_check" value="確定">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="score_vedio_fancybox">
		<div class="fancybox_area" id="score_vedio_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看上傳影片 -</h2>
			<?php
				$sql = "SELECT `fileurl` FROM `research_report` WHERE `p_id`= '".$_GET['p_id']."' AND `stage`= '5-3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<center>".$row['fileurl']."</center>";
					}
				}else{
					echo "<center>《尚未上傳報告影片》</center>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<input type="button" class="fancybox_btn" id="score_vedio_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="score_qna_fancybox">
		<div class="fancybox_area" id="score_qna_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看問與答 -</h2>
			<?php
				// 讀取專題小組--------------------------------------------------------
				$sql = "SELECT * FROM `research_qna` WHERE `p_id` = '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<div>
								<h4>Q".$row["order"].". ".$row["question"]."</h4>
								<p style='margin-left: 35px;'>".$row["answer"]."</p>
							  </div>";
					}
				}
						mysql_query($sql, $link) or die(mysql_error());
			?>
			<input type="button" class="fancybox_btn" id="score_qna_submit" value="確定">
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>