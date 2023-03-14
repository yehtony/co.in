<style>
/*---------------------------------小組工作室：5-2----------------------------------*/
.research_field{
	float: right;
	width: 35%;
}
.research_upload_table td{
	width: 250px;
	padding: 15px;
	text-align: center;
	border: 1px #000000 solid;
}
.research_upload_table button{
	color: blue;
	cursor: pointer;
}
.research_upload_table hr{
	border: 0;
	border-bottom: 1px dashed #ccc;
	background-color: #999;
}
.research_upload_table h3{
	margin: 0px;
}
.research_review_table td{
	text-align: center;
	border: 1px #000000 solid;
}
.research_files{
	width: 70px;
	height: 30px;
	cursor: pointer;
}
/*----------------------------------繳交作業：5-2-----------------------------------*/
#research_check_area{
	width: 45%;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：5-2----------------------------------*/
	$(".research_files").change(function(){
		var realfile = $(this).val();				// 檔案實際位置
		var order = $(this).attr('title');			// 檔案標號

		if(realfile != ""){
			$($(this).parent()).ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "upload_report",
					order : order,
					action : "stage5-2_update"
				},
				error : function(){
					alert("【系統】檔案類別錯誤，請上傳檔案類型(.ppt/.pptx)。");
					return;
				},
				success : function(data){
					window.location.reload();
				}
			});
		}
	});
/*----------------------------------繳交審核表：5-2----------------------------------*/
	$("#research_report_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	$("#research_check_submit").click(function(){
		var checknum = 7;				// 檢核表的題數
		var checklist = [];				// 檢核表的答案
		var check = "true";				// 是否全檢核過
		$("#research_check_form input:radio:checked").each(function(){	// 將檢核表的答案存入checkList
			checklist.push($(this).val());

			if($(this).val() != '1'){
				check = "false";
			}
		});
		if(checklist.length < checknum){
			alert("【系統】檢核表尚未填寫完成。");
		}else if(check == "false"){
			alert("【系統】請重新確認上傳海報是否合格。");
		}else{
			var x = confirm("【系統】確定送出海報後，就無法修改囉？");
			if(x){
				$("#research_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_research",
						check_num : checknum,
						action : "stage5-2_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data) {
						alert("【系統】作品海報繳交成功！");
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

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-2'";
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
				$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) == 4){
					echo "<button class='steps_submit' id='research_report_submit'>繳交作業</button>";
				}else{
					echo "<button class='steps_submit' id='research_report_submit' disabled>繳交作業</button>";
				}
			}
		?>
		<h1 id="title">完成作品海報</h1>
		<p>請依下列說明先檢視自己的海報有沒有符合規定，在製作完成之後，由組長完成上傳動作，並提交給教師審核。</p>
		<p class="steps_tips">[作品海報範例上、左、右、中。]</p>
		<button><a target="_blank" href="/co.in/science/model/download/作品海報範例.docx">作品海報範例</a></button>
		<fieldset class="research_field">
			<p>你的看板應包含：</p>
			<ol>
				<li>標題</li>
				<li>摘要</li>
				<li>研究問題</li>
				<li>變因和假設</li>
				<li>材料清單</li>
				<li>實驗計畫</li>
				<li>資料分析和討論(包含統計圖表)</li>
				<li>結論(包含未來研究的建議)</li>
				<li>參考書目</li>
			</ol>
		</fieldset>
		<p>海報有沒有進行適當的排版，讓其他人容易看得懂嗎？</p>
		<p>你的字體夠大嗎？(至少16字體)</p>
		<p>標題能不能吸引人，標題的字體能不能從教室的另一端看得見呢？</p>
		<p>你有沒有使用照片及圖表來傳達你的研究結果呢？</p>
		<p>你有沒有盡可能簡潔地架構你的科展海報？</p>
		<p>你有沒有校對你的科展海報，以免出現錯字或將內容放錯地方？</p>
		<p>你有沒有依照科展的規定來設計科展海報？</p>
		<div class="steps_menu">
			<ul>
				<li class='has-sub active'><a href='#'>上傳作品海報</a>
					<ul>
						<li class='sub-sub'><a href='#'>
						<table class="research_upload_table">
							<tr>
								<td style="border: 0px;"></td>
								<td>
									<h3>上</h3>
									<button onclick="javascript:location.href='/co.in/science/model/download/top.ppt'">[參考版型]</button><br />
								<hr/>
									<?php
										$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '0'";
										$qry = mysql_query($sql, $link) or die(mysql_error());
										if(mysql_num_rows($qry) > 0){
											echo "<span class='steps_mark'>[已上傳]</span>";
										}else{
											echo "<span class='steps_hint'>[尚未上傳]</span>";
										}
									?>
									<form>
										<input type="file" name="files" class="research_files" title="0" tabindex="1">
									</form>
								</td>
								<td style="border: 0px;"></td>
							</tr>
							<tr>
								<td>
									<h3>左</h3>
									<button onclick="javascript:location.href='/co.in/science/model/download/left.ppt'">[參考版型]</button><br />
								<hr/>
									<?php
										$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '1'";
										$qry = mysql_query($sql, $link) or die(mysql_error());
										if(mysql_num_rows($qry) > 0){
											echo "<span class='steps_mark'>[已上傳]</span>";
										}else{
											echo "<span class='steps_hint'>[尚未上傳]</span>";
										}
									?>
									<form>
										<input type="file" name="files" class="research_files" title="1" tabindex="2">
									</form>
								</td>
								<td>
									<h3>中</h3>
									<button onclick="javascript:location.href='/co.in/science/model/download/middle.ppt'">[參考版型]</button><br />
								<hr/>
									<?php
										$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '2'";
										$qry = mysql_query($sql, $link) or die(mysql_error());
										if(mysql_num_rows($qry) > 0){
											echo "<span class='steps_mark'>[已上傳]</span>";
										}else{
											echo "<span class='steps_hint'>[尚未上傳]</span>";
										}
									?>
									<form>
										<input type="file" name="files" class="research_files" title="2" tabindex="3">
									</form>
								</td>
								<td>
									<h3>右</h3>
									<button onclick="javascript:location.href='/co.in/science/model/download/right.ppt'">[參考版型]</button><br />
								<hr/>
									<?php
										$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '3'";
										$qry = mysql_query($sql, $link) or die(mysql_error());
										if(mysql_num_rows($qry) > 0){
											echo "<span class='steps_mark'>[已上傳]</span>";
										}else{
											echo "<span class='steps_hint'>[尚未上傳]</span>";
										}
									?>
									<form>
										<input type="file" name="files" class="research_files" title="3" tabindex="4">
									</form>
								</td>
							</tr>
						</table>
						</a></li>
					</ul>
				</li>
				<li class='has-sub'><a href='#'>觀看作品海報</a>
					<ul>
						<li class='sub-sub'><a href='#'>
						<table class="research_review_table">
							<tr>
								<?php
									$sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."' AND `order`= '0'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										while($row = mysql_fetch_array($qry)){
											echo "<td style='border: 0px;'></td><td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189".$row['fileurl']."&embedded=true' style='width: 265px; height: 90px;' frameborder='0'></iframe></td><td style='border: 0px;'></td>";
										}
									}else{
										echo "<td style='border: 0px;'></td><td width='30%'>尚未上傳檔案</td><td style='border: 0px;'></td>";
									}
								?>
							</tr>
							<tr>
								<?php
									$sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."' AND `order`= '1'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										while($row = mysql_fetch_array($qry)){
											echo "<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189".$row['fileurl']."&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>";
										}
									}else{
										echo "<td width='30%'>尚未上傳檔案</td>";
									}
								?>
								<?php
									$sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."' AND `order`= '2'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										while($row = mysql_fetch_array($qry)){
											echo "<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189".$row['fileurl']."&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>";
										}
									}else{
										echo "<td width='30%'>尚未上傳檔案</td>";
									}
								?>
								<?php
									$sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."' AND `order`= '3'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										while($row = mysql_fetch_array($qry)){
											echo "<td><iframe src='http://docs.google.com/gview?url=http://140.115.126.189".$row['fileurl']."&embedded=true' style='width: 265px; height: 470px;' frameborder='0'></iframe></td>";
										}
									}else{
										echo "<td width='30%'>尚未上傳檔案</td>";
									}
								?>
							</tr>
						</table>
						</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 5-2《製作作品海報》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 看板包含：
								<div> (1) 標題</div>
								<div> (2) 摘要</div>
								<div> (3) 研究問題</div>
								<div> (4) 變因和假設</div>
								<div> (5) 材料清單</div>
								<div> (6) 實驗計畫</div>
								<div> (7) 資料分析和討論(包含統計圖表)</div>
								<div> (8) 結論(包含未來研究的建議)</div>
								<div> (9) 參考書目</div>
							</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 海報有沒有進行適當的排版，讓其他人容易看得懂嗎？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 字體夠大嗎？(至少16字體)</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 標題能不能吸引人，標題的字體能不能從教室的另一端看得見呢？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 我們有沒有使用照片及圖表來傳達我們的研究結果呢？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 有沒有盡可能簡潔地架構科展看板？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 有沒有依照科展的規定來設計科展看板？</td>
							<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
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