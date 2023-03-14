<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 學習評量與結案';
	include("api/php/header.php");
?>
<style>
/*--------------------------------專題管理：評量結案--------------------------------*/
.assess_box{
	float: left;
	width: 18%;
	height: 180px;
	margin: 30px 10px 15px 80px;
	padding: 120px 20px 60px 20px;
	color: #000000;
	border: 1px solid #000000;
	cursor: pointer;
}
.assess_box:hover{
	background-color: #eee;
}
</style>
<script>
$(function(){
/*--------------------------------專題管理：評量結案--------------------------------*/
	$("#assess_finish").click(function(){
		var x = confirm("【系統】確定結束專題小組後，就無法再進行任何更改囉？");
		if(x){
			$.ajax({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "finish_project",
					p_id : <?php echo $_GET['p_id']; ?>,
					action : "project_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】已確定結束此專題小組！！");
					window.location.href = "nav_finish.php";
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<a href="project_score.php?p_id=<?php echo $_GET['p_id']; ?>">
		<div class="assess_box log_btn" value="學習成果評量">
			<h3 align="center">學習成果評量</h3>
			<p style="text-indent: 30px;">為專題小組和辛苦小組成員們打個成績吧！</p>
		</div>
	</a>
	<div class="assess_box log_btn" id="assess_finish" value="結束專題小組">
		<h3 align="center">結束專題小組</h3>
		<p style="text-indent: 30px;">此專題小組完成了所有階段，結束專題後專題將移往【<a href="nav_finish.php">已結束專題</a>】進行查找。</p>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>