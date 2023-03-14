<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 匯入學生名單';
	include("api/php/header.php");
?>
<style>
#new_field{
	width: 80%;
	padding: 20px;
	margin: 15px auto;
}
#new_field p{
	float: left;
	margin: 0px 20px 10px 0px;
	font-weight: bolder;
}
.new_table{
	width: 100%;
	text-align: center;
}
.new_table th{
	font-size: 18px;
	line-height: 40px;
	color: #FFFFFF;
	background-color: #69B0FA;
}
.new_table td{
	line-height: 30px;
}
.new_table tr:nth-child(odd){
	background-color: #79BBFF;
}
</style>
<script>
$(function(){
/*-----------------------------------匯入學生名單-----------------------------------*/
	$("#project_new_add").click(function(){
		if($("#project_new_form input[type=file]").val() == ""){
			alert("【系統】請選擇上傳學生名單檔案(csv)。");
		}else{
			$("#project_new_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "new_project",
					action : "project_update"
				},
				error : function(){
					alert("【系統】匯入失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】學生名單匯入成功！！");
					window.location.reload();
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<fieldset id="new_field">
		<button style="float: right;"><a href="../model/download/匯入學生名單(範例).csv">匯入學生名單(範例)</a></button>
		<form id="project_new_form">
			<label>匯入學生名單(csv檔)：</label><br />
			<input type="file" name="files">
			<input type="button" id="project_new_add" value="匯入">
		</form>
	</fieldset>
	<fieldset id="new_field">
		<p>學校：<?php echo $_SESSION['school']; ?></p><p>指導老師：<?php echo $_SESSION['name']; ?></p>
		<table class="new_table">
			<tr>
				<th width="20%">帳號</th>
				<th width="20%">密碼</th>
				<th width="20%">名稱</th>
				<th width="20%">學校</th>
				<th width="20%">指導老師</th>
			</tr>
			<?php
				$sql = "SELECT * FROM `userinfo` WHERE `school` = '".$_SESSION['school']."' && `teacher` = '".$_SESSION['name']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<tr>".
								"<td>".$row['account']."</td>".
							  	"<td>".$row['password']."</td>".
							  	"<td>".$row['name']."</td>".
							  	"<td>".$row['school']."</td>".
							  	"<td>".$row['teacher']."</td>".
							  "</tr>";
					}
				}else{
					echo "<tr><td colspan='5'>尚未建立任何學生資料。</td></tr>";
				}
			?>
		</table>
	</fieldset>
</div>
<?php
	include("api/php/footer.php");
?>