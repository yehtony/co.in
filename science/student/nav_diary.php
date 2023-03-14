<?php
	$page_url = '<a href="index.php">小組首頁</a> > 日誌專區';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------日誌專區-------------------------------------*/
.diary_title{
	padding: 12px;
	margin: 5px;
	font-size: 24px;
	font-weight: bolder;
	line-height: 25px;
	border-bottom: 1px solid #000000;
}
.diary_title span{
	margin-left: 30px;
	font-size: 14px;
	color: #888;
}
.fancybox_area textarea{
	width: 100%;
	height: 80px;
	resize: none; 								/*------------不可拉縮------------*/
}
/*--------------------------------日誌專區：小組日誌--------------------------------*/
.diary_group_new{
	color: blue;
}
.diary_group{
	min-height: 60px;
}
.diary_group li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: 0px 10px 10px 10px;
	font-size: 20px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
.diary_group_file{
	display: none;
}
/*--------------------------------日誌專區：個人日誌--------------------------------*/
.diary_personal_new{
	color: blue;
}
.diary_personal li{
	width: 120px;
	height: 25px;
	padding: 8px;
	margin: 0px 10px 10px 10px;
	font-size: 20px;
	font-weight: bolder;
	list-style-type: none;
	text-align: center;
	background-color: #FFD966;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
.diary_personal_file{
	display: none;
}
/*--------------------------------日誌專區：反思日誌--------------------------------*/
.diary_reflection li{
	width: 60px;
	height: 60px;
	padding: 8px 8px 5px 15px;
	margin: 0px 10px;
	font-size: 20px;
	font-weight: bolder;
	list-style-type: none;
	letter-spacing: 8px;
	text-align: center;
	background-color: #989898;
	border-radius: 10px;
	display: inline-table;
	cursor: pointer;
}
.diary_reflection li#stageI, 
.diary_reflection li#stageII,
.diary_reflection li#stageIII,
.diary_reflection li#stageIV,
.diary_reflection li#stageV,
.diary_reflection li#stageVI{
	background-color: #FFD966;
}
#diary_stageI_area, 
#diary_stageII_area, 
#diary_stageIII_area, 
#diary_stageIV_area, 
#diary_stageV_area, 
#diary_stageVI_area{
	width: 60%;
}
</style>
<script>
$(function(){
/*--------------------------------日誌專區：小組日誌--------------------------------*/
	$(".diary_group_new").click(function(){
		$("#diary_group_area h2").html("- 新增小組日誌 -");

		$("input[name=diary_group_date]").val("");
		$("textarea[name=diary_group_problem]").val("");
		$("textarea[name=diary_group_conclusion]").val("");
		$("textarea[name=diary_group_future]").val("");

		$(".diary_group_file").hide();

		$("#diary_group_add").show();
		$("input[type=file]").show();

		$("#diary_group_fancybox").show();
	});
	$("#diary_group_add").click(function(){
		var check = "true";
	
		$("#diary_group_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if($("#diary_group_form input[type=date]").val() == ""){
			alert("【系統】請選擇討論時間。");
		}else if(check == "false"){
			alert("【系統】請填寫完整日誌。");
		}else{
			$("#diary_group_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_group",
					action : "diary_update"
				},
				error : function(){
					alert("【警告】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】小組日誌新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".diary_group_view").click(function(){
		var diary_id = $(this).attr('id');

		$("input[name=diary_group_date]").val("");
		$("textarea[name=diary_group_problem]").val("");
		$("textarea[name=diary_group_conclusion]").val("");
		$("textarea[name=diary_group_future]").val("");
		$(".diary_group_file").html("");

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'view_group',
				diary_id: diary_id,
				action: 'diary_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				for(var a in data){
					$("#diary_group_area h2").html("- 觀看小組日誌 -");

					$("input[name=diary_group_date]").val(data[a].diary_date);
					$("textarea[name=diary_group_problem]").val(data[a].diary_content1);
					$("textarea[name=diary_group_conclusion]").val(data[a].diary_content2);
					$("textarea[name=diary_group_future]").val(data[a].diary_content3);

					if(data[a].diary_filename != undefined){
						$(".diary_group_file").html("<a href='"+ data[a].diary_fileurl +"' download><img src='/co.in/science/model/images/project_files.gif' width='20px' style='vertical-align: middle;'>"+ data[a].diary_filename +"</a>");
					}else{
						$(".diary_group_file").html("未上傳檔案。");
					}

					$(".diary_group_file").show();

					$("#diary_group_add").hide();
					$("input[type=file]").hide();
					
					$("#diary_group_fancybox").show();
				}
			}
		});
	});
/*--------------------------------日誌專區：個人日誌--------------------------------*/
	$(".diary_personal_new").click(function(){
		$("#diary_personal_area h2").html("- 新增個人日誌 -");

		$("input[name=diary_personal_date]").val("");
		$("textarea[name=diary_personal_progress]").val("");
		$("textarea[name=diary_personal_discuss]").val("");
		$("textarea[name=diary_personal_learn]").val("");
		$("textarea[name=diary_personal_future]").val("");

		$(".diary_personal_file").hide();

		$("input[type=file]").show();
		$("#diary_personal_add").show();

		$("#diary_personal_fancybox").show();
	});
	$("#diary_personal_add").click(function(){
		var check = "true";

		$("#diary_personal_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if($("#diary_personal_form input[type=date]").val() == ""){
			alert("【系統】請選擇討論時間。");
		}else if(check == "false"){
			alert("【系統】請填寫完整日誌。");
		}else{
			$("#diary_personal_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_personal",
					action : "diary_update"
				},
				error : function(){
					alert("【警告】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】個人日誌新增成功！");
					window.location.reload();
				}
			});
		}
	});
	$(".diary_personal_view").click(function(){
		var diary_id = $(this).attr('id');

		$("input[name=diary_personal_date]").val("");
		$("textarea[name=diary_personal_progress]").val("");
		$("textarea[name=diary_personal_discuss]").val("");
		$("textarea[name=diary_personal_learn]").val("");
		$("textarea[name=diary_personal_future]").val("");
		$(".diary_personal_file").html("");

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'view_personal',
				diary_id: diary_id,
				action: 'diary_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				for(var a in data){
					$("#diary_personal_area h2").html("- 觀看個人日誌 -");

					$("input[name=diary_personal_date]").val(data[a].diary_date);
					$("textarea[name=diary_personal_progress]").val(data[a].diary_content1);
					$("textarea[name=diary_personal_discuss]").val(data[a].diary_content2);
					$("textarea[name=diary_personal_learn]").val(data[a].diary_content3);
					$("textarea[name=diary_personal_future]").val(data[a].diary_content3);

					if(data[a].diary_filename != undefined){
						$(".diary_personal_file").html("<a href='"+ data[a].diary_fileurl +"' download>"+ data[a].diary_filename +"</a>");
					}else{
						$(".diary_personal_file").html("未上傳檔案。");
					}

					$(".diary_personal_file").show();

					$("#diary_personal_add").hide();
					$("input[type=file]").hide();
					
					$("#diary_personal_fancybox").show();
				}
			}
		});
	});
/*--------------------------------日誌專區：反思日誌--------------------------------*/
	// 第一階段
	$("#stageI").click(function(){
		$("#diary_stageI_fancybox").show();
	});
	$("#diary_stageI_add").click(function(){
		var stageI_num = 7;
		var check = "true";
	
		$("#diary_stageI_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出第一階段反思後，就無法修改囉？");
			if(x){
				$("#diary_stageI_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageI",
						stageI_num : stageI_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data) {
						alert("【系統】第一階段反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 第二階段
	$("#stageII").click(function(){
		$("#diary_stageII_fancybox").show();
	});
	$("#diary_stageII_add").click(function(){
		var stageII_num = 4;
		var check = "true";
	
		$("#diary_stageII_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出第二階段反思後，就無法修改囉？");
			if(x){
				$("#diary_stageII_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageII",
						stageII_num : stageII_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data) {
						alert("【系統】第二階段反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 第三階段
	$("#stageIII").click(function(){
		$("#diary_stageIII_fancybox").show();
	});
	$("#diary_stageIII_add").click(function(){
		var stageIII_num = 4;
		var check = "true";
	
		$("#diary_stageIII_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出第三階段反思後，就無法修改囉？");
			if(x){
				$("#diary_stageIII_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageIII",
						stageIII_num : stageIII_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function(data){
						alert("【系統】第三階段反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 第四階段
	$("#stageIV").click(function(){
		$("#diary_stageIV_fancybox").show();
	});
	$("#diary_stageIV_add").click(function(){
		var stageIV_num = 6;
		var check = "true";
	
		$("#diary_stageIV_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出第四階段反思後，就無法修改囉？");
			if(x){
				$("#diary_stageIV_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageIV",
						stageIV_num : stageIV_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function(data){
						alert("【系統】第四階段反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 第五階段
	$("#stageV").click(function(){
		$("#diary_stageV_fancybox").show();
	});
	$("#diary_stageV_add").click(function(){
		var stageV_num = 8;
		var check = "true";
	
		$("#diary_stageV_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出第五階段反思後，就無法修改囉？");
			if(x){
				$("#diary_stageV_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageV",
						stageV_num : stageV_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function(data){
						alert("【系統】第五階段反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
	// 總回顧
	$("#stageVI").click(function(){
		$("#diary_stageVI_fancybox").show();
	});
	$("#diary_stageVI_add").click(function(){
		var stageVI_num = 5;
		var check = "true";
	
		$("#diary_stageVI_form textarea").each(function(){
			if($(this).val() == ""){
				check = "false";
			};
		});

		if(check == "false"){
			alert("【系統】請填寫完整階段反思。");
		}else{
			var x = confirm("【系統】確定送出總回顧反思後，就無法修改囉？");
			if(x){
				$("#diary_stageVI_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "add_stageVI",
						stageVI_num : stageVI_num,
						action : "diary_update"
					},
					error : function(){
						alert("【警告】送出失敗！！請再重試一次！！");
						return;
					},
					success : function(data){
						alert("【系統】總回顧反思新增成功！");
						window.location.reload();
					}
				});
			}
		}
	});
});
</script>
<div id="centercolumn">
	<div class="diary_title">- 小組日誌 -<span>( p.s 小組日誌，由組長撰寫。)</span></div>
	<ul class="diary_group">
		<?php
			$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' && `type` = '2' ORDER BY `date` ASC";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					echo "<li class='diary_group_view' id='".$row['d_id']."'>".$row['date']."</li>";
				}
				if($_SESSION['chief'] == '1'){ // 組長
					echo "<li class='diary_group_new'>[新增日誌]</li>";
				}
			}else{
				if($_SESSION['chief'] == '1'){ // 組長
					echo "<li class='diary_group_new'>[新增日誌]</li>";
				}
			}
				mysql_query($sql, $link) or die(mysql_error());
		?>
	</ul>
	<div class="diary_title">- 反思日誌 -</div>
	<ul class="diary_reflection">
		<?php
			if($_SESSION['reflection_stage'] == '0'){
				echo "<li>第一階段</li>
					  <li>第二階段</li>
					  <li>第三階段</li>
					  <li>第四階段</li>
					  <li>第五階段</li>
					  <li>總回顧</li>";
			}else if($_SESSION['reflection_stage'] == '1'){
				echo "<li id='stageI'>第一階段</li>
					  <li>第二階段</li>
					  <li>第三階段</li>
					  <li>第四階段</li>
					  <li>第五階段</li>
					  <li>總回顧</li>";
			}else if($_SESSION['reflection_stage'] == '2'){
				echo "<li id='stageI'>第一階段</li>
					  <li id='stageII'>第二階段</li>
					  <li>第三階段</li>
					  <li>第四階段</li>
					  <li>第五階段</li>
					  <li>總回顧</li>";
			}else if($_SESSION['reflection_stage'] == '3'){
				echo "<li id='stageI'>第一階段</li>
					  <li id='stageII'>第二階段</li>
					  <li id='stageIII'>第三階段</li>
					  <li>第四階段</li>
					  <li>第五階段</li>
					  <li>總回顧</li>";
			}else if($_SESSION['reflection_stage'] == '4'){
				echo "<li id='stageI'>第一階段</li>
					  <li id='stageII'>第二階段</li>
					  <li id='stageIII'>第三階段</li>
					  <li id='stageIV'>第四階段</li>
					  <li>第五階段</li>
					  <li>總回顧</li>";
			}else if($_SESSION['reflection_stage'] == '5'){
				echo "<li id='stageI'>第一階段</li>
					  <li id='stageII'>第二階段</li>
					  <li id='stageIII'>第三階段</li>
					  <li id='stageIV'>第四階段</li>
					  <li id='stageV'>第五階段</li>
					  <li id='stageVI'>總回顧</li>";
			}
		?>
	</ul>
	<div class="diary_title">- 個人日誌 -</div>
	<ul class="diary_personal">
		<?php
			$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' && `u_id` = '".$_SESSION['UID']."' && `type` = '0' ORDER BY `date` ASC";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					echo "<li class='diary_personal_view' id='".$row['d_id']."'>".$row['date']."</li>";
				}
			}
				mysql_query($sql, $link) or die(mysql_error());
		?>
		<li class='diary_personal_new'>[新增日誌]</li>
	</ul>
	<div class="fancybox_box" id="diary_group_fancybox">
		<div class="fancybox_area" id="diary_group_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新增小組日誌 -</h2>
			<form id="diary_group_form" method="post">
				<p>
					<label>討論的時間：</label>
					<input type="date" name="diary_group_date">
				</p>
				<p>
					<label>討論的問題：</label>
					<textarea name="diary_group_problem" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>討論的結論：</label>
					<textarea name="diary_group_conclusion" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>後續應進行之工作：</label>
					<textarea name="diary_group_future" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>錄音檔：</label>
					<input type="file" name="files" />
					<span class="diary_group_file"></span>
				</p>
				<input type="button" class="fancybox_btn" id="diary_group_add" value="新增">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_personal_fancybox">
		<div class="fancybox_area" id="diary_personal_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 新增個人日誌 -</h2>
			<form id="diary_personal_form" method="post">
				<p>
					<label>討論的時間：</label>
					<input type="date" name="diary_personal_date">
				</p>
				<p>
					<label>目前研究進度已經進展到哪裡了？</label>
					<textarea name="diary_personal_progress" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>我和同學討論了什麼？</label>
					<textarea name="diary_personal_discuss" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>我學習到什麼？尤其哪些是以前沒學過的？</label>
					<textarea name="diary_personal_learn" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>接下來，我想要如何進行科學專題？</label>
					<textarea name="diary_personal_future" placeholder="請填寫..."></textarea>
				</p>
				<p>
					<label>錄音檔：</label>
					<input type="file" name="files" />
					<span class="diary_personal_file"></span>
				</p>
				<input type="button" class="fancybox_btn" id="diary_personal_add" value="新增">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageI_fancybox">
		<div class="fancybox_area" id="diary_stageI_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 第一階段《形成問題》-</h2>
			<form id="diary_stageI_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>若你下次要和同學一起做科展，要尋找科展的題目，你覺得要注意什麼？</label>
								<textarea name='diary_stageI_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>你是如何找出你有興趣研究的題目？</label>
								<textarea name='diary_stageI_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>在找出科展的研究題目時，什麼是你覺得困難的？</label>
								<textarea name='diary_stageI_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>科展系統有幫你們克服這些困難嗎？</label>
								<textarea name='diary_stageI_4'>".$row['content_4']."</textarea>
							  </p>
							  <p>
								<label>在許多有興趣的題目中，你們是如何決定出最想做的題目？</label>
								<textarea name='diary_stageI_5'>".$row['content_5']."</textarea>
							  </p>
							  <p>
								<label>在尋找題目的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們找到想研究的題目？</label>
								<textarea name='diary_stageI_6'>".$row['content_6']."</textarea>
							  </p>
							  <p>
								<label>如果要讓你們再做一次科展，在找出科展的研究題目時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們找出科展的研究題目？</label>
								<textarea name='diary_stageI_7'>".$row['content_7']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>若你下次要和同學一起做科展，要尋找科展的題目，你覺得要注意什麼？</label>
							<textarea name='diary_stageI_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>你是如何找出你有興趣研究的題目？</label>
							<textarea name='diary_stageI_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在找出科展的研究題目時，什麼是你覺得困難的？</label>
							<textarea name='diary_stageI_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>科展系統有幫你們克服這些困難嗎？</label>
							<textarea name='diary_stageI_4' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在許多有興趣的題目中，你們是如何決定出最想做的題目？</label>
							<textarea name='diary_stageI_5' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在尋找題目的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們找到想研究的題目？</label>
							<textarea name='diary_stageI_6' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>如果要讓你們再做一次科展，在找出科展的研究題目時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們找出科展的研究題目？</label>
							<textarea name='diary_stageI_7' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageI_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageII_fancybox">
		<div class="fancybox_area" id="diary_stageII_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 第二階段《規劃》-</h2>
			<form id="diary_stageII_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '2'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>請你說說看，你們依據想研究的問題來設計出可解決問題或找出答案的計畫，在這設計的過程中，你是如何進行的？有哪一些步驟？例如先做…..然後再做……最後才……請依順序說明。</label>
								<textarea name='diary_stageII_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>設計計畫的哪一些步驟，對你來說是困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
								<textarea name='diary_stageII_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>在進行設計計畫的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們設計計畫的？</label>
								<textarea name='diary_stageII_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>如果要讓你們再做一次科展，在進行設計計畫時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行設計計畫？</label>
								<textarea name='diary_stageII_4'>".$row['content_4']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>請你說說看，你們依據想研究的問題來設計出可解決問題或找出答案的計畫，在這設計的過程中，你是如何進行的？有哪一些步驟？例如先做…..然後再做……最後才……請依順序說明。</label>
							<textarea name='diary_stageII_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>設計計畫的哪一些步驟，對你來說是困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
							<textarea name='diary_stageII_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在進行設計計畫的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們設計計畫的？</label>
							<textarea name='diary_stageII_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>如果要讓你們再做一次科展，在進行設計計畫時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行設計計畫？</label>
							<textarea name='diary_stageII_4' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageII_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageIII_fancybox">
		<div class="fancybox_area" id="diary_stageIII_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 第三階段《執行》-</h2>
			<form id="diary_stageIII_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>這次的實驗/調查/探索過程中你是如何進行的？有哪一些步驟？例如先做…然後再做……最後才……請依順序說明。</label>
								<textarea name='diary_stageIII_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>哪一些實驗/調查/探索的過程對你而言是困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
								<textarea name='diary_stageIII_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>在進行實驗/調查/探索過程的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們進行實驗/調查/探索的？</label>
								<textarea name='diary_stageIII_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>如果要讓你們再做一次科展，在進行實驗/調查/探索時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行實驗/調查/探索？</label>
								<textarea name='diary_stageIII_4'>".$row['content_4']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>這次的實驗/調查/探索過程中你是如何進行的？有哪一些步驟？例如先做…然後再做……最後才……請依順序說明。</label>
							<textarea name='diary_stageIII_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>哪一些實驗/調查/探索的過程對你而言是困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
							<textarea name='diary_stageIII_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在進行實驗/調查/探索過程的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們進行實驗/調查/探索的？</label>
							<textarea name='diary_stageIII_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>如果要讓你們再做一次科展，在進行實驗/調查/探索時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行實驗/調查/探索？</label>
							<textarea name='diary_stageIII_4' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageIII_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageIV_fancybox">
		<div class="fancybox_area" id="diary_stageIV_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 第四階段《形成結論》-</h2>
			<form id="diary_stageIV_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '4'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>說說看這次的實驗/調查數據，你是如何解釋實驗/調查的結果？你是如何整理數據結果讓別人容易看得懂？</label>
								<textarea name='diary_stageIV_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>說說看你的研究結果？依據你的實驗/調查 假設和實驗/調查 數據，有什麼發現？</label>
								<textarea name='diary_stageIV_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>說說看你的科展結論寫了哪些內容？</label>
								<textarea name='diary_stageIV_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>在寫科展結論的過程中，那一些部份是你覺得困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
								<textarea name='diary_stageIV_4'>".$row['content_4']."</textarea>
							  </p>
							  <p>
								<label>在寫科展結論的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們寫科展結論的？</label>
								<textarea name='diary_stageIV_5'>".$row['content_5']."</textarea>
							  </p>
							  <p>
								<label>如果要讓你們再做一次科展，在進行寫科展結論時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們寫科展結論？</label>
								<textarea name='diary_stageIV_6'>".$row['content_6']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>說說看這次的實驗/調查數據，你是如何解釋實驗/調查的結果？你是如何整理數據結果讓別人容易看得懂？</label>
							<textarea name='diary_stageIV_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>說說看你的研究結果？依據你的實驗/調查 假設和實驗/調查 數據，有什麼發現？</label>
							<textarea name='diary_stageIV_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>說說看你的科展結論寫了哪些內容？</label>
							<textarea name='diary_stageIV_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在寫科展結論的過程中，那一些部份是你覺得困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
							<textarea name='diary_stageIV_4' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在寫科展結論的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們寫科展結論的？</label>
							<textarea name='diary_stageIV_5' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>如果要讓你們再做一次科展，在進行寫科展結論時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們寫科展結論？</label>
							<textarea name='diary_stageIV_6' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageIV_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageV_fancybox">
		<div class="fancybox_area" id="diary_stageV_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 第五階段《報告與展示》-</h2>
			<form id="diary_stageV_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '5'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>說說看你的科展報告內容主要寫些什麼？</label>
								<textarea name='diary_stageV_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>說說看，你上台向老師同學分享你的研究報告，報告了哪些你覺得重要的地方？哪些地方你沒有說出來，為什麼？</label>
								<textarea name='diary_stageV_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>說說看你的科展結論寫了哪些內容？</label>
								<textarea name='diary_stageV_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>在上台分享報告的過程中，那一個部份是你覺得最困難的？為什麼？</label>
								<textarea name='diary_stageV_4'>".$row['content_4']."</textarea>
							  </p>
							  <p>
								<label>同學和老師對你寫的報告內容提出了什麼疑問？你依據什麼來回答？請舉例說明。</label>
								<textarea name='diary_stageV_5'>".$row['content_5']."</textarea>
							  </p>
							  <p>
								<label>在科展報告的過程中，那一些部份是你覺得困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
								<textarea name='diary_stageV_6'>".$row['content_6']."</textarea>
							  </p>
							  <p>
								<label>在科展報告的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們進行科展報告？</label>
								<textarea name='diary_stageV_7'>".$row['content_7']."</textarea>
							  </p>
							  <p>
								<label>如果要讓你們再做一次科展，在進行科展報告時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行科展報告？</label>
								<textarea name='diary_stageV_8'>".$row['content_8']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>說說看你的科展報告內容主要寫些什麼？</label>
							<textarea name='diary_stageV_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>說說看，你上台向老師同學分享你的研究報告，報告了哪些你覺得重要的地方？哪些地方你沒有說出來，為什麼？</label>
							<textarea name='diary_stageV_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>說說看你的科展結論寫了哪些內容？</label>
							<textarea name='diary_stageV_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在上台分享報告的過程中，那一個部份是你覺得最困難的？為什麼？</label>
							<textarea name='diary_stageV_4' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>同學和老師對你寫的報告內容提出了什麼疑問？你依據什麼來回答？請舉例說明。</label>
							<textarea name='diary_stageV_5' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在科展報告的過程中，那一些部份是你覺得困難的？為什麼？科展系統有幫你們克服這些困難嗎？</label>
							<textarea name='diary_stageV_6' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>在科展報告的這段時間，你覺得科展系統所提供的哪些功能是你覺得能幫你們進行科展報告？</label>
							<textarea name='diary_stageV_7' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>如果要讓你們再做一次科展，在進行科展報告時，你希望科展系統還可以提供哪一些目前沒有的功能，以幫助你們進行科展報告？</label>
							<textarea name='diary_stageV_8' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageV_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="diary_stageVI_fancybox">
		<div class="fancybox_area" id="diary_stageVI_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 最後 總回顧 -</h2>
			<form id="diary_stageVI_form" method="post">
			<?php
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `u_id` = '".$_SESSION['UID']."' AND `type` = '1'  AND `category` = '6'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>
								<label>你覺得透過科展系統來做科展跟一般自然上課方式哪些是一樣的？哪些是不同的？</label>
								<textarea name='diary_stageVI_1'>".$row['content_1']."</textarea>
							  </p>
							  <p>
								<label>你認為參加科展，從開始到完成一件科展作品，在這當中你可以學到什麼？</label>
								<textarea name='diary_stageVI_2'>".$row['content_2']."</textarea>
							  </p>
							  <p>
								<label>你覺得科展系統對你們進行科展有幫助嗎？為什麼？</label>
								<textarea name='diary_stageVI_3'>".$row['content_3']."</textarea>
							  </p>
							  <p>
								<label>你覺得科展系統所提供的哪些功能是你覺得對你們進行科展沒有幫助的？為什麼？</label>
								<textarea name='diary_stageVI_4'>".$row['content_4']."</textarea>
							  </p>
							  <p>
								<label>你還會想要用科展系統來幫助你們做科展嗎？為什麼？</label>
								<textarea name='diary_stageVI_5'>".$row['content_5']."</textarea>
							  </p>";
					}
				}else{
					echo "<p>
							<label>你覺得透過科展系統來做科展跟一般自然上課方式哪些是一樣的？哪些是不同的？</label>
							<textarea name='diary_stageVI_1' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>你認為參加科展，從開始到完成一件科展作品，在這當中你可以學到什麼？</label>
							<textarea name='diary_stageVI_2' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>你覺得科展系統對你們進行科展有幫助嗎？為什麼？</label>
							<textarea name='diary_stageVI_3' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>你覺得科展系統所提供的哪些功能是你覺得對你們進行科展沒有幫助的？為什麼？</label>
							<textarea name='diary_stageVI_4' placeholder='請填寫...'></textarea>
						  </p>
						  <p>
							<label>你還會想要用科展系統來幫助你們做科展嗎？為什麼？</label>
							<textarea name='diary_stageVI_5' placeholder='請填寫...'></textarea>
						  </p>
						  <input type='button' class='fancybox_btn' id='diary_stageVI_add' value='新增'>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>