<style>
/*---------------------------------小組工作室：4-1----------------------------------*/
.research_download, .research_edit, .research_discuss, .research_read{
	float: left;
	margin-left: 22px;
	font-size: 14px;
	color: #808080;
	cursor: pointer;
}
.research_view, .research_chat{
	float: left;
	margin-left: 18px;
	/*margin: 0px 15px;*/
	font-size: 14px;
	color: #808080;
	cursor: pointer;
}
.steps_box{
	width: 225px;
	height: 285px;
}
.steps_box img{
	vertical-align: middle;						/*-----------圖文對齊-----------*/
}
.steps_box p{
	margin: -5px 0px 0px 0px;
	font-size: 15px;
	text-align: left;
}
.research_checked{
	position: relative;
	top: -10px;
	left: 5px;
}
.research_next{
	float: right;
}
/*-------------------------------編輯一般性討論：4-1--------------------------------*/
#discussion_general_area textarea{
	width: 75%;
	height: 100px;
	resize: none;
}
/*-------------------------------編輯綜合性討論：4-1--------------------------------*/
.research_complex_table{
	width: 95%;
	margin: 20px auto;
	border: 1px solid #1F1F1F;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_complex_table th{
	padding: 15px;
	text-align: center;
	border: 1px solid #1F1F1F;
	background-color: #6699CC;
}
.research_complex_table td{
	text-align: center;
	border-left: 1px solid #1F1F1F;
	border-right: 1px solid #1F1F1F;
	background-color: #D2DEEF;
}
.complex_new{
	height: 8%;
	padding-top: 5px;
	font-size: 26px;
	font-weight: bolder;
	color: #1C90FD;
	border-top: 1px solid #1F1F1F;
	cursor: pointer;
}
.complex_new:hover{
	color: #6699CC;
}
#discussion_complex_area textarea{
	width: 75%;
	height: 100px;
	resize: none;
}
</style>
<script>
$(function(){
/*---------------------------------小組工作室：4-1----------------------------------*/
	$(".research_download").click(function(){
		var fileurl = $(this).attr("value");
			location.href = fileurl;
	});
	$(".research_edit").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID

		$(".question_id").val(question_id);

		$(".research_next").hide();
		// 按鈕
		$("#discussion_general_add").show();
		// 清空
		$("textarea[name=research_description]").val("");

		$("#discussion_general_fancybox").show();
	});
	$(".research_next").click(function(){
		$(".fancybox_box").hide();
	});
/*-------------------------------編輯一般性討論：4-1--------------------------------*/
	$("#discussion_general_add").click(function(){
		var question_id = $(".question_id").val();				// 抓取問題ID

		if($("#discussion_general_form textarea").val() == ""){
			alert("【系統】尚未填寫討論說明。");
		}else{
			$("#discussion_general_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_general",
					question_id : question_id,
					action : "stage4-1_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】一般性討論新增成功！");
					window.location.reload();
				}
			});
		}
	});	
	$(".research_read").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID

		$(".research_next").show();
		// console.log(question_id);
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
					type : "read_general",
					question_id : question_id,
					action : "stage4-1_update"
			},
			error : function(){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function (data){
				for(var a in data){
					$("textarea[name=research_description]").val(data[a].discussion_description);
					// 隱藏區域
					$("#discussion_general_add").hide();
				}
				$("#discussion_general_fancybox").show();
			}
		});
	});
	$(".research_discuss").click(function(){
		var response = ($(this).parent().attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("一般性討論 - " + title);
		$("select[name=discuss_stage]").val("4-1");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
/*-------------------------------編輯綜合性討論：4-1--------------------------------*/
	$(".complex_new").click(function(){
		$("input[name=research_question]").prop("checked", false);
		$("textarea[name=research_description]").val("");
		$(".research_next").hide();
		$("#discussion_complex_add").show();
		$("#discussion_complex_fancybox").show();
	});
	$("#discussion_complex_add").click(function(){
		var questionlist = '';

		$('input:checkbox[name=research_question]:checked').each(function(){
			questionlist += $(this).val() + ",";
		});

		if(questionlist.length > 0) {
			questionlist = questionlist.substring(0, questionlist.length - 1);
		} // 去最後標點符號

		if($("#discussion_complex_form textarea").val() == ""){
			alert("【系統】尚未填寫討論說明。");
		}else{
			$("#discussion_complex_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_complex",
					questionlist : questionlist,
					action : "stage4-1_update"
				},
				error : function(){
					alert("【系統】送出失敗！請再重試一次！");
					return;
				},
				success : function (data) {
					alert("【系統】綜合性討論新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".research_view").click(function(){
		var discussion_id = $(this).parent().parent().attr('id');			// 抓取討論ID
		// console.log(discussion_id);
		$(".research_next").show();
		$("input[name=research_question]").prop("checked", false);

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_complex",
				discussion_id : discussion_id,
				action : "stage4-1_update"
			},
			error : function(){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					var related = data[a].discussion_related.split(",");

					for(var i = 0; i < related.length; i++){
						$("#question" + related[i]).prop("checked", true);
					}

					$("textarea[name=research_description]").val(data[a].discussion_description);
					// 隱藏區域
					$("#discussion_complex_add").hide();
				}
				$("#discussion_complex_fancybox").show();
			}
		});
	});
	$(".research_chat").click(function(){
		var response = ($(this).parent().parent().attr('value')).split('|', 2);		// 拆值
		var title = response[0];
		var description = response[1];

		$("#discuss_new_fancybox").show();							// 新增
		$("input[name=discuss_title]").val("綜合性討論 - " + title);
		$("select[name=discuss_stage]").val("4-1");
		$("select[name=discuss_type]").val("1");
		$("textarea[name=discuss_description]").val(description);
	});
/*----------------------------------繳交審核表：4-1----------------------------------*/
	// 繳交作業
	$("#research_discussion_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 3;				// 檢核表的題數
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
						action : "stage4-1_update"
					},
					error : function(){
						alert("【系統】送出失敗！請再重試一次！");
						return;
					},
					success : function (data){
						alert("【系統】研究討論審核表送出成功！");
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '4-1'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(審核表)
			$display2 = '';					// 消失(審核表)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<h1 id="title">撰寫正式研究討論</h1>
		<p>要記得每個研究討論可能都會與不同的研究問題以及有相關。請在填寫研究討論的時候，勾選出與這項研究討論相關的研究問題有哪些。</p>
		<p class="steps_tips">[提醒：每個研究問題都會有一個一般性研究討論，請先完成一般性研究討論再進行綜合性研究討論。]</p>
		<?php
			$active1 = 'active';				// 一般性討論(初始)
			$active2 = '';						// 綜合性討論(初始)
			// 計算總問題筆數
			$q_sql = "SELECT COUNT(q_id) AS question_number FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
			$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
			$q_row = mysql_fetch_array($q_qry);
				$question = $q_row['question_number'];

			// 計算一般性討論
			$f_sql = "SELECT COUNT(d_id) AS question_discussion FROM `research_discussion` WHERE `p_id`= '".$_SESSION['p_id']."' AND `type` = '0'";
			$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
			$f_row = mysql_fetch_array($f_qry);
				$discussion = $f_row['question_discussion'];

				if($question == $discussion){
					$active1 = '';						// 綜合性討論(更改)
					$active2 = 'active';				// 一般性討論(更改)
				}
		?>
		<div class="steps_menu">
			<ul>
				<li class='has-sub <?php echo $active1; ?>'><a href='#'>第一步：一般性討論</a>
					<ul>
						<li class='sub-sub'><a href='#'>
						<?php
							$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<div class='steps_box' id='".$row['q_id']."' value='".$row['question']."|".$row['assume']."'>
											<h3>".$row['question']."</h3>
											<p>研究假設：<br/>".$row['assume']."</p>";

									// 下載實驗結論
									$a_sql = "SELECT `fileurl` FROM `research_form` WHERE `q_id` = '".$row['q_id']."' AND `stage` = '3-3'";
									$a_qry = mysql_query($a_sql, $link) or die(mysql_error());
									if(mysql_num_rows($a_qry) > 0){
										// 研究結論URL
										while($a_row = mysql_fetch_array($a_qry)){
											$fileurl = $a_row['fileurl'];
										}
										echo "<div class='research_download' value='".$fileurl."'><img src='../../model/images/project_download.png' width='50px'>下載實驗結果</div>";
									}
									// 一般性討論
									$r_sql = "SELECT * FROM `research_discussion` WHERE `r_q_id`= '".$row['q_id']."' AND `p_id`= '".$_SESSION['p_id']."'";
									$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
									if(mysql_num_rows($r_qry) > 0){		// 已填一般性討論
										echo "<div class='research_read'><img src='../../model/images/project_checked.png' width='15px' class='research_checked'><img src='../../model/images/project_edit.png' width='50px'>編輯一般性討論</div>";
									}else{
										echo "<div class='research_edit'><img src='../../model/images/project_edit.png' width='50px'>編輯一般性討論</div>";
									}
									
									echo "<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'>實驗討論</div>
									</div>";
								}
							}
						?>
						</a></li>
					</ul>
				</li>
				<li class='has-sub <?php echo $active2; ?>'><a href='#'>第二步：綜合性討論</a>
					<ul>
						<li class='sub-sub'><a href='#'>
							<table class="research_complex_table">
								<tr>
									<th width="68%">相關研究問題</th>
									<th width="16%">綜合性討論</th>
									<th width="16%">討論活動</th>
								</tr>
								<?php
									$sql = "SELECT * FROM `research_discussion` WHERE `p_id`= '".$_SESSION['p_id']."' AND `type` = '1'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										while($row = mysql_fetch_array($qry)){
											echo "<tr id='".$row['d_id']."' value='問題".$row['r_q_id']."|".$row['description']."'>
													<td>".$row['description']."</td>
													<td align='center'><div class='research_view'><img src='../../model/images/project_edit.png' width='55px'><br />觀看研究結果</div></td>
													<td align='center'><div class='research_chat'><img src='../../model/images/project_discuss.png' width='55px'><br />觀看研究討論</div></td>
												</tr>";
										}
									}
								?>
								<tr>
									<td colspan="3"><div class="complex_new">+ 新增綜合性問題</div></td>
								</tr>
							</table>
							<?php
								if($_SESSION['chief'] == '1'){ // 組長
									// 計算綜合性討論
									$d_sql = "SELECT COUNT(d_id) AS question_complex FROM `research_discussion` WHERE `p_id`= '".$_SESSION['p_id']."' AND `type` = '1'";
									$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
									$d_row = mysql_fetch_array($d_qry);
										$complex = $d_row['question_complex'];

										if($complex == 0){
											echo "<button class='steps_submit' id='research_discussion_submit' disabled>繳交作業</button>";
										}else{
											echo "<button class='steps_submit' id='research_discussion_submit'>繳交作業</button>";
										}
								}
							?>
						</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="fancybox_box" id="discussion_general_fancybox">
			<div class="fancybox_area" id="discussion_general_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 編輯一般性討論 -</h2>
				<form id="discussion_general_form" method="post">
					<p style="display: none;">問題：<input type="text" class="question_id"></p>
					<p>
						<label>討論類型：</label>
						<select name="research_type">
							<option value="0">一般性討論</option>
						</select>
					</p>
					<p>
						<label>討論說明：</label>
						<textarea name="research_description"></textarea>
					</p>
					<input type="button" class="research_next" value="確定">
					<input type="button" class="fancybox_btn" id="discussion_general_add" value="送出">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="discussion_complex_fancybox">
			<div class="fancybox_area" id="discussion_complex_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 編輯綜合性討論 -</h2>
				<form id="discussion_complex_form" method="post">
					<p>
						<label>討論類型：</label>
						<select name="research_type">
							<option value="1">綜合性討論</option>
						</select>
					</p>
					<p>
						<label>研究問題：</label><br />
						<?php
							$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<input type='checkbox' name='research_question' id='question".$row['q_id']."' value='".$row['q_id']."'/>".$row['question']."<br />";
								}
							}
						?>
					</p>
					<p>
						<label>討論說明：</label>
						<textarea name="research_description"></textarea>
					</p>
					<input type="button" class="research_next" value="確定">
					<input type="button" class="fancybox_btn" id="discussion_complex_add" value="送出">
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
							<td>1. 我們有沒有說明每一組數據、圖表的涵意？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們有沒有提出誤差的來源有哪些？這些誤差如何影響實驗的結果？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 我們有沒有簡略說明實驗結果的意義？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
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