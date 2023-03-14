<style>
/*---------------------------------小組工作室：3-1----------------------------------*/
.research_review, .research_experiment, .research_discuss, .research_experiment_read{
	float: left;
	margin-left: 22px;
	font-size: 14px;
	color: #808080;
	cursor: pointer;
}
.steps_box img{
	vertical-align: middle;						/*-----------圖文對齊-----------*/
}
.research_checked{
	position: relative;
	top: -10px;
	left: 5px;
}
/*--------------------------------觀看研究構想：3-1---------------------------------*/
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
/*--------------------------------新增實驗日誌：3-1---------------------------------*/
#research_experiment_area{
	width: 35%;
}
#research_experiment_form textarea{
	width: 95%;
	height: 100px;
	resize: none; 									/*------------不可拉縮------------*/
}
/*---------------------------------繳交審核表：3-1----------------------------------*/
#research_check_area{
	width: 45%;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：3-1----------------------------------*/
	$(".research_review").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID

		$(".question_name").html(""); 		// 一開始則清空
		$(".question_assume").html("");
		$(".question_independent").html("");
		$(".question_dependent").html("");
		$(".question_record").html("");
		
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
	// 實驗討論
	$(".research_discuss").click(function(){
		var response = ($(this).parent().attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();									// 新增
		$("input[name=discuss_title]").val("執行實驗 - " + title);
		$("select[name=discuss_stage]").val("3-1");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
	// 新增實驗日誌
	$(".research_experiment").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID
		$(".question_id").val(question_id);
		$("#research_experiment_fancybox").show();

		$("input[name=research_date]").val("");
		$("select[name=research_result]").val("default");
		$("textarea[name=research_description]").val("");
		// 下載
		$(".research_files").hide();
		$("input[name=files]").show();
		// 按鈕
		$("#research_add").show();

	});
	// 新增日誌
	$("#research_add").click(function(){
		var question_id = $(".question_id").val();				// 抓取問題ID

		if($("#research_experiment_form input[type=date]").val() == ""){
			alert("【系統】日期尚未選擇。");
		}else if($("#research_experiment_form textarea").val() == "" || $("#research_experiment_form select").val() == "default"){
			alert("【系統】尚未填寫完成。");
		}else{
			$("#research_experiment_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_experiment",
					question_id : question_id,
					action : "stage3-1_update"
				},
				error : function(){
					alert("【系統】送出失敗！請再重試一次！");
					return;
				},
				success : function (data) {
					alert("【系統】實驗日誌新增成功！");
					window.location.reload();
				}
			});
		}
	});
	// 觀看已繳交實驗結果
	$(".research_experiment_read").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID
		// console.log(question_id);
		// 下載
		$(".research_files").show();
		$("input[name=files]").show();
		// 按鈕
		$("#research_add").show();
		
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
					type : "read_experiment",
					question_id : question_id,
					action : "stage3-1_update"
			},
			error : function(){
				alert("【系統】送出失敗！請再重試一次！");
				return;
			},
			success : function(data){
				for(var a in data){
					$("input[name=research_date]").val(data[a].experiment_date);
					$("select[name=research_result]").val(data[a].experiment_result);
					$("textarea[name=research_description]").val(data[a].experiment_description);

					if(data[a].experiment_fileurl != undefined){
						$(".research_files").html("<a href='"+ data[a].experiment_fileurl +"' download>檔案下載</a>");
					}else{
						$(".research_files").html("未上傳檔案");
					}
					// 隱藏區域
					$("input[name=files]").hide();
					$("#research_add").hide();
				}
				$("#research_experiment_fancybox").show();
			}
		});
	});
/*----------------------------------繳交審核表：3-1----------------------------------*/
	// 繳交作業
	$("#research_experiment_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 4;				// 檢核表的題數
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
						action : "stage3-1_update"
					},
					error : function(){
						alert("【系統】送出失敗！請再重試一次！");
						return;
					},
					success : function (data){
						alert("【系統】實驗日誌審核表送出成功！");
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '3-1'";
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

				// 計算總實驗日誌
				$f_sql = "SELECT COUNT(e_id) AS question_experiment FROM `research_experiment` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
				$f_row = mysql_fetch_array($f_qry);
					$experiment = $f_row['question_experiment'];

					if($question == $experiment){
						echo "<button class='steps_submit' id='research_experiment_submit'>繳交作業</button>";
					}else{
						echo "<button class='steps_submit' id='research_experiment_submit' disabled>繳交作業</button>";
					}
			}
		?>
		<h1 id="title">上傳實驗紀錄和討論實驗結果</h1>
		<p style="text-indent: 2em;">在進行實驗以及做實驗紀錄的過程中，請點選下列閱讀教材以供參考。每當完成一個實驗時，請下載紀錄表格來撰寫實驗紀錄，並點選「新增」上傳每一個你們進行過的實驗結果及紀錄，並在「討論活動」中進行討論。</p>
		<p class="steps_tips">[提醒：如果實驗失敗時仍然要上傳實驗紀錄。]</p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
		<?php
			$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					echo "<div class='steps_box' id='".$row['q_id']."' value='".$row['question']."|".$row['assume']."'>
							<h3>".$row['question']."</h3>
							<div class='research_review'><img src='../../model/images/project_review.png' width='50px'>觀看研究構想表</div>";

					// 實驗結果
					$r_sql = "SELECT * FROM `research_experiment` WHERE `q_id`= '".$row['q_id']."' AND `p_id`= '".$_SESSION['p_id']."'";
					$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
					if(mysql_num_rows($r_qry) > 0){
						// 已填寫實驗結果
						echo "<div class='research_experiment_read'><img src='../../model/images/project_diary.png' width='50px'>實驗日誌<img src='../../model/images/project_checked.png' width='15px' class='research_checked'></div>";
					}else{
						echo "<div class='research_experiment'><img src='../../model/images/project_diary.png' width='50px'>實驗日誌</div>";
					}
						
						echo "<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'>實驗討論</div>
						  </div>";
				}
			}
		?>
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
						<td align="center"><span class="question_independent"></span></td>
						<td align="center"><span class="question_dependent"></span></td>
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
				<h2>- 新增實驗日誌 -</h2>
				<form id="research_experiment_form" method="post">
					<p style="display: none;">問題：<input type="text" class="question_id"></p>
					<p>
						<label>實驗日期：</label>
						<input type="date" name="research_date" />
					</p>
					<p>
						<label>實驗結果：</label>
						<select name="research_result">
							<option value="default">實驗結果為...</option>
							<option value="0">成功</option>
							<option value="1">失敗</option>
						</select>
					</p>
					<p>
						<label>實驗紀錄表：</label>
						<input type="file" name="files" />
						<span class="research_files"></span>
					</p>
					<p>
						<label>結果說明：</label>
						<textarea name="research_description"></textarea>
						<span class="steps_mark">（p.s 請詳細描述實驗結果、與上次實驗的差異性以及成功或失敗的原因，但不需多加推論。）</span>
					</p>
					<input type="button" class="fancybox_btn" id="research_add" value="新增日誌">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 3-1《進行實驗記錄》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 我們是否小心、謹慎進行測量？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 有謹慎地保持「控制變因」不變，以免影響到實驗結果嗎？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 有在實驗日誌中詳細的描述觀察到的現象及記錄數據嗎？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 有使用實驗記錄表來記錄數據嗎？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
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
		<p style="text-indent: 2em;">在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button> 撰寫【個人日誌】回想自己在此階段學習到了什麼！ </p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>