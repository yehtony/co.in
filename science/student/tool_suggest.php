<?php
	$page_url = '<a href="index.php">小組首頁</a> > 學生求助';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------學生求助-------------------------------------*/
.suggest_table{
	width: 100%;
	margin-top: 40px;
	text-align: center;
}
.suggest_table th{
	font-size: 20px;
	line-height: 45px;
	color: white;
	background-color: #5DBC90;
}
.suggest_table td{
	line-height: 30px;
}
.suggest_table tr:nth-child(odd){
	background-color: #7CE786;
}
/*--------------------------------學生求助：我要求助--------------------------------*/
.suggest_new{
	float: right;
	width: 6%;
	height: 60px;
	font-weight: bolder;
	color: #FFFFFF;
	border: 4px solid #FFFFFF;
	border-radius: 20px;
	box-shadow: 1px 1px 2px 1px #888888;
	background-color: #5DBC90;
	cursor: pointer;
}
#suggest_new_area textarea{
	width: 85%;
	height: 80px;
	vertical-align: top;
	resize: none;
}
/*----------------------------------學生求助：回覆----------------------------------*/
#suggest_read_area{
	width: 50%;
}
#suggest_read_area textarea{
	width: 85%;
	height: 60px;
	vertical-align: top;
	resize: none;
}
#suggest_reply{
	line-height: 10px;
	text-align: center;
}
</style>
<script>
$(function(){
/*--------------------------------學生求助：我要求助--------------------------------*/
	$(".suggest_new").click(function(){
		$("#suggest_new_fancybox").show();
	});
	$("#suggest_add").click(function(){
		if($("#suggest_new_form select").val() == "default" || $("#suggest_new_form input").val() == ""){
			alert("【系統】請填寫求助內容。");
		}else{
			$("#suggest_new_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_help",
					action : "help_update"
				},
				error : function(){
					alert("【系統】新增失敗！請檢查網路是否正常！");
					return;
				},
				success : function (data) {
					alert("【系統】問題求助新增成功！");
					window.location.reload();
				}
			});
		}
	});
/*----------------------------------學生求助：回覆----------------------------------*/
	$(".suggest_reply").click(function(){
		var help_id = $(this).parent().parent().attr('id');
		// console.log(help_id);
		$("#suggest_reply").show();
		$('#suggest_reply_content').html("");

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "read_help",
				help_id : help_id,
				action : "help_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					$('input[name=suggest_id]').val(data[a].help_id);	// 將結果顯示出來
					$('#suggest_date').html(data[a].help_time);	
					$('#suggest_people').html(data[a].help_uid);
					$('#suggest_type').html(data[a].help_type);
					$('#suggest_title').html(data[a].help_title);
					$('#suggest_description').html(data[a].help_description);

					// 判斷檔案是否為空值
					if(data[a].help_filename != undefined){
						$('#suggest_filename').html("<a href='"+ data[a].help_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].help_filename +"</a>");
					}
					if(data[a].help_r_content != undefined){
						$("#suggest_reply").hide();
						$("<p>"+ data[a].help_r_uid +"："+ data[a].help_r_content +"</p>").appendTo("#suggest_reply_content");
					}
					if(data[a].help_r_filename != undefined){
						$("<a href='"+ data[a].help_r_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].help_r_filename +"</a>").appendTo("#suggest_reply_content");
					}
				}
				$("#suggest_read_fancybox").show();
			}
		});
	});
	$("#suggest_submit").click(function(){
		var help_id = $('input[name=suggest_id]').val();
		// console.log(help_id);
		if($("textarea[name=suggest_reply]").val() == ""){
			alert("【系統】尚未填寫回覆內容。");
		}else{
			$("#suggest_read_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "reply_help",
					help_id : help_id,
					action : "help_update"
				},
				error : function(){
					alert("【系統】回覆失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】回覆問題求助成功！");
					window.location.reload();
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<button class="suggest_new log_btn" value="我要求助">我要<br />求助</button>
	<h1 id="title">- 學生求助 -</h1>
	<table class="suggest_table">
		<tr>
			<th width="15%">日期</th>
			<th width="10%">發問人</th>
			<th width="10%">類型</th>
			<th width="25%">回報內容</th>
			<th width="10%">功能</th>
		</tr>
		<?php
			$sql = "SELECT * FROM `help` WHERE `p_id` = '".$_SESSION['p_id']."' && `objects` = '1' ORDER BY `help_time` DESC";	// 皆顯示是因為可以回憶以前的問題。
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					// 抓取求助者
					$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
					$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
					$p_row = mysql_fetch_array($p_qry);
						$name = $p_row['name'];
					// 問題類型
					if($row['type'] == "0"){
						$type = "其他";
					}else if($row['type'] == "1"){
						$type = "器材問題";
					}else if($row['type'] == "2"){
						$type = "如何進行";
					}else if($row['type'] == "3"){
						$type = "小組溝通";
					}
					echo "<tr id=".$row['h_id'].">".
							"<td>".date('Y-m-d', strtotime($row['help_time']))."</td>".
							"<td>".$name."</td>".
							"<td>".$type."</td>".
							"<td>".$row['description']."</td>".
							"<td>
								<button class='suggest_reply log_btn' value='求助回覆'>回覆</button>
							</td>".
						  "</tr>";
				}
			}else{
				echo "<tr><td colspan='5'>尚未回報任何相關問題。</td></tr>";
			}
		?>
	</table>
	<div class="fancybox_box" id="suggest_new_fancybox">
		<div class="fancybox_area" id="suggest_new_area">
		<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<form id="suggest_new_form">
				<h2>- 我要求助 -</h2>
				<p>類型：<select name="suggest_type">
							<option value="default">請選求助類型...</option>
							<option value="1">器材問題</option>
							<option value="2">如何進行</option>
							<option value="3">小組溝通</option>
							<option value="0">其他</option>
						 </select></p>
				<p>主題：<input type="text" name="suggest_title" /></p>
				<p>內容：<textarea name="suggest_description"></textarea></p>
				<p>附加檔案：<input type="file" name="files" /></p>
				<input type="button" class="fancybox_btn" id="suggest_add" value="新增">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="suggest_read_fancybox">
		<div class="fancybox_area" id="suggest_read_area">
		<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 學生求助 -</h2>
			<p>時間：<span id="suggest_date"></span></p>
			<p>求助人：<span id="suggest_people"></span></p>
			<p>類型：<span id="suggest_type"></span></p>
			<p>主題：<span id="suggest_title"></span></p>
			<p>內容：<span id="suggest_description"></span></p>
			<p style="padding-left: 60px;"><span id="suggest_filename"></span></p>
		<hr />
			<p id="suggest_reply">目前無任何回應</p>
			<p><span id="suggest_reply_content"></span></p>
		<hr />
			<form id="suggest_read_form">
				<p style="display: none;">問題：<input type="text" name="suggest_id"></p>
				<p>回覆：<textarea name="suggest_reply"></textarea></p>
				<p>附加檔案：<input type="file" name="files" /></p>
				<input type="button" class="fancybox_btn" id="suggest_submit" value="送出">
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>