<style>
/*---------------------------------小組工作室：5-1----------------------------------*/
.research_view_result,.research_view_discuss,.research_view_conclusion,.research_result,.research_discuss{
	float: left;
	margin: 0px 15px;
	line-height: 10px;
	font-size: 12px;
	color: #808080;
	cursor: pointer;
}
.report_btn{
	float: right;
	margin: 0px 10px 10px 0px;
	text-align: center;
}
.steps_box img{
	vertical-align: middle;						/*-----------圖文對齊-----------*/
}
.research_next{
	float: right;
}
/*--------------------------------觀看研究結果：5-1---------------------------------*/
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
/*--------------------------------觀看研究討論：5-1---------------------------------*/
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
/*--------------------------------觀看研究結論：5-1---------------------------------*/
.conclusion_table{
	width: 100%;
	margin: 15px 0px;
	min-height: 100px;
}
.conclusion_table th{
	background-color: #eee;
}
.conclusion_table td{
	padding: 10px 10px;
	word-break: break-all;
}
/*-------------------------------討論作品報告書：5-1--------------------------------*/
.report_table_new{
	margin: 15px 40px;
	height: 120px;
	width: 90%;
	font-size: 30px;
	font-weight: bolder;
	color: #1C90FD;
	text-align: center;
	cursor: pointer;
}
.report_table{
	width: 90%;
	margin: 15px 40px;
	min-height: 100px;
}
.report_table th{
	text-align: center;
	background-color: #eee;
}
.report_table td{
	padding: 10px 10px;
	word-break: break-all;
}
/*-------------------------------統整作品報告書：5-1--------------------------------*/
.research_report_table{
	width: 100%;
	margin: 10px auto;
	border-collapse: collapse;
}
.research_report_table th{
	text-align: center;
	line-height: 25px;
	color: #FFFFFF;
	background-color: #6699CC;
	border: 1px solid #000000;
}
.research_report_table tr:nth-child(odd){
	background-color: #99CCFF;
}
.research_report_table td{
	border: 1px solid #000000;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：5-1----------------------------------*/
	$(".research_view_result").click(function(){
		$("#research_result_fancybox").show();
	});
	$(".research_view_discuss").click(function(){
		$("#research_discussion_fancybox").show();
	});
	$(".research_view_conclusion").click(function(){
		$("#research_conclusion_fancybox").show();
	});
	$(".research_next").click(function(){
		$(".fancybox_box").hide();
	});
/*---------------------------------觀看研究結果：5-1---------------------------------*/
	$(".research_discuss").click(function(){
		$("#page_work").hide();
		$("#page_discuss").show();
	});
/*---------------------------------上傳作品報告：5-1---------------------------------*/
	$(".research_files").change(function(){
		var realfile = $(this).val();				// 檔案實際位置

		if(realfile != ""){
			$($(this).parent()).ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "upload_report",
					action : "stage5-1_update"
				},
				error : function(){
					alert("【系統】網路連線發生問題！上傳失敗！");
					return;
				},
				success : function(data){
					window.location.reload();
				}
			});
		}
	});
/*----------------------------------繳交審核表：5-1----------------------------------*/
	$("#research_complete_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 5;				// 檢核表的題數
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
						action : "stage5-1_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data){
						alert("【系統】研究作品報告書審核表送出成功！");
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-1'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(審核表)
			$display2 = '';					// 消失(審核表)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<h1 id="title">統整作品報告</h1>
		<p>在進行資料分析與繪製圖表的過程中，請點選下列閱讀教材以供參考，根據實驗的紀錄進行資料分析和圖表繪製，並上傳分析後的報告。</p>
		<p class="steps_tips">[提醒：將所有分析的資料和圖表整理成一份文件，只會紀錄最後上傳的文件。]</p>
		<?php
			$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
			$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
			while($t_row = mysql_fetch_array($t_qry)){
				echo "<h3>研究題目：".$t_row['topic']."</h3>";
			}
		?>
		<table class="research_report_table">
			<tr>
				<th width="25%">章節</th>
				<th width="75%">說明</th>
			</tr>
			<tr>
				<td>封面</td>
				<td>引人入勝的封面一定可以讓讀者心情愉悅。</td>
			</tr>
			<tr>
				<td>摘要</td>
				<td>對你的科展做簡略說明，引起他人的興趣。</td>
			</tr>
			<tr>
				<td>目錄</td>
				<td>說明書的內容有哪些？標註各個章節的頁碼。</td>
			</tr>
			<tr>
				<td>研究動機</td>
				<td>為什麼想要研究這個主題？</td>
			</tr>
			<tr>
				<td>研究問題</td>
				<td>你找出了哪些值得研究的問題？</td>
			</tr>
			<tr>
				<td>研究器材與設備</td>
				<td>你使用了哪些器材協助你做實驗和觀察？</td>
			</tr>
			<tr>
				<td>研究過程與方法</td>
				<td>你如何進行實驗或調查？</td>
			</tr>
			<tr>
				<td>實驗結果</td>
				<td>簡單陳述實驗的結果。</td>
			</tr>
			<tr>
				<td>研究討論</td>
				<td>對實驗結果進一步的分析和解說。</td>
			</tr>
			<tr>
				<td>結論</td>
				<td>簡述你的假設是否有被實驗結果證實，闡述未來的研究方向。</td>
			</tr>
			<tr>
				<td>參考資料</td>
				<td>你的資訊來源有哪些？</td>
			</tr>
		</table>
		<form>
			上傳作品報告書：<input type="file" name="files" class="research_files" tabindex="1">
			<?php
				$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					echo "<span class='steps_mark'>[已上傳]</span>";
				}else{
					echo "<span class='steps_hint'>[尚未上傳]</span>";
				}
			?>
		</form>
		<?php
			if($_SESSION['chief'] == '1'){ // 組長
				$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					echo "<button class='steps_submit' id='research_complete_submit'>繳交作業</button>";
				}else{
					echo "<button class='steps_submit' id='research_complete_submit' disabled>繳交作業</button>";
				}
			}
		?>
	<!-- 	<div class="steps_menu">
			<ul>
				<li class='has-sub active'><a href='#'>討論作品報告書</a>
					<ul>
						<li class='sub-sub'><a href='#'>
							<div class="report_btn">
								<div class='research_view_result'><img src='../../model/images/project_rewrite.png' width='55px'><br />觀看研究結果</div>
								<div class='research_view_discuss'><img src='../../model/images/project_discuss.png' width='55px'><br />觀看研究討論</div>
								<div class='research_view_conclusion'><img src='../../model/images/project_review.png' width='55px'><br />觀看研究結論</div>
							</div>
							<hr width="100%">
							<table class='report_table' border='1' cellspacing='0'>
								<tr>
									<th width='15%'>1</th>
									<td width='75%'>參考資料</td>
									<td width='12%'><center>
										<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'><br />進入討論</div>
									</center></td>
								</tr>
							</table>
							<table class='report_table' border='1' cellspacing='0'>
								<tr>
									<th width='15%'>2</th>
									<td width='75%'>摘要</td>
									<td width='12%'><center>
										<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'><br />進入討論</div>
									</center></td>
								</tr>
							</table>
							<table class='report_table' border='1' cellspacing='0'>
								<tr>
									<th width='15%'>3</th>
									<td width='75%'>研究目的與動機</td>
									<td width='12%'><center>
										<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'><br />進入討論</div>
									</center></td>
								</tr>
							</table>
							<table class='report_table' border='1' cellspacing='0'>
								<tr>
									<th width='15%'>4</th>
									<td width='75%'>研究問題</td>
									<td width='12%'><center>
										<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='50px'><br />進入討論</div>
									</center></td>
								</tr>
							</table>
							<table class='report_table_new' border='1' cellspacing='0'>
								<tr>
									<td>+ 開始討論作品報告書</td>
								</tr>
							</table>
						</a></li>
					</ul>
				</li>
				<li class='has-sub'><a href='#'>統整作品報告書</a>
					<ul>
						<li class='sub-sub'><a href='#'>
							<?php
								$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
								$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
								while($t_row = mysql_fetch_array($t_qry)){
									echo "<h3>研究題目：".$t_row['topic']."</h3>";
								}
							?>
							<table class="research_report_table">
								<tr>
									<th width="25%">章節</th>
									<th width="75%">說明</th>
								</tr>
								<tr>
									<td>封面</td>
									<td>引人入勝的封面一定可以讓讀者心情愉悅。</td>
								</tr>
								<tr>
									<td>摘要</td>
									<td>對你的科展做簡略說明，引起他人的興趣。</td>
								</tr>
								<tr>
									<td>目錄</td>
									<td>說明書的內容有哪些？標註各個章節的頁碼。</td>
								</tr>
								<tr>
									<td>研究動機</td>
									<td>為什麼想要研究這個主題？</td>
								</tr>
								<tr>
									<td>研究問題</td>
									<td>你找出了哪些值得研究的問題？</td>
								</tr>
								<tr>
									<td>研究器材與設備</td>
									<td>你使用了哪些器材協助你做實驗和觀察？</td>
								</tr>
								<tr>
									<td>研究過程與方法</td>
									<td>你如何進行實驗或調查？</td>
								</tr>
								<tr>
									<td>實驗結果</td>
									<td>簡單陳述實驗的結果。</td>
								</tr>
								<tr>
									<td>研究討論</td>
									<td>對實驗結果進一步的分析和解說。</td>
								</tr>
								<tr>
									<td>結論</td>
									<td>簡述你的假設是否有被實驗結果證實，闡述未來的研究方向。</td>
								</tr>
								<tr>
									<td>參考資料</td>
									<td>你的資訊來源有哪些？</td>
								</tr>
							</table>
							<form>
								上傳作品報告書：<input type="file" name="files" class="research_files" tabindex="1">
							</form>
							<button class="steps_next">繳交作業</button>
						</a></li>
					</ul>
				</li>
			</ul>
		</div> -->
		<div class="fancybox_box" id="research_result_fancybox">
			<div class="fancybox_area" id="research_result_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究結果 -</h2>
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
				<h2>- 觀看研究結論 -</h2>
				<?php
					$num = 1;

					$sql = "SELECT * FROM `research_conclusion` WHERE `p_id`= '".$_SESSION['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<table class='conclusion_table' border='1' cellspacing='0'>
									<tr id='".$row['c_id']."'>
										<th width='20%'>".$num."</th>
										<td width='80%'>".$row['content']."</td>
									</tr>
								</table>";
							  $num++;
						}
					}
				?>
				<input type="button" class="research_next" value="確定">
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 5-1《統整作品報告書》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 摘要是否包含：簡介、研究問題、研究方法、結果、結論？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 摘要有沒有引起讀者對科展的興趣？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 有沒有符合作品說明書的文字及標題格式？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 作品說明書中有沒有錯別字？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 作品說明書有沒有包含以下幾個章節？
								<div>- 標題頁</div>
								<div>- 摘要</div>
								<div>- 目錄</div>
								<div>- 研究問題、變因和假設</div>
								<div>- 材料清單</div>
								<div>- 實驗計畫</div>
								<div>- 實驗結果</div>
								<div>- 實驗結果</div>
								<div>- 結論(包含未來研究的建議)</div>
								<div>- 參考書目</div>
							</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
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