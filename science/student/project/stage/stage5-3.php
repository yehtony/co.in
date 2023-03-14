<style>
/*---------------------------------小組工作室：5-3----------------------------------*/
.report_box_new{
	height: 45px;
	padding-top: 15px;
	font-size: 26px;
	font-weight: bolder;
	color: #1C90FD;
	text-align: center;
	border: 1px solid #000000;
	cursor: pointer;
}
.report_box_new img{
	vertical-align: top;						/*-----------圖文對齊-----------*/
}
/*--------------------------------上傳報告影片：5-3---------------------------------*/
#research_new_area{
	width: 25%;
}
#research_new_area textarea{
	width: 75%;
	height: 120px;
	resize: none; 									/*------------不可拉縮------------*/
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：5-3----------------------------------*/
	$(".report_box_new").click(function(){
		$("#research_new_fancybox").show();
	});
	$("#research_add").click(function(){
		if($("#research_new_form textarea").val() == ""){
			alert("【系統】尚未選擇上傳影片。");
		}else{
			$("#research_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_video",
					action : "stage5-3_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】影片上傳成功！");
					window.location.reload();
				}
			});
		}
	});
/*----------------------------------繳交審核表：5-3----------------------------------*/
	$("#research_vedio_submit").click(function(){
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
			alert("【系統】請重新確認上傳影片是否合格。");
		}else{
			var x = confirm("【系統】確定送出影片後，就無法修改囉？");
			if(x){
				$("#research_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_check_video",
						check_num : checknum,
						action : "stage5-3_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data) {
						alert("【系統】報告影片繳交成功！");
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
		// 是否已上傳出現
		$block1 = 'display: block;';		// 顯示(初始)
		$block2 = 'display: none;';			// 隱藏(初始)

		$b_sql = "SELECT `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."'";
		$b_qry = mysql_query($b_sql, $link) or die(mysql_error());
		if(mysql_num_rows($b_qry) > 0){
			$block1 = 'display: none;';		// 隱藏(已上傳)
			$block2 = 'display: block;';	// 顯示(已上傳)
		}
			mysql_query($b_sql, $link) or die(mysql_error());

		// 是否已繳交審核表
		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-3'";
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
				$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					echo "<button class='steps_submit' id='research_vedio_submit'>繳交作業</button>";
				}else{
					echo "<button class='steps_submit' id='research_vedio_submit' disabled>繳交作業</button>";
				}
			}
		?>
		<h1 id="title">錄製報告影片</h1>
		<p>請你錄製5分鐘以內的報告內容，來排練科展報告；記得要盡量應用海報及實驗器材來輔助說明。 </p>
		<p class="steps_tips">[提醒：請上傳至<span onClick="https://www.youtube.com/?gl=TW">youtube</span>，再內嵌至本系統。]</p>
		<p class="steps_tips">[提醒：在youtube上有"分享"->"嵌入"，複製貼上嵌入的語法即可。]</p>
		<div class="steps_menu">
			<ul>
				<li class='has-sub active'><a href='#'>上傳報告影片</a>
					<ul style="<?php echo $block1; ?>">
						<li class='sub-sub'><a href='#'>
						<?php
							$sql = "SELECT `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<div class='report_box_new'>
											<img src='../../model/images/project_add.png' width='30px'>更新報告影片
										  </div>";
								}
							}else{
								echo "<div class='report_box_new'>
										<img src='../../model/images/project_add.png' width='30px'>上傳報告影片
									  </div>";
							}
						?>
						</a></li>
					</ul>
				</li>
				<li class='has-sub'><a href='#'>觀看報告影片</a>
					<ul style="<?php echo $block2; ?>">
						<li class='sub-sub'><a href='#'>
						<?php
							$sql = "SELECT `fileurl` FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage`= '".$_GET['stage']."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<center>".$row['fileurl']."</center>";
								}
							}
						?>
						</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="fancybox_box" id="research_new_fancybox">
			<div class="fancybox_area" id="research_new_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 上傳報告影片 -</h2>
				<form id="research_new_form">
					<label>嵌入：</label>
					<textarea name="research_vedio" placeholder="例如：<iframe width='640' height='360' src='https://www.youtube.com/embed/R8lEukwIdyo' frameborder='0' allowfullscreen></iframe>"></textarea>
					<input type="button" class="fancybox_btn" id="research_add" value="上傳影片">
				</form>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 5-3《錄製報告影片》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 你有在影片中提到這次的研究動機嗎？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 你有提到怎麼進行實驗的嗎？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 你有提到實驗的結果和你們的結論嗎？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 你有提到你們的研究對社會的幫助嗎？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 你有展示你們用於研究的基礎理論嗎？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 你有準備好評審可能會問的「問題清單」嗎？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 你有充分練習回答問題的技巧嗎？</td>
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