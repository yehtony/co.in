<?php
	$page_url = '<a href="index.php">小組首頁</a> > 帳號管理';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------帳號管理-------------------------------------*/
.account_table{
	width: 70%;
	margin: 15px auto; 
	font-size: 110%;
	text-align: center;
	border-bottom: 15px solid #43474A;
}
.account_table th{
	padding: 8px;
	background-color: #62C48E;
}
.account_table td{
	padding: 5px;
}
/*--------------------------------帳號管理：小組成員--------------------------------*/
#account_member_area{
	width: 25%;
	text-align: center;
}
#account_member_area h3{
	margin: 10px;
}
/*--------------------------------帳號管理：上傳照片--------------------------------*/
#account_upload_area{
	width: 25%;
}
/*--------------------------------帳號管理：修改資料--------------------------------*/
#account_info_area{
	width: 20%;
}
</style>
<script>
$(function(){
/*--------------------------------帳號管理：小組成員--------------------------------*/
	$("#member_btn").click(function(){
		$("#account_member_fancybox").show();
	});
/*--------------------------------帳號管理：上傳照片--------------------------------*/
	$("#upload_btn").click(function(){
		$("#account_upload_fancybox").show();
	});
	$("#upload_add").click(function(){
		var realfile = $(".upload_files").val();				// 檔案實際位置
		// console.log(realfile);
		if(realfile != ''){
			$("#upload_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "upload_photo",
					action : "account_update"
				},
				error : function(){
					alert("【警告】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("【系統】上傳照片成功！！");
					window.location.reload();
				}
			});
		}
	});
/*--------------------------------帳號管理：修改資料--------------------------------*/
	$("#info_btn").click(function(){
		$("#account_info_fancybox").show();
	});
	$("#info_add").click(function(){
		$("#info_form").ajaxSubmit({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "info_fixed",
				action : "account_update"
			},
			error : function(){
				alert("【警告】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				alert("【系統】修改資料成功！！");
				window.location.reload();
			}
		});
	});
});
</script>
<div id="centercolumn">
	<h1 id="title">- 帳號管理 -</h1>
<hr />
	<table class="account_table" border="1" cellspacing="0">
		<tr>
			<th colspan="3">個人小檔案</td>
		</tr>
		<?php
			$sql = "SELECT * FROM `userinfo` WHERE `u_id` = '".$_SESSION['UID']."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					echo "<tr>
							<td width='20%'>姓名</td>
							<td width='48%'>".$row['name']."</td>
							<td width='37%' rowspan='9'><img src='".$row['photo']."' width='100%' /></td>
						  </tr>
						  <tr>
							<td>暱稱</td><td>".$row['nickname']."</td>
						  </tr>
						  <tr>
							<td>帳號</td><td>".$row['account']."</td>
						  </tr>
						  <tr>
							<td>密碼</td><td>".$row['password']."</td>
						  </tr>
						  <tr>
							<td>性別</td><td>".$row['gender']."</td>
						  </tr>
						  <tr>
							<td>生日</td><td>".$row['birthday']."</td>
						  </tr>
						  <tr>
							<td>身份別</td><td>".$row['identity']."</td>
						  </tr>
						  <tr>
							<td>縣(市)</td><td>".$row['county']."</td>
						  </tr>
						  <tr>
							<td>校名</td><td>".$row['school']."</td>
						  </tr>
						  <tr>
							<td>年級</td><td>".$row['grade']."年級</td>
							<td>
								<input type='button' class='log_btn' id='member_btn' value='小組成員' />
								<input type='button' class='log_btn' id='upload_btn' value='上傳照片' />
								<input type='button' class='log_btn' id='info_btn' value='修改資料' />
							</td>
						  </tr>
						  <tr>
							<td>指導老師</td><td colspan='2'>".$row['teacher']."</td>
						  </tr>
						  <tr>
							<td>信箱</td><td colspan='2'>".$row['email']."</td>
						  </tr>";
					}
				}
		?>
	</table>
	<div class="fancybox_box" id="account_member_fancybox">
		<div class="fancybox_area" id="account_member_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 10px;">- 小組成員 -</h2>
		<hr />
			<?php
				// 抓取小組成員
				$group_chief = "";
				$group_member = "";

				$g_sql = "SELECT `s_id`, `chief` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
				while($g_row = mysql_fetch_array($g_qry)){
					// 抓取隊員名稱
					$n_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$g_row['s_id']."'";
					$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
					$n_row = mysql_fetch_array($n_qry);
					if($g_row['chief'] == '1'){
						$group_chief = $n_row['name'];
					}else{
						$group_member .= $n_row['name']."<br />";
					}
				}
				if($group_member == ""){
					$group_member = "無";
				}
				echo "<h3>組長</h3>".$group_chief.
					 "<h3>組員</h3>".$group_member;
				
	?>
		</div>
	</div>
	<div class="fancybox_box" id="account_upload_fancybox">
		<div class="fancybox_area" id="account_upload_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 10px;">- 上傳照片 -</h2>
		<hr />
			<form id="upload_form" method="post">
				<input type='file' name='files' class='upload_files' />
				<input type='button' id='upload_add' value='上傳' />
			</form>
		<hr />
		</div>
	</div>
	<div class="fancybox_box" id="account_info_fancybox">
		<div class="fancybox_area" id="account_info_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2 style="margin: 10px;">- 修改資料 -</h2>
		<hr />
			<form id="info_form" method="post">
				<p>姓名：<input type='text' name='info_name' /></p>
				<p>暱稱：<input type='text' name='info_nickname' /></p>
				<p>密碼：<input type='text' name='info_password' /></p>
				<p>信箱：<input type='email' name='info_email' placeholder="XXX@email.com" /></p>
				<input type='button' class="fancybox_btn" id='info_add' value='修改' />
			</form>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>