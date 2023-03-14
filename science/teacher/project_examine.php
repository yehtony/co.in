<?php
	$page_url = '<a href="index.php">專題指導</a> > 歷史審核紀錄';
	include("api/php/header.php");
?>
<style>
.more_examine_table{
	width: 90%;
	margin: 0px auto;
	border: 1px solid #000000;
	border-collapse: collapse;
}
.more_examine_table th{
	padding: 8px;
	text-align: center;
	background-color: #69B0FA;
}
.more_examine_table td{
	padding: 5px;
}
</style>
<script>
</script>
<div id="centercolumn">
	<h1 id="title">- 歷史審核紀錄 -</h1>
<hr />
	<table class="more_examine_table">
		<tr>
			<th width="15%">階段</th>
			<th width="15%">小組</th>
			<th width="20%">狀態</th>
			<th width="30%">評語</th>
			<th width="20%">時間</th>
		</tr>
		<?php
			// 任務審核
			$sql = "SELECT * FROM `project_examine` WHERE `t_id` = '".$_SESSION['UID']."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					// 抓取小組名稱
					$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
					$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
					$p_row = mysql_fetch_array($p_qry);
						$pname = $p_row['pname'];
					// 審核狀態
					if($row['result'] == '0'){
						$guide_result = '過關';
					}else if($row['result'] == '1'){
						$guide_result = '審核中...';
					}else if($row['result'] == '2'){
						$guide_result = '未過關';
					}
					// 審核時間
					if($row['examine_end_time'] == '0000-00-00 00:00:00'){
						$examine_end_time = '0000-00-00';
					}else{
						$examine_end_time = date('Y-m-d', strtotime($row['examine_end_time']));
					}
					echo "<tr>".
							"<td align='center'>".$row['stage']."</td>".
							"<td align='center'>".$pname."</td>".
							"<td align='center'>".$guide_result."</td>".
							"<td>".$row['comment']."</td>".
							"<td align='center'>".$examine_end_time."</td>".
						 "</tr>";
					}
			}else{
					echo "<tr><td colspan='4'>尚未有任何審核紀錄！</td></tr>";
			}
				mysql_query($sql, $link) or die(mysql_error());
		?>
	</table>
</div>
<?php
	include("api/php/footer.php");
?>