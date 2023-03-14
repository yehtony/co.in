<?php
	$page_url = '<a href="index.php">專題指導</a> > 我的知識庫';
	include("api/php/header.php");
?>
<style>
/*------------------------------------我的知識庫------------------------------------*/
.knowledge_new{
	position: relative;
	top: -25px;
	right: 100px;
	float: right;
}
.knowledge_table{
	width: 100%;
	margin-top: 15px;
	text-align: center;
}
.knowledge_table th{
	font-size: 20px;
	line-height: 45px;
	color: white;
	background-color: #63ADF8;
}
.knowledge_table td{
	line-height: 30px;
}
.knowledge_table tr:nth-child(odd){
	background-color:#99CCFF;
}
</style>
<script>
$(function(){
/*---------------------------------我的知識庫：搜尋---------------------------------*/
	$("#search_btn").click(function(){
		$.ajax({
			url: "/co.in/science/teacher/api/php/science.php",
			type: "POST",
			data: {
				knowledge_search: $("input[name=knowledge_search]").val(),
				action: 'knowledge_get'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				var response = (data).split('|', 8);
				var question = response[1];
				var way = response[2];
				var people = response[3];

				$('#question').html(question);	//將結果顯示出來
				$('#way').html(way);
				$('#people').html(people);
			}
		});
	});
});
</script>
<div id="centercolumn">
	<form method="post">
		關鍵字：<input type="text" name="knowledge_search">
		<input type="button" id="search_btn" value="搜尋">
	</form>
	<button class="knowledge_new">我要回報</button>
	<table class="knowledge_table" width="100%">
		<tr class="knowledge_table_tr">
			<th>遇到問題</th>
			<th>解決方式</th>
			<th>關鍵字</th>
			<th>上傳者</th>
			<th>詳細情況</th>
		</tr>
		<tr>
			<td><span id="question"></span></td>
			<td><span id="way"></span></td>
			<td><span id="people"></span></td>
			<td><span id="people"></span></td>
			<td><span id="people"></span></td>
		</tr>
	</table>
</div>
<?php
	include("api/php/footer.php");
?>