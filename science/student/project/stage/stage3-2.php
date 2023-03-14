<style>
/*---------------------------------小組工作室：3-2----------------------------------*/
.research_analysis_table{
	width: 98%;
	margin: 20px auto;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_analysis_table th{
	padding: 15px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_analysis_table td{
	padding: 15px 0px 15px 5px;
	border: 1px solid #000000;
	background-color: #D2DEEF;
}
.research_analysis_table button{
	padding: 5px 10px;
	margin: 0px 2px;
}
.research_files{
	width: 70px;
	height: 30px;
	cursor: pointer;
}
/*--------------------------------觀看實驗日誌：3-2---------------------------------*/
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
/*----------------------------------小組工作室：3-2----------------------------------*/
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
				action: 'stage3-2_update'
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
	// 上傳資料分析
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
					type : "upload_analysis",
					question_id : question_id,
					action : "stage3-2_update"
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
		$("input[name=discuss_title]").val("資料分析 - " + title);
		$("select[name=discuss_stage]").val("3-2");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
/*----------------------------------繳交審核表：3-2----------------------------------*/
	// 繳交作業
	$("#research_analysis_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 11;				// 檢核表的題數
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
						action : "stage3-2_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data){
						alert("【系統】分析資料審核表送出成功！");
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '3-2'";
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
				$f_sql = "SELECT COUNT(fileurl) AS question_fileurl FROM `research_form` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-2'";
				$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
				$f_row = mysql_fetch_array($f_qry);
					$fileurl = $f_row['question_fileurl'];

					if($question == $fileurl){
						echo "<button class='steps_submit' id='research_analysis_submit'>繳交作業</button>";
					}else{
						echo "<button class='steps_submit' id='research_analysis_submit' disabled>繳交作業</button>";
					}
			}
		?>
		<h1 id="title">上傳資料分析和圖表</h1>
		<p>在進行資料分析與繪製圖表的過程中，請點選下列閱讀教材以供參考，根據實驗的紀錄進行資料分析和圖表繪製，並上傳分析後的報告。在分析其數據並繪製成圖表過程中，如果有任何問題隨時提出來和大家討論喔。</p>
		<p class="steps_tips">[提醒：將所有分析的資料和圖表整理成一份文件，只會紀錄最後上傳的文件。]</p>
		<table class="research_analysis_table">
			<tr>
				<th width="37%">研究問題</th>
				<th width="37%">研究假設</th>
				<th width="8%">實驗<br/>日誌</th>
				<th width="9%">資料<br/>分析</th>
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
								"<td><button class='research_experiment_view'>瀏覽</button></td>";
						// 抓取是否已上傳
						$l_sql = "SELECT `stage`, `fileurl` FROM `research_form` WHERE `q_id` = '".$row['q_id']."' AND `stage` = '3-2'";
						$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
						if(mysql_num_rows($l_qry) > 0){
							// 分析資料URL
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
				<h2>- 3-2《分析資料與繪圖》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 是否有足夠的數據知道我們的假設是正確的？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們的數據準確嗎？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 我們是否有將數據經過「平均」或是其他計算方式呈現呢？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 我們的數據是否有標示正確的單位？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 我們是否已經驗證所有的計算都是正確的？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 我們選擇的圖表類型是否適當？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 我們的圖表有標題嗎？</td>
							<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
						</tr>
						<tr>
							<td>8. 在圖表上，X軸是否為「操縱變因」，Y軸為「應變變因」？</td>
							<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
						</tr>
						<tr>
							<td>9. X軸及Y軸是否都有標示單位？</td>
							<td class="check_choose"><input type="radio" name="check_9" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_9" value="0" /></td>
						</tr>
						<tr>
							<td>10. 圖表是否有適當的比例？</td>
							<td class="check_choose"><input type="radio" name="check_10" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_10" value="0" /></td>
						</tr>
						<tr>
							<td>11. 圖表的繪製是否正確且清晰？</td>
							<td class="check_choose"><input type="radio" name="check_11" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_11" value="0" /></td>
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
		<p>在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button> 撰寫【個人日誌】回想自己在此階段學習到了什麼！ </p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>