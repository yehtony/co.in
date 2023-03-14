<?php
	$page_url = '<a href="index.php">專題指導</a> > 教師日誌';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------教師日誌-------------------------------------*/
.diary_box{
	margin: 10px 20px 10px 120px;
	padding: 40px 20px;
	width: 28%;
	height: 110px;
	text-align: center;
	border: 1px solid #000000;
	display: inline-table;
	cursor: pointer;
}
.diary_box:hover{
	background-color: #eee;
}
.diary_table{
	margin: 20px;
	width: 90%;
	text-align: center;
}
.diary_table th{
	font-size: 20px;
	line-height: 45px;
	color: white;
	background-color: #63ADF8;
}
.diary_table td{
	line-height: 30px;
}
.diary_table tr:nth-child(odd){
	background-color:#99CCFF;
}
/*----------------------------------教師日誌：詳情----------------------------------*/
.fancybox_area{
	width: 50%;
}
.fancybox_area textarea{
	width: 95%;
	height: 100px;
	resize: none;
}
.diary_reflect_page{
	display: none;
}
#diary_guide_check, #diary_reflect_check{
	display: none;
}
</style>
<script>
$(function(){
/*------------------------------教師日誌：新增指導日誌------------------------------*/
	$("#diary_guide_box").click(function(){
		// 初始化
		$("#diary_guide_area h2").text("- 新增指導日誌 -");
		$('#diary_guide_form input[type=date]').val("");
		$('#diary_guide_form select').val("default");
		$('#diary_guide_form textarea').val("");

		$("#diary_guide_check").hide();
		$("#diary_guide_add").show();

		$("#diary_guide_fancybox").show();
	});
	$("#diary_guide_add").click(function(){
		if($("#diary_guide_form input[type=date]").val() == ""){
			alert("【系統】請選擇撰寫時間。");
		}else if($("#diary_guide_form select").val() == "default"){
			alert("【系統】請選擇小組名稱和指導方式。");
		}else if($("#diary_guide_form textarea").val() == ""){
			alert("【系統】請填寫完整日誌。");
		}else{
			$("#diary_guide_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_guide_diary",
					action : "diary_update"
				},
				error : function(){
					alert("【警告】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】指導日誌新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$("#diary_guide_check").click(function(){
		$("#diary_guide_fancybox").hide();
	});
/*------------------------------教師日誌：新增反思日誌------------------------------*/
	$("#diary_reflect_box").click(function(){
		$("#diary_reflect_area h2").text("- 新增反思日誌 -");
		$('#diary_reflect_form select').val("default");
		$('#diary_reflect_form textarea').val("");

		$(".diary_reflect_page").hide();

		$("#diary_reflect_check").hide();
		$("#diary_reflect_add").show();

		$("#diary_reflect_fancybox").show();
	});
	$("select[name=diary_reflect_category]").change(function(){
		var diary_category = $(this).val();
		// console.log(diary_category);
		if(diary_category == 'default'){
			$(".diary_reflect_page").hide();
		}else{
			$(".diary_reflect_page").hide();
			$("#diary_reflect_page"+ diary_category).show();
		}
	});
	$("#diary_reflect_add").click(function(){
		if($("#diary_reflect_form select[name=diary_reflect_pname]").val() == "default" || $("#diary_reflect_form select[name=diary_reflect_category]").val() == "default"){
			alert("【系統】請選擇小組名稱和反思類型。");
		}else{
			$("#diary_reflect_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_reflect_diary",
					action : "diary_update"
				},
				error : function(){
					alert("【警告】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】反思日誌新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$("#diary_reflect_check").click(function(){
		$("#diary_reflect_fancybox").hide();
	});
/*-------------------------------教師日誌：分類[小組]-------------------------------*/
	$("select[name='diary_project']").change(function(){
		var diary_project = $(this).val();
		// console.log(diary_project);
		if(diary_project == 'default'){
			$(".diary_table_tr ~ tr").show();
		}else{
			$(".diary_table_tr ~ tr").hide();
			$("tr[id='project"+ diary_project +"']").show();
		}
	});
/*-------------------------------教師日誌：分類[種類]-------------------------------*/
	$("select[name='diary_type']").change(function(){
		var diary_type = $(this).val();
		// console.log(diary_type);
		switch(diary_type){
			case 'default':
				$(".diary_table_tr ~ tr").show();
				break;
			case '0':
				$(".diary_table_tr ~ tr").hide();
				$("tr[class='type0']").show();
				break;
			case '1':
				$(".diary_table_tr ~ tr").hide();
				$("tr[class='type1']").show();
				break;
			default:
				break;
		}
	});
/*----------------------------------教師日誌：詳情----------------------------------*/
	$(".diary_detail").click(function(){
		var diary_id = $(this).attr('value');			// 日誌ID
		var type = $(this).attr('id');					// 種類 0:指導 1:反思
		// console.log(diary_id, type);
		if(type == '0'){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data: {
					type : 'view_guide_diary',
					diary_id : diary_id,
					action : 'diary_update'
				},
				error: function(){
					alert("【警告】讀取失敗，請檢查網絡連線問題。");
					return;
				},
				success: function(data){
					for(var a in data){
						$("#diary_guide_area h2").text("- 詳情指導日誌 -");
						$('input[name=diary_guide_date]').val(data[a].diary_date);
						$('select[name=diary_guide_pname]').val(data[a].diary_pid);
						$('select[name=diary_guide_category]').val(data[a].diary_category);
						$('textarea[name=diary_guide_event]').html(data[a].diary_content1);
						$('textarea[name=diary_guide_result]').html(data[a].diary_content2);
						$('textarea[name=diary_guide_remark]').html(data[a].diary_content3);

						$("#diary_guide_add").hide();
						$("#diary_guide_check").show();
						
						$("#diary_guide_fancybox").show();
					}
				}
			});
		}else{
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data: {
					type : 'view_reflect_diary',
					diary_id : diary_id,
					action : 'diary_update'
				},
				error: function(){
					alert("【警告】讀取失敗，請檢查網絡連線問題。");
					return;
				},
				success: function(data){
					for(var a in data){
						$("#diary_reflect_area h2").text("- 詳情反思日誌 -");
						$('select[name=diary_reflect_category]').val(data[a].diary_category);
						$('select[name=diary_reflect_pname]').val(data[a].diary_pid);

						if(data[a].diary_category == 0){
							$('textarea[name=diary_reflect_content01]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content02]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content03]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content04]').html(data[a].diary_content4);
							$('textarea[name=diary_reflect_content05]').html(data[a].diary_content5);
							$('textarea[name=diary_reflect_content06]').html(data[a].diary_content6);
						}else if(data[a].diary_category == 1){
							$('textarea[name=diary_reflect_content11]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content12]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content13]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content14]').html(data[a].diary_content4);
						}else if(data[a].diary_category == 2){
							$('textarea[name=diary_reflect_content21]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content22]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content23]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content24]').html(data[a].diary_content4);
						}else if(data[a].diary_category == 3){
							$('textarea[name=diary_reflect_content31]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content32]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content33]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content34]').html(data[a].diary_content4);
						}else if(data[a].diary_category == 4){
							$('textarea[name=diary_reflect_content41]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content42]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content43]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content44]').html(data[a].diary_content4);
						}else if(data[a].diary_category == 5){
							$('textarea[name=diary_reflect_content51]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content52]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content53]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content54]').html(data[a].diary_content4);
						}else if(data[a].diary_category == 6){
							$('textarea[name=diary_reflect_content61]').html(data[a].diary_content1);
							$('textarea[name=diary_reflect_content62]').html(data[a].diary_content2);
							$('textarea[name=diary_reflect_content63]').html(data[a].diary_content3);
							$('textarea[name=diary_reflect_content64]').html(data[a].diary_content4);
							$('textarea[name=diary_reflect_content65]').html(data[a].diary_content5);
						}

						$("#diary_reflect_page" + data[a].diary_category).show();

						$("#diary_reflect_fancybox").show();
					}
				}
			});
		}
	});
/*----------------------------------教師日誌：詳情----------------------------------*/
	$(".diary_delete").click(function(){
		var diary_id = $(this).attr('value');			// 日誌ID
		console.log(diary_id);
		var x = confirm("【系統】確定刪除此日誌後，就無法修改囉？");
		if(x){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "delete_diary",
					diary_id : diary_id,
					action : "diary_update"
				},
				error : function(){
					alert("【系統】刪除失敗，請檢查網絡連線問題。");
					return;
				},
				success : function(data){
					alert("【系統】教師日誌已刪除！");
					window.location.reload();
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<div class="diary_box log_btn" id="diary_guide_box" value="教學指導日誌">
		<h3>新增教學指導日誌</h3>
		<p>指導日誌紀錄與專題小組的討論時間、指導方式，以便紀錄其指導事項、結果與註記事項</p>
	</div>
	<div class="diary_box log_btn" id="diary_reflect_box" value="教師反思日誌">
		<h3>新增教師反思日誌</h3>
		<p>反思日誌在不同階段形成不同的反思心得，根據反思階段指引撰寫且紀錄相關心得</p>
	</div>
	<p>篩選：
		<select name="diary_project">
			<option value='default'>全部的小組</option>
			<?php
				$sql = "SELECT * FROM `project` WHERE `t_m_id` = '".$_SESSION['UID']."' OR `t_s_id` = '".$_SESSION['UID']."' ";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				while($row = mysql_fetch_array($qry)){
					echo "<option value=".$row['p_id'].">".$row['pname']."</option>";
				}
			?>
		</select>
		<select name="diary_type">
			<option value='default'>所有日誌</option>
			<option value='0'>指導日誌</option>
			<option value='1'>反思日誌</option>
		</select>
	</p>
	<table class="diary_table">
		<tr class="diary_table_tr">
			<th width="10%">日期</th>
			<th width="5%">類型</th>
			<th width="20%">小組名稱(研究題目)</th>
			<th width="10%">詳情</th>
		</tr>
		<?php
			$sql = "SELECT * FROM `diary` WHERE `u_id` = '".$_SESSION['UID']."' ORDER BY `date` DESC";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					// 日誌類型--------------------------------------------------------------
					if($row['type'] == '0'){
						$type = '指導日誌';
					}else if($row['type'] == '1'){
						$type = '反思日誌';
					}
					// 抓取小組名稱----------------------------------------------------------------
					$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
					$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
					$p_row = mysql_fetch_array($p_qry);
						$pname = $p_row['pname'];
					// 抓取小組名稱----------------------------------------------------------------
					$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id` = '".$row['p_id']."'";
					$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
					$t_row = mysql_fetch_array($t_qry);
						$topic = $t_row['topic'];

					echo "<tr id='project".$row['p_id']."' class='type".$row['type']."'>".
							"<td>".$row['date']."</td>".
							"<td>".$type."</td>".
							"<td>".$pname."(".$topic.")</td>".
							"<td>
							  	<button class='diary_detail' id=".$row['type']." value=".$row['d_id'].">詳情</button>
							  	<button class='diary_delete' value=".$row['d_id'].">刪除</button>
							 </td>".
						 "</tr>";
				}
			}else{
				echo "<tr><td colspan='4'>尚未撰寫任何日誌。</td></tr>";
			}
		?>
	</table>
	<div class="fancybox_box" id="diary_guide_fancybox">
		<div class="fancybox_area" id="diary_guide_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新增指導日誌 -</h2>
			<form id="diary_guide_form" method="post">
				<p>
					<label>時間：</label>
					<input type="date" name="diary_guide_date">
				</p>
				<p>
					<label>小組名稱：</label>
					<select name="diary_guide_pname">
						<option value="default">請選擇小組...</option>
						<?php
							$sql = "SELECT `p_id`, `pname` FROM `project` WHERE (`t_m_id` = '".$_SESSION['UID']."' OR `t_s_id` = '".$_SESSION['UID']."') AND `state` = '0'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['p_id']."'>".$row['pname']."</option>";
								}
							}
							mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<p>
					<label>指導方式：</label>	
					<select name="diary_guide_category">
						<option value="default">請選擇類型...</option>
						<option value="0">線上指導</option>
						<option value="1">面對面指導</option>
					</select>
				</p>
				<p>
					<label>指導事項：</label>	
					<textarea name="diary_guide_event" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>結果：</label>	
					<textarea name="diary_guide_result" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>註記（包含學生特殊狀況、教師個人省思或其他事項）：</label>	
					<textarea name="diary_guide_remark" placeholder="請填寫..."></textarea>
				</p>
				<input type="button" class="fancybox_btn" id="diary_guide_add" value="儲存日誌">
				<input type="button" class="fancybox_btn" id="diary_guide_check" value="確定">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_reflect_fancybox">
		<div class="fancybox_area" id="diary_reflect_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新增反思日誌 -</h2>
			<form id="diary_reflect_form" method="post">
				<p>
					<label>反思日誌：</label>	
					<select name="diary_reflect_category">
						<option value="default">請選擇階段...</option>
						<option value="0">開始專題前</option>
						<option value="1">形成問題階段</option>
						<option value="2">設計計畫階段</option>
						<option value="3">執行計畫階段</option>
						<option value="4">形成結論階段</option>
						<option value="5">報告與展示階段</option>
						<option value="6">完成專題後</option>
					</select>
				</p>
				<p>
					<label>小組名稱：</label>
					<select name="diary_reflect_pname">
						<option value="default">請選擇小組...</option>
						<?php
							$sql = "SELECT `p_id`, `pname` FROM `project` WHERE (`t_m_id` = '".$_SESSION['UID']."' OR `t_s_id` = '".$_SESSION['UID']."') AND `state` = '0'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['p_id']."'>".$row['pname']."</option>";
								}
							}
							mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<div class="diary_reflect_page" id="diary_reflect_page0">
					<p>
						<label>過去你有沒有指導學生進行科展的經驗呢？</label>	
						<textarea name="diary_reflect_content01" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>你覺得在指導學生完成科展的過程中，哪些是特別重要的？</label>	
						<textarea name="diary_reflect_content02" placeholder="請填寫..."></textarea>
					</p>
					<p>
						<label>你覺得在指導學生完成科展的過程中，學生可以學習到哪些事情？</label>	
						<textarea name="diary_reflect_content03" placeholder="請填寫..."></textarea>
					</p>
					<p>
						<label>你覺得科展整個流程應該如何進行呢？</label>	
						<textarea name="diary_reflect_content04" placeholder="請填寫..."></textarea>
					</p>
					<p>
						<label>你覺得對於指導學生完成科展的信心程度為何？為什麼？(如果是0－100，請為自己的信心程度打分數。)</label>	
						<textarea name="diary_reflect_content05" placeholder="請填寫..."></textarea>
					</p>
					<p>
						<label>你覺得指導學生完成科展的過程中可能遇到哪些困難呢？</label>	
						<textarea name="diary_reflect_content06" placeholder="請填寫..."></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page1">
					<p>
						<label>在指導學生找到科展題目的過程中，你是如何進行？請詳細描述整個過程。此外，你所遇到的困難有哪些？</label>	
						<textarea name="diary_reflect_content11" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生找到科展題目的過程中，你覺得哪些部分是你需要再改進的？請詳述。如果再一次帶學生做科展，你覺得在這個階段你應該要怎麼做？</label>	
						<textarea name="diary_reflect_content12" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生找到科展題目的過程中，使用本系統輔助和沒有使用系統的差異性有哪些？系統提供哪些功能幫助你進行這個階段？哪些功能是不一定需要的？請詳述。</label>	
						<textarea name="diary_reflect_content13" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生找到科展題目的過程中，希望系統還可以提供哪些功能幫助你？請詳述。</label>
						<textarea name="diary_reflect_content14" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page2">
					<p>
						<label>在指導學生規劃實驗的過程中，你是如何進行？請詳細描述整個過程。此外，你所遇到的困難有哪些？</label>	
						<textarea name="diary_reflect_content21" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生規劃實驗的過程中，你覺得哪些部分是你需要再改進的？請詳述。如果再一次帶學生做科展，你覺得在這個階段你應該要怎麼做？</label>	
						<textarea name="diary_reflect_content22" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生規劃實驗的過程中，使用本系統輔助和沒有使用系統的差異性有哪些？系統提供哪些功能幫助你進行這個階段？哪些功能是不一定需要的？請詳述。</label>	
						<textarea name="diary_reflect_content23" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生規劃實驗的過程中，希望系統還可以提供哪些功能幫助你？請詳述。</label>	
						<textarea name="diary_reflect_content24" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page3">
					<p>
						<label>在指導學生執行實驗和紀錄的過程中，你是如何進行？請詳細描述整個過程。此外，你所遇到的困難有哪些？</label>	
						<textarea name="diary_reflect_content31" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生執行實驗和紀錄的過程中，你覺得哪些部分是你需要再改進的？請詳述。如果再一次帶學生做科展，你覺得在這個階段你應該要怎麼做？</label>	
						<textarea name="diary_reflect_content32" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生執行實驗和紀錄的過程中，使用本系統輔助和沒有使用系統的差異性有哪些？系統提供哪些功能幫助你進行這個階段？哪些功能是不一定需要的？請詳述。</label>	
						<textarea name="diary_reflect_content33" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生執行實驗和紀錄的過程中，希望系統還可以提供哪些功能幫助你？請詳述。</label>	
						<textarea name="diary_reflect_content34" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page4">
					<p>
						<label>在指導學生形成實驗結論的過程中，你是如何進行？請詳細描述整個過程。此外，你所遇到的困難有哪些？</label>	
						<textarea name="diary_reflect_content41" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生形成實驗結論的過程中，你覺得哪些部分是你需要再改進的？請詳述。如果再一次帶學生做科展，你覺得在這個階段你應該要怎麼做？</label>	
						<textarea name="diary_reflect_content42" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生形成實驗結論的過程中，使用本系統輔助和沒有使用系統的差異性有哪些？系統提供哪些功能幫助你進行這個階段？哪些功能是不一定需要的？請詳述。</label>	
						<textarea name="diary_reflect_content43" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生形成實驗結論的過程中，希望系統還可以提供哪些功能幫助你？請詳述。</label>
						<textarea name="diary_reflect_content44" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page5">
					<p>
						<label>在指導學生製作成果報告、海報及影片的過程中，你是如何進行？請詳細描述整個過程。此外，你所遇到的困難有哪些？</label>	
						<textarea name="diary_reflect_content51" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生製作成果報告、海報及影片的過程中，你覺得哪些部分是你需要再改進的？請詳述。如果再一次帶學生做科展，你覺得在這個階段你應該要怎麼做？</label>	
						<textarea name="diary_reflect_content52" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生製作成果報告、海報及影片的過程中，使用本系統輔助和沒有使用系統的差異性有哪些？系統提供哪些功能幫助你進行這個階段？哪些功能是不一定需要的？請詳述。</label>	
						<textarea name="diary_reflect_content53" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>在指導學生製作成果報告、海報及影片的過程中，希望系統還可以提供哪些功能幫助你？請詳述。</label>	
						<textarea name="diary_reflect_content54" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<div class="diary_reflect_page" id="diary_reflect_page6">
					<p>
						<label>你覺得在指導學生完成科展的過程中，哪些是特別重要的？</label>	
						<textarea name="diary_reflect_content61" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>你覺得在指導學生完成科展的過程中，學生可以學習到哪些事情？</label>	
						<textarea name="diary_reflect_content62" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>你覺得科展整個流程應該如何進行呢？</label>	
						<textarea name="diary_reflect_content63" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>你覺得對於指導學生完成科展的信心程度為何？為什麼？（如果是0－100，請為自己的信心程度打分數。）</label>	
						<textarea name="diary_reflect_content64" placeholder="請填寫..." ></textarea>
					</p>
					<p>
						<label>你覺得未來對於指導學生完成科展的過程中可能會遇到哪些困難呢？</label>	
						<textarea name="diary_reflect_content65" placeholder="請填寫..." ></textarea>
					</p>
				</div>
				<input type="button" class="fancybox_btn" id="diary_reflect_add" value="儲存日誌">
				<input type="button" class="fancybox_btn" id="diary_reflect_check" value="確定">
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>