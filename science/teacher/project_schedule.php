<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 小組進度管理';
	include("api/php/header.php");
?>
<style>
/*--------------------------------專題管理：進度管理--------------------------------*/
.schedule_box{
	float: left;
	width: 18%;
	height: 180px;
	margin: 30px 10px 15px 80px;
	padding: 120px 20px 60px 20px;
	color: #000000;
	border: 1px solid #000000;
	cursor: pointer;
}
.schedule_box:hover{
	background-color: #eee;
}
/*-----------------------------------小組階段成果-----------------------------------*/
#report_view_area{
	width: 60%;
	overflow-x: none;
}
/*----------------------------------小組/個人日誌-----------------------------------*/
#diary_view_area{
	width: 50%;
}
#diary_view_area h4{
	border-bottom: 1px solid #000000;
}
.diary_group{
	min-height: 60px;
	margin-left: -30px;
}
.diary_group li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: 0px 10px 10px 10px;
	font-size: 20px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
.diary_personal{
	min-height: 60px;
	margin-left: -30px;
}
.diary_personal li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: 0px 10px 10px 10px;
	font-size: 20px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
.fancybox_area textarea{
	width: 100%;
	height: 80px;
	resize: none; 								/*------------不可拉縮------------*/
}
/*-----------------------------------退回專題階段-----------------------------------*/
#back_view_area textarea{
	width: 80%;
	height: 100px;
	resize: none;
}
</style>
<script>
$(function(){
/*-----------------------------------小組階段成果-----------------------------------*/
	$("#report_view").click(function(){
		$("#report_view_fancybox").show();
	});
/*----------------------------------小組/個人日誌-----------------------------------*/
	$("#diary_view").click(function(){
		$("#diary_view_fancybox").show();
	});
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
	$("#diary_group_check").click(function(){
		$("#diary_group_fancybox").hide();
		$("#diary_view_fancybox").show();
	});
	$("#diary_personal_check").click(function(){
		$("#diary_personal_fancybox").hide();
		$("#diary_view_fancybox").show();
	});
/*-----------------------------------退回專題階段-----------------------------------*/
	$("#back_view").click(function(){
		$("#back_view_fancybox").show();
	});
});
</script>
<div id="centercolumn">
	<div class="schedule_box log_btn" id="report_view" value="小組階段成果">
		<h3 align="center">小組階段成果</h3>
		<p style="text-indent: 30px;">查看小組在各個階段的成果表現，適時督處各小組改善。</p>
	</div>
	<div class="schedule_box log_btn" id="diary_view" value="小組/個人日誌">
		<h3 align="center">小組/個人日誌</h3>
		<p style="text-indent: 30px;">查看小組/個人日誌，確認小組各成員都有跟上進度。</p>
	</div>
	<?php if(isset($_GET['disabled']) == '0'){ ?> <!-- 判斷是否為已結案專題 -->
		<div class="schedule_box log_btn" id="back_view" value="將小組退回專題階段">
			<h3 align="center">將小組退回專題階段</h3>
			<p style="text-indent: 30px;">將此小組退回先前的專題階段，小組先前的成果會被保留。</p>
		</div>
	<?php } ?>
	<div class="fancybox_box" id="report_view_fancybox">
		<div class="fancybox_area" id="report_view_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 15px;">- 階段成果 -</h2>
		<hr />
			<h3 style="margin: 0px;">1-1 決定研究主題</h3>
			<?php
				$sql = "SELECT * FROM `research_theme` WHERE `p_id` = '".$_GET['p_id']."' AND `research` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 主題來源
						if($row["info_src"] == '0'){
							$info_src = '其他';
						}else if($row["info_src"] == '1'){
							$info_src = '生活中';
						}else if($row["info_src"] == '2'){
							$info_src = '課本中';
						}else if($row["info_src"] == '3'){
							$info_src = '參考別人的題目';
						}
						echo "<p style='margin: 5px 0px 0px 40px;'>主題名稱：".$row['theme']."<br />".
								"主題來源：".$info_src."<br />".
								"主題說明：".$row['description']."</p>";
					}
				}else{
					echo "<p align='center'>《尚未決定研究主題》</p>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<h3 style="margin: 0px;">1-2 決定研究題目</h3>
			<?php
				$sql = "SELECT * FROM `research_topic` WHERE `p_id` = '".$_GET['p_id']."' AND `research` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p style='margin: 5px 0px 0px 40px;'>主題名稱：".$row['topic']."<br />".
							 	"主題說明：".$row['description']."</p>";
					}
				}else{
					echo "<p align='center'>《尚未決定研究題目》</p>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
		<hr />
			<h3 style="margin: 0px;">2-1 決定研究問題</h3><br />
			<table class="fancybox_table">
				<tr>
					<th width="35%">研究問題</th>
					<th width="35%">研究假設</th>
					<th width="15%">操縱變因</th>
					<th width="15%">應變變因</th>
				</tr>
				<?php
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_GET['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 操縱變因
							$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								$independent_name = $i_row['name'];
							}
							// 應變變因
							$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
							$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
							while($d_row = mysql_fetch_array($d_qry)){
								$dependent_name = $d_row['name'];
							}
							echo "<tr>".
									"<td>".$row["question"]."</td>".
									"<td>".$row["assume"]."</td>".
									"<td align='center'>".$independent_name."</td>".
									"<td align='center'>".$dependent_name."</td>".
								"</tr>";
						}
					}else{
						echo "<tr><td colspan='4' align='center'>《尚未決定研究問題》</td></tr>";
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
			</table>
		<hr />
			<h3 style="margin: 0px;">2-2 訂定研究構想表</h3>
			<?php
				// 初始化
				$question = "《尚未決定》";
				$assume = "《尚未決定》";
				$independent_name = "《尚未》";
				$dependent_name = "《尚未》";
				$record = "《尚未決定》";
				$material = "<center>《尚未決定》</center>";
				$steps = "<center>《尚未決定》</center>";

				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$question = $row["question"];
						$assume = $row["assume"];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}
						// 材料和工具
						$material = "";

						$m_sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['q_id']."' AND `type` != '0' AND `kind` = '1'";
						$m_qry = mysql_query($m_sql, $link) or die(mysql_error());
						while($m_row = mysql_fetch_array($m_qry)){
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$m_row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$material .= "<div class='material_choosed'>".$m_row['name']." x ".$m_row['number']." 個<br />"."<img src='".$material_pic."' width='120px;' height='120px;'>"."</div>";
						}
						// 記錄方式
						$r_sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['q_id']."' AND `type` = '0'";
						$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
						while($r_row = mysql_fetch_array($r_qry)){
							$record = $r_row['name'];
						}
						// 問題步驟
						$steps = "";

						$s_sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['q_id']."'";
						$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
						if(mysql_num_rows($s_qry) > 0){
							while($s_row = mysql_fetch_array($s_qry)){
								$steps .= "<table><tr><th>步驟".$s_row['steps_order']."</th><td>".$s_row['steps_name']."</td></tr></table>";
							}
						}
			?>
			<table class="research_idea_table">
				<tr>
					<th width="35%">研究問題</th>
					<th width="35%">研究假設</th>
					<th width="15%">操縱變因</th>
					<th width="15%">應變變因</th>
				</tr>
				<tr>
					<td><?php echo $question; ?></td>
					<td><?php echo $assume; ?></td>
					<td align="center"><?php echo $independent_name; ?></td>
					<td align="center"><?php echo $dependent_name; ?></td>
				</tr>
				<tr>
					<td colspan="4">實驗紀錄方式：<?php echo $record; ?></td>
				</tr>
				<tr>
					<th colspan="4">研究材料與工具</th>
				</tr>
				<tr>
					<td colspan="4"><?php echo $material; ?></td>
				</tr>
				<tr>
					<th colspan="4">研究實驗步驟</th>
				</tr>
				<tr>
					<td colspan="4" class="question_steps"><?php echo $steps; ?></td>
				</tr>
			</table>
			<?php
					}
				}else{
					echo "<center>《尚未決定研究構想表》</center>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<h3 style="margin: 0px;">2-3 設計研究紀錄表格</h3>
			<table width="100%" border="1" cellspacing="0" style="border: 1px solid #808080;">
				<tr>
				<?php
					$order = 1;
					$sql = "SELECT * FROM `research_form` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '2-3'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<td align='center'><a href='".$row["fileurl"]."' download>紀錄表格".$order."</a></td>";

							$order++;
						}
					}else{
						echo "<center>《尚未上傳研究紀錄表格》</center>";
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
				</tr>
			</table>
		<hr />
			<h3 style="margin: 0px;">3-1 進行實驗並紀錄</h3>
			<?php
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_GET['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}
						// 實驗日誌
						$e_sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['q_id']."'";
						$e_qry = mysql_query($e_sql, $link) or die(mysql_error());
						while($e_row = mysql_fetch_array($e_qry)){
							$experiment_date = $e_row['date'];

							if($e_row['result'] == '0'){
								$experiment_result = '成功';
							}else if($e_row['result'] == '1'){
								$experiment_result = '失敗';
							}

							$experiment_fileurl = $e_row['fileurl'];
							$experiment_description = $e_row['description'];
						}

						echo "<table class='fancybox_table' style='margin: 10px 0px;'>
							  <tr>
								<th width='38%'>研究問題</th>
								<th width='38%'>研究假設</th>
								<th width='12%'>操縱<br />變因</th>
								<th width='12%'>應變<br />變因</th>
							  </tr>".
							  "<tr>".
							  	"<td>".$row["question"]."</td>".
							 	"<td>".$row["assume"]."</td>".
								"<td align='center'>".$independent_name."</td>".
							 	"<td align='center'>".$dependent_name."</td>".
							  "</tr>".
							  "<tr>".
								"<td colspan='4'>".
									"<p>實驗日期：".$experiment_date."</p>".
									"<p>實驗結果：".$experiment_result."</p>".
									"<p>實驗紀錄表：<button><a target='_blank' href='".$experiment_fileurl."' download='實驗紀錄表'>下載</a></button></p>".
									"<p>實驗描述：".$experiment_description."</p>".
								"</td>".
							  "</tr>".
							  "</table>";
					}
				}else{
					echo "<center>《尚未寫出研究結論》</center>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<h3 style="margin: 0px;">3-2 分析資料與繪圖</h3>
			<table width="100%" border="1" cellspacing="0" style="border: 1px solid #808080;">
				<tr>
				<?php
					$order = 1;
					$sql = "SELECT * FROM `research_form` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '3-2'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<td align='center'><a href='".$row["fileurl"]."' download>分析資料".$order."</a></td>";

							$order++;
						}
					}else{
						echo "<center>《尚未上傳研究資料與繪圖》</center>";
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
				</tr>
			</table>
			<h3 style="margin: 0px;">3-3 撰寫研究結果</h3>
			<table width="100%" border="1" cellspacing="0" style="border: 1px solid #808080;">
				<tr>
				<?php
					$order = 1;
					$sql = "SELECT * FROM `research_form` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '3-3'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<td align='center'><a href='".$row["fileurl"]."' download>研究結果".$order."</a></td>";

							$order++;
						}
					}else{
						echo "<center>《尚未上傳研究研究結果》</center>";
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
				</tr>
			</table>
		<hr />
			<h3 style="margin: 0px;">4-1 進行研究討論</h3>
			<table class="fancybox_table" style="margin: 10px 0px;">
				<tr>
					<th width="8%">類別</th>
					<th width="40%">相關研究問題</th>
					<th width="40%">相關研究討論</th>
				</tr>
				<?php
					// 讀取研究討論--------------------------------------------------------
					$sql = "SELECT * FROM `research_discussion` WHERE `p_id` = '".$_GET['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							//  討論類型
							if($row['type'] == '0'){
								$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['r_q_id']."'";
								$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
								while($q_row = mysql_fetch_array($q_qry)){
									echo "<tr id='discussion".$row['d_id']."'><td align='center'>一般性</td><td>".$q_row['question']."</td><td>".$row['description']."</td></tr>";
								}
							}else{
								$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_GET['p_id']."' AND `q_id` = '".$row['r_q_id']."'";
								$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
								while($q_row = mysql_fetch_array($q_qry)){
									$question .= $q_row['question']."<br />";

									echo "<tr id='discussion".$row['d_id']."'><td align='center'>綜合性</td><td>".$question."</td><td>".$row['description']."</td></tr>";
								}
							}
						}
						
					}else{
						echo "<tr><td colspan='3' align='center'>《尚未撰寫研究討論》</td></tr>";
					}
						mysql_query($sql, $link) or die(mysql_error());
				?>
			</table>
			<h3 style="margin: 0px;">4-2 撰寫研究結論</h3>
			<table width='80%' style='margin-left: 10%; border-bottom: 1px solid #000000;'>
				<tr>
					<th width="20%">編號</th>
					<th width="80%">實驗結論</th>
				</tr>
			</table>
			<?php
				$num = 1;
				$sql = "SELECT * FROM `research_conclusion` WHERE `p_id` = '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<table class='research_conclusion_table' border='1' cellspacing='0' width='80%' style='margin-left: 10%;'>".
								"<tr id='".$row['c_id']."'>".
									"<th width='20%'>".$num."</th>".
									"<td width='80%'>".$row['content']."</td>".
								"</tr>".
							"</table>";
						$num++;
					}
				}else{
					echo "<table class='research_conclusion_table' border='1' cellspacing='0' width='80%' style='margin-left: 10%;'>".
								"<tr>".
									"<td><center>《尚未撰寫研究結論》</center></td>".
								"</tr>".
							"</table>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
		<hr />
			<h3 style="margin: 0px;">5-1 統整作品報告書</h3>
			<?php
				$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<table width='100%' border='1' cellspacing='0'><tr><td align='center'><a href='".$row["fileurl"]."' download>作品報告書</a></td></tr></table>";
					}
				}else{
					echo "<center>《尚未上傳作品報告書》</center>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<h3 style="margin: 0px;">5-2 製做作品海報</h3>
			<table width="100%" border="1" cellspacing="0">
				<tr>
					<th>上</th>
					<th>左</th>
					<th>中</th>
					<th>右</th>
				</tr>
				<tr>
				<?php
					$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_GET['p_id']."' AND `stage` = '5-2'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							if($row["order"] == '0'){
								echo "<td align='center'><a href='".$row["fileurl"]."' download>上</a></td>";
							}else if($row["order"] == '1'){
								echo "<td align='center'><a href='".$row["fileurl"]."' download>左</a></td>";
							}else if($row["order"] == '2'){
								echo "<td align='center'><a href='".$row["fileurl"]."' download>中</a></td>";
							}else if($row["order"] == '3'){
								echo "<td align='center'><a href='".$row["fileurl"]."' download>右</a></td>";
							}
						}
						mysql_query($sql, $link) or die(mysql_error());
					}
				?>
				</tr>
			</table>
			<h3 style="margin: 0px;">5-3 錄製報告影片</h3>
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
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="diary_view_fancybox">
		<div class="fancybox_area" id="diary_view_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 小組/個人日誌 -</h2>
			<h4>小組日誌</h4>
			<ul class="diary_group">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_GET['p_id']."' && `type` = '2' ORDER BY `date` ASC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<li class='diary_group_view' id='".$row['d_id']."'>".$row['date']."</li>";
					}
				}else{
					echo "<p align='center'>《尚未有人撰寫小組日誌》</p>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</ul>
			<h4>個人日誌</h4>
			<ul class="diary_personal">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_GET['p_id']."' && `type` = '0' ORDER BY `date` ASC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<li class='diary_personal_view' id='".$row['d_id']."'>".$row['date']."</li>";
					}
				}else{
					echo "<p align='center'>《尚未有人撰寫個人日誌》</p>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</ul>
		</div>
	</div>
	<div class="fancybox_box" id="back_view_fancybox">
		<div class="fancybox_area" id="back_view_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 退回專題階段 -</h2>
			<form id="examine_pass_form" method="post">
				<p style="display: none;">
					<label>小組ID：</label>
					<input type="text" name="examine_pass_project" value="<?php echo $_GET['p_id']; ?>" />
				<p style="display: none;">
					<?php
						$sql = "SELECT * FROM `project` WHERE `p_id` = '".$_GET['p_id']."' ORDER BY `project_time` ASC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){		// 確定有小組
							while($row = mysql_fetch_array($qry)){
								$stage = $row['stage'];
							}
						}
					?>
					<label>階段：</label>
					<input type="text" name="examine_pass_stage" value="<?php echo $stage; ?>"/>
				</p>
				<p style="display: none;">
					<label>通過：</label>
					<input type="text" name="examine_pass_pass" value="unpass" />
				</p>
				<p class="examine_pass_back">
					<label>退回階段：</label>
					<select name="examine_pass_back">
						<option value="default">請選擇退回階段...</option>
						<?php
							$s_sql = "SELECT `s_id` FROM `stage` WHERE `stage` = '".$stage."'";
							$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
							$s_row = mysql_fetch_array($s_qry);
								$s_id = $s_row['s_id'];

							$sql = "SELECT * FROM `stage` WHERE `s_id` <= '".$s_id."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['stage']."'>".$row['stage']." ".$row['name']."</option>";
								}
							}else{ // 嘗試性實驗
								echo "<option value='2-4'>2-4 進行嘗試性研究</option>";
							}
								mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<p>
					<label>評語：</label>
					<textarea name="examine_pass_content"></textarea>
				</p>
				<input type="button" class="fancybox_btn" id="examine_pass_submit" value="送出">
			</form>
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
</div>
<?php
	include("api/php/footer.php");
?>