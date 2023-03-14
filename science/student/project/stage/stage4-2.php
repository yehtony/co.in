<style>
/*---------------------------------小組工作室：4-2----------------------------------*/
.research_view_result, .research_view_discuss, .research_discuss, .research_conclusion{
	float: left;
	margin: 0px 15px;
	font-size: 14px;
	color: #808080;
	cursor: pointer;
}
.research_next{
	float: right;
}
.conclusion_btn{
	float: right;
	margin: -60px 10px 10px 0px;
	text-align: center;
}
.conclusion_table_new{
	margin: 15px 40px;
	height: 145px;
	width: 90%;
	font-size: 30px;
	font-weight: bolder;
	color: #1C90FD;
	text-align: center;
	cursor: pointer;
}
.conclusion_table{
	width: 90%;
	margin: 15px 40px;
	min-height: 100px;
}
.conclusion_table th{
	background-color: #eee;
}
.conclusion_table td{
	padding: 10px 10px;
	word-break: break-all;
}
/*--------------------------------觀看研究結果：4-2---------------------------------*/
#research_result_area{
	width: 55%;
}
.research_result_table{
	width: 98%;
	margin: 20px auto;
	border-collapse: collapse;			
}
.research_result_table th{
	padding: 8px;
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
}
/*--------------------------------觀看研究討論：4-2---------------------------------*/
#research_discussion_area{
	width: 55%;
}
.research_discussion_table{
	width: 98%;
	margin: 20px auto;
	border-collapse: collapse;			
}
.research_discussion_table th{
	padding: 8px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_discussion_table td{
	padding: 15px 0px 15px 5px;
	border: 1px solid #000000;
	background-color: #D2DEEF;
}
.research_discussion_table button{
	padding: 5px 10px;
}
/*--------------------------------新增研究討論：4-2---------------------------------*/
#research_conclusion_form textarea{
	width: 98%;
	height: 100px;
	resize: none; 									/*------------不可拉縮------------*/
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：4-2----------------------------------*/
	$(".research_view_result").click(function(){
		$("#research_result_fancybox").show();
	});
	$(".research_discuss").click(function(){
		var response = ($(this).parent().parent().parent().attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("研究結論 - " + title);
		$("select[name=discuss_stage]").val("4-2");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
	$(".research_next").click(function(){
		$(".fancybox_box").hide();
	});
/*---------------------------------觀看研究討論：4-2---------------------------------*/
	$(".research_view_discuss").click(function(){
		$("#research_discussion_fancybox").show();
	});
/*---------------------------------新增研究討論：4-2---------------------------------*/
	$(".conclusion_table_new").click(function(){
		$("#research_conclusion_area h2").html("- 新增研究結論 -");
		$("textarea[name=research_content]").val("");
		$(".research_next").hide();
		$("#research_add").show();
		$("#research_conclusion_fancybox").show();
	});
	$("#research_add").click(function(){
		if($("#research_conclusion_form textarea").val() == ""){
			alert("【系統】填寫研究討論。");
		}else{
			$("#research_conclusion_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_conclusion",
					action : "stage4-2_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】新增結論成功！");
					window.location.reload();
				}
			});
		}
	});
/*---------------------------------編輯研究結論：4-2---------------------------------*/
	$(".research_conclusion").click(function(){
		var conclusion_id = $(this).parent().parent().parent().attr('id');			// 抓取結論ID
		// console.log(conclusion_id);
		$(".research_next").show();

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_conclusion",
				conclusion_id : conclusion_id,
				action : "stage4-2_update"
			},
			error : function(){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function (data) {
				for(var a in data){
					$("#research_conclusion_area h2").html("- 觀看研究結論 -");
					$("textarea[name=research_content]").val(data[a].conclusion_content);
					// 隱藏區域
					$("#research_add").hide();
				}
				$("#research_conclusion_fancybox").show();
			}
		});
	});
/*----------------------------------繳交審核表：4-2----------------------------------*/
	$("#research_conclusion_submit").click(function(){
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
						action : "stage4-2_update"
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '4-2'";
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
				// 計算綜合性討論
				$d_sql = "SELECT COUNT(c_id) AS question_conclusion FROM `research_conclusion` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
				$d_row = mysql_fetch_array($d_qry);
					$conclusion = $d_row['question_conclusion'];

				if($conclusion == 0){
					echo "<button class='steps_submit' id='research_conclusion_submit' disabled>繳交作業</button>";
				}else{
					echo "<button class='steps_submit' id='research_conclusion_submit'>繳交作業</button>";
				}
			}
		?>
		<h1 id="title">討論研究結論</h1>
		<p>請根據「研究結果」和「研究討論」的內容提出可能的「研究結論」，並與小組其他成員一同進行討論。</p>
		<?php
			$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
			$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
			while($t_row = mysql_fetch_array($t_qry)){
				echo "<h3>研究題目：".$t_row['topic']."</h3>";
			}
		?>
		<div class="conclusion_btn">
			<div class='research_view_result'><img src='../../model/images/project_rewrite.png' width='55px'><br />觀看研究結果</div>
			<div class='research_view_discuss'><img src='../../model/images/project_discuss.png' width='55px'><br />觀看研究討論</div>
		</div>
		<?php
			$num = 1;

			$sql = "SELECT * FROM `research_conclusion` WHERE `p_id`= '".$_SESSION['p_id']."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					echo "<table class='conclusion_table' border='1' cellspacing='0'>
							<tr id='".$row['c_id']."' value='研究結論|".$row['content']."'>
								<th width='15%'>".$num."</th>
								<td width='70%'>".$row['content']."</td>
								<td width='14%'><center>
									<div class='research_conclusion'><img src='../../model/images/project_edit.png' width='50px'><br />觀看結論</div>
									<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'><br />進入討論</div>
								</center></td>
							</tr>
						</table>";
					  $num++;
				}
				echo "<table class='conclusion_table_new' border='1' cellspacing='0'>
						<tr>
							<td>+ 新增研究結論</td>
						</tr>
					  </table>";
			}else{
				echo "<table class='conclusion_table_new' border='1' cellspacing='0'>
						<tr>
							<td>+ 新增研究結論</td>
						</tr>
					  </table>";
			}
		?>
		<div class="fancybox_box" id="research_result_fancybox">
			<div class="fancybox_area" id="research_result_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看實驗結果 -</h2>
				<table class="research_result_table">
					<tr class="research_result_tr">
						<th width="42%">研究問題</th>
						<th width="42%">研究假設</th>
						<th width="15%">研究結果</th>
					</tr>
					<?php
						// 讀取研究問題--------------------------------------------------------
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								// 研究結論
								$r_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '3-3'";
								$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
								$r_row = mysql_fetch_array($r_qry);
									$result = $r_row["fileurl"];
								
								echo "<tr>".
										"<td>".$row["question"]."</td>".
										"<td>".$row["assume"]."</td>".
										"<td align='center'><button><a href='".$result."'>下載</a></button></td>".
									 "</tr>";
							}
						}
							mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
				<input type="button" class="research_next" value="確定">
			</div>
		</div>
		<div class="fancybox_box" id="research_discussion_fancybox">
			<div class="fancybox_area" id="research_discussion_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究討論 -</h2>
				<table class="research_discussion_table">
					<tr class="research_discussion_tr">
						<th width="15%">類別</th>
						<th width="42%">相關研究問題</th>
						<th width="42%">相關討論的成果</th>
					</tr>
					<?php
						// 讀取研究討論--------------------------------------------------------
						$sql = "SELECT * FROM `research_discussion` WHERE `p_id` = '".$_SESSION['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								if($row["type"] == '0'){
									// 相關研究問題
									$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$row["r_q_id"]."'";
									$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
									$q_row = mysql_fetch_array($q_qry);
										$question = $q_row['question'];

									echo "<tr>".
											"<td>一般性</td>".
											"<td>".$question."</td>".
											"<td>".$row["description"]."</td>".
										 "</tr>";
								}else if($row["type"] == '1'){
									// 相關研究問題
									$question = "";

									$q_id = explode(",", $row["r_q_id"]);

									for($j = 0; $j < count($q_id); $j++){
										$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$q_id[$j]."'";
										$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
										$q_row = mysql_fetch_array($q_qry);
											$question .= "<div>".$q_row['question']."</div>";
									}

									echo "<tr>".
											"<td>綜合性</td>".
											"<td>".$question."</td>".
											"<td>".$row["description"]."</td>".
										 "</tr>";
								}
							}
						}
							mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
				<input type="button" class="research_next" value="確定">
			</div>
		</div>
		<div class="fancybox_box" id="research_conclusion_fancybox">
			<div class="fancybox_area" id="research_conclusion_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 新增研究討論 -</h2>
				<form id="research_conclusion_form" method="post">
					<p>
						<label>研究結論：</label>
						<textarea name="research_content"></textarea>
					</p>
					<input type="button" class="research_next" value="確定">
					<input type="button" class="fancybox_btn" id="research_add" value="新增結論">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 4-1《進行研究討論》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 我們的結論和實驗結果有沒有矛盾？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們的結論是否證明或反駁我們的研究假設？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 我們是否說出「操縱變因」和「應變變因」之間的關係？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 我們是否有提出改進實驗的方法，或是未來做進一步研究的可能性？</td>
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
		<p>在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button>  撰寫【個人日誌】和【反思日誌】來回想自己在此階段學習到了什麼。^_^</p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>