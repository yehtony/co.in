<style>
/*---------------------------------小組工作室：3-3----------------------------------*/
.research_result_table{
	width: 98%;
	margin: 20px auto;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_result_table th{
	padding: 15px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_result_table td{
	padding: 15px 0px 15px 5px;
	border: 1px solid #000000;
	background-color: #D2DEEF;
}
.research_result_table button{
	padding: 5px 10px;
	margin: 0px 2px;
}
.research_analysis_load{
	color: blue;
	text-decoration: underline;
	cursor: pointer;
}
.research_files{
	width: 70px;
	height: 30px;
	cursor: pointer;
}
/*--------------------------------觀看研究構想：3-3---------------------------------*/
#research_idea_area{
	width: 65%;
}
.research_idea_table{
	width: 98%;
	padding: 5px;
	margin: 20px auto;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_idea_table th{
	padding: 5px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_idea_table td{
	padding: 20px 0px 20px 5px;
	border: 1px solid #000000;
}
.question_steps table{
	width: 70%;
	margin: 5px auto;
	border: 1px solid #000000;
	border-collapse: collapse;
}
.question_steps th{
	width: 25%;
	line-height: 70px;
	text-align: center;
	border-right: 1px dotted #000000;
	background-color: #6699CC;
}
.material_choosed{
	padding: 3px 5px 3px 5px;
	margin: 5px 5px 5px 5px;
	height: 140px;
	font-size: 14px;
	text-align: center;
	border: rgb(165,165,165) 1px solid;
	border-radius: 3px;
	display: inline-block;
}
.material_deleted{
	float: right;
	margin-left: 5px;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
/*--------------------------------觀看實驗日誌：3-3---------------------------------*/
#research_experiment_area{
	width: 50%;
}
.research_experiment_table{
	width: 98%;
	padding: 5px;
	margin: 20px auto;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_experiment_table th{
	padding: 5px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_experiment_table td{
	padding: 15px 0px 15px 5px;
	border: 1px solid #000000;
}
.research_experiment_table button{
	color: blue;
	text-decoration: underline;
	cursor: pointer;
}
/*---------------------------------繳交審核表：3-2----------------------------------*/
#research_check_area{
	width: 45%;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：3-3----------------------------------*/
	$(".research_idea_view").click(function(){
		var question_id = $(this).parent().parent().attr('id');			// 抓取問題ID
		
		$(".question_material").html("");
		$(".question_steps").html("");

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'view_idea',
				question_id: question_id,
				action: 'stage3-1_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
					$(".question_id").val(data[a].question_id);
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
					$("#research_idea_fancybox").show();
				}
			}
		});
	});
	$(".research_experiment_view").click(function(){
		var question_id = $(this).parent().parent().attr('id');			// 抓取問題ID

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'view_experiment',
				question_id: question_id,
				action: 'stage3-3_update'
			},
			error: function(){
				alert("【警告】讀取失敗！請檢查網絡連線問題！");
				return;
			},
			success: function(data){
				for(var a in data){
					$(".question_id").val(data[a].question_id);
					$(".question_name").html(data[a].question_name);
					$(".question_assume").html(data[a].question_assume);
					$(".question_independent").html(data[a].question_independent);
					$(".question_dependent").html(data[a].question_dependent);

					$(".experiment_date").html(data[a].experiment_date);
					$(".experiment_result").html(data[a].experiment_result);
					$(".experiment_description").html(data[a].experiment_description);

					if(data[a].experiment_record != undefined){
						$(".experiment_record").attr("href", data[a].experiment_record);
					}else{
						$(".experiment_record").removeAttr("href");
					}

					$("#research_experiment_fancybox").show();
				}
			}
		});
	});
	// 上傳研究結果
	$(".research_files").change(function(){
		var question_id = $(this).parent().parent().parent().attr('id');	// 抓取問題ID
		var realfile = $(this).val();										// 檔案實際位置

		if(realfile != ""){
			$($(this).parent()).ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "upload_result",
					question_id : question_id,
					action : "stage3-3_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(){
					alert("【系統】已上傳更新！！");
					window.location.reload();
					return;
				}
			});
		}
	});
	// 實驗討論
	$(".research_discuss").click(function(){
		var response = ($(this).parent().parent().attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("研究結果 - " + title);
		$("select[name=discuss_stage]").val("3-3");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
/*----------------------------------繳交審核表：3-3----------------------------------*/
	// 繳交作業
	$("#research_result_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 2;				// 檢核表的題數
		var checklist = [];				// 檢核表的答案
		var check = "true";				// 是否全檢核過
		$("#research_check_form input:radio:checked").each(function(){	// 將檢核表的答案存入checkList
			checklist.push($(this).val());
			// 判斷是否有否
			if($(this).val() != '1'){
				check = "false";
			}
		});
		if(checklist.length < checknum){
			alert("【系統】檢核表尚未填寫完成。");
		}else if(check == "false"){
			alert("【系統】請重新確認上傳研究是否合格。");
		}else{
			var x = confirm("【系統】確定送出作業後，就無法修改囉？");
			if(x){
				$("#research_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_research",
						check_num : checknum,
						action : "stage3-3_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data){
						alert("【系統】研究結論審核表送出成功！");
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
		// 是否已繳交審核表
		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '3-3'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(審核表)
			$display2 = '';					// 消失(審核表)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<?php
			if($_SESSION['chief'] == '1'){ // 組長
				// 計算總問題筆數
				$q_sql = "SELECT COUNT(q_id) AS question_number FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
				$q_row = mysql_fetch_array($q_qry);
					$question = $q_row['question_number'];

				// 計算總資料分析
				$f_sql = "SELECT COUNT(fileurl) AS question_fileurl FROM `research_form` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-3'";
				$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
				$f_row = mysql_fetch_array($f_qry);
					$fileurl = $f_row['question_fileurl'];

					if($question == $fileurl){
						echo "<button class='steps_submit' id='research_result_submit'>繳交作業</button>";
					}else{
						echo "<button class='steps_submit' id='research_result_submit' disabled>繳交作業</button>";
					}
			}
		?>
		<h1 id="title">上傳研究結果</h1>
		<p>請根據研究結果範例，在討論活動中與小組一同討論，將討論後的研究結果撰寫成word檔，並上傳研究結果。</p>
		<p class="steps_tips">[提醒：將所有分析的資料和圖表整理成一份文件，只會紀錄最後上傳的文件。]</p>
		<table class="research_result_table">
			<tr>
				<th width="30%">研究問題</th>
				<th width="30%">研究假設</th>
				<th width="8%">研究<br/>構想</th>
				<th width="8%">實驗<br/>日誌</th>
				<th width="8%">實驗<br/>分析</th>
				<th width="8%">研究<br/>結果</th>
				<th width="8%">討論<br/>活動</th>
			</tr>
			<?php
				$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<tr id='".$row['q_id']."' value='".$row['question']."|".$row['assume']."'>".
								"<td>".$row['question']."</td>".
								"<td>".$row['assume']."</td>".
								"<td><button class='research_idea_view'>瀏覽</button></td>".
								"<td><button class='research_experiment_view'>瀏覽</button></td>";
						// 下載實驗圖表
						$a_sql = "SELECT `fileurl` FROM `research_form` WHERE `q_id` = '".$row['q_id']."' AND `stage` = '3-2'";
						$a_qry = mysql_query($a_sql, $link) or die(mysql_error());
						if(mysql_num_rows($a_qry) > 0){
							// 分析資料URL
							while($a_row = mysql_fetch_array($a_qry)){
								$fileurl = $a_row['fileurl'];
							}
							echo "<td><button class='research_analysis_load'><a target='_blank' href='".$fileurl."' download='實驗分析".$row['q_id']."'>下載</a></button></td>";
						}else{
							echo "<td><button>未上傳</button></td>";
						}
						// 抓取是否已上傳
						$l_sql = "SELECT `fileurl` FROM `research_form` WHERE `q_id` = '".$row['q_id']."' AND `stage` = '3-3'";
						$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
						if(mysql_num_rows($l_qry) > 0){
							// 研究結論URL
							while($l_row = mysql_fetch_array($l_qry)){
								$fileurl = $l_row['fileurl'];
							}
							echo "<td>
									<form>
										<input type='file' name='files' class='research_files'>
									</form>
									<span class='steps_hint' title='".$fileurl."'><center>[已上傳]</center></span>
								  </td>";
						}else{
							echo "<td>
									<form>
										<input type='file' name='files' class='research_files'>
									</form>
									<span class='steps_mark'><center>[未上傳]</center></span>
								  </td>";
						}
						echo "<td><button class='research_discuss'>進入<br/>討論</button></td>".
							 "</tr>";
					}
				}
			?>
		</table>
		<div class="fancybox_box" id="research_idea_fancybox">
			<div class="fancybox_area" id="research_idea_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究構想 -</h2>
				<table class="research_idea_table">
					<tr>
						<th width="38%">研究問題</th>
						<th width="38%">研究假設</th>
						<th width="12%">操縱變因</th>
						<th width="12%">應變變因</th>
					</tr>
					<tr>
						<td><span class="question_name"></span></td>
						<td><span class="question_assume"></span></td>
						<td><span class="question_independent"></span></td>
						<td><span class="question_dependent"></span></td>
					</tr>
					<tr>
						<td colspan="4">實驗紀錄方式：<span class="question_record"></td>
					</tr>
					<tr>
						<th colspan="4">研究材料與工具</th>
					</tr>
					<tr>
						<td colspan="4"><span class="question_material"></span></td>
					</tr>
					<tr>
						<th colspan="4">研究實驗步驟</th>
					</tr>
					<tr>
						<td colspan="4"><span class="question_steps"></span></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="fancybox_box" id="research_experiment_fancybox">
			<div class="fancybox_area" id="research_experiment_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看實驗日誌 -</h2>
				<table class="research_experiment_table">
					<tr>
						<th width="38%">研究問題</th>
						<th width="38%">研究假設</th>
						<th width="12%">操縱<br/>變因</th>
						<th width="12%">應變<br/>變因</th>
					</tr>
					<tr>
						<td><span class="question_name"></span></td>
						<td><span class="question_assume"></span></td>
						<td align="center"><span class="question_independent"></span></td>
						<td align="center"><span class="question_dependent"></span></td>
					</tr>
					<tr>
						<td colspan="4">
							<p>實驗日期：<span class="experiment_date"></span></p>
							<p>實驗結果：<span class="experiment_result"></span></p>
							<p>實驗紀錄表：<button><a target="_blank" class="experiment_record" download='實驗紀錄表'>下載</a></button></p>
							<p>結果說明：<span class="experiment_description"></span></p>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 3-3《繳交研究結果》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 是否有簡單敘述實驗的設計與過程？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 是否有說明數據以及圖表的趨勢及差異？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
					</table>
					<div class="fancybox_mark">小提醒：送出後便不能再修改和討論了。</div>
					<input type="button" class="fancybox_btn" id="research_check_submit" value="送出">
				</form>
			</div>
		</div>
	</div>
	<div class="<?php echo $display2; ?>">
		<h1>完成的學習任務，已經提交給老師！等待老師審核。</h1>
		<p>在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button>  撰寫【個人日誌】和【反思日誌】來回想自己在此階段學習到了什麼。^_^</p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>