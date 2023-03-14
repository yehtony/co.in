<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 小組任務編輯';
	include("api/php/header.php");
?>
<style>
/*--------------------------------專題管理：任務編輯--------------------------------*/
.task_box{
	margin: 100px 10px 0px 100px;
	padding: 50px 20px 60px 20px;
	width: 30%;
	height: 140px;
	color: #000000;
	border: 1px solid #000000;
	float: left;
	cursor: pointer;
}
.task_box:hover{
	background-color: #eee;
}
</style>
<div id="centercolumn">
	<div>
		<p>您可以依據學生的探究能力不同，針對不同小組給予<b>時間規劃</b>和調整其<b>探究開放程度</b>。</p>
	</div>
	<a href="project_time.php?p_id=<?php echo $_GET['p_id']; ?>">
		<div class="task_box log_btn" value="小組時間規劃">
			<h3 align="center">小組時間規劃</h3>
			<p style="text-indent: 30px;">為專題的每一階段設定截止日期，妥善規劃完成專題的時間。你也可以發送廣播，提醒學生要盡快完成階段任務。</p>
		</div>
	</a>
	<a href="project_open.php?p_id=<?php echo $_GET['p_id']; ?>">
		<div class="task_box log_btn" value="任務開放程度設定">
			<h3 align="center">任務開放程度設定</h3>
			<p style="text-indent: 30px;">為每個不同能力的小組，設定不一樣的任務開放程度及學習鷹架，給予學生更適性化的教學。</p>
		</div>
	</a>
</div>
<?php
	include("api/php/footer.php");
?>