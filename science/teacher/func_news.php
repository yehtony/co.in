<?php
	$page_url = '<a href="index.php">專題指導</a> > 最新消息';
	include("api/php/header.php");
?>
<style>
.more_news_table{
	width: 90%;
	margin: 0px auto;
	border: 1px solid #000000;
	border-collapse: collapse;
}
.more_news_table th{
	padding: 8px;
	text-align: center;
	background-color: #69B0FA;
}
.more_news_table td{
	padding: 5px;
}
.more_news_table td a{
	color: #000000;
	text-decoration: none;
}
</style>
<script>
</script>
<div id="centercolumn">
	<h1 id="title">- 最新消息 -</h1>
	<hr />
	<table class="more_news_table">
	<tr>
		<th>消息類型</th>
		<th>消息主題</th>
		<th>發佈時間</th>
	</tr>
	<?php
		$sql = "SELECT * FROM `news` WHERE `u_id` = '".$_SESSION['UID']."' order by `n_id` DESC";
		$qry = mysql_query($sql, $link) or die(mysql_error());
		if(mysql_num_rows($qry) > 0){
			while($row = mysql_fetch_array($qry)){
				// 消息類型
				if($row['type'] == '1'){
					$type = '系統通知';
				}else if($row['type'] == '1'){
					$type = '求助回覆';
				}else if($row['type'] == '1'){
					$type = '審核通知';
				}else if($row['type'] == '1'){
					$type = '學習日誌';
				}
				echo "<tr>
						<td align='center'>".$type."</td>
						<td><a href='".$row['page_url']."'>".$row['title']."</a></td>
						<td align='center'>".$row['news_time']."</td>
					  </tr>";
			}
		}
	?>
	</table>
</div>
<?php
	include("api/php/footer.php");
?>