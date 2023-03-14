<?php
	$page_url = '小組首頁';
	include("api/php/header.php");
?>
<div id="centercolumn">
	<div id="leftcolumn">
		<fieldset id="info_field">
			<legend>教師廣播室</legend>
			<table border="1" width="100%">
				<tr>
					<th width="11%">廣播小組</th>
					<th width="11%">類型</th>
					<th width="45%">廣播內容</th>
					<th width="15%">日期</th>
				</tr>
				<?php
					$sql = "SELECT * FROM `help` WHERE `t_u_id` = '".$_SESSION['p_id']."' && `objects` = '0' ORDER BY `help_time` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取小組名稱----------------------------------------------------------------
							$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['t_u_id']."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$pname = $p_row['pname'];
							// 廣播類型--------------------------------------------------------------------
							if($row['type'] == "0"){
								$help_type = "其他";
							}else if($row['type'] == "1"){
								$help_type = "開會通知";
							}else if($row['type'] == "2"){
								$help_type = "作業催繳";
							}else if($row['type'] == "3"){
								$help_type = "緊急事項";
							}
							echo "<tr>".
									"<td>".$pname."</td>".
								  	"<td>".$help_type."</td>".
								  	"<td>".$row['description']."</td>".
								  	"<td>".date('Y-m-d', strtotime($row['help_time']))."</td>".
								 "</tr>";
						}
					}else{
						echo "<tr><td colspan='4'>尚未有任何廣播。</td></tr>";
					}
				?>
			</table>
		</fieldset>
		<fieldset id="info_field">
			<legend>學生求助</legend>
			<button class="help_btn log_btn" value="前往求助"><img src="/co.in/science/model/images/project_suggest.png" width="20px;" style="vertical-align: bottom;">前往求助</button>
			<table border="1" width="100%">
				<tr>
					<th width="11%">發問小組</th>
					<th width="11%">類型</th>
					<th width="45%">發問內容</th>
					<th width="15%">日期</th>
				</tr>
				<?php
					$sql = "SELECT * FROM `help` WHERE `p_id` = '".$_SESSION['p_id']."' && `objects` = '1' ORDER BY `help_time` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							//抓取小組名稱----------------------------------------------------------------
							$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$pname = $p_row['pname'];
							//求助類型--------------------------------------------------------------------
							if($row['type'] == "0"){
								$help_type = "其他";
							}else if($row['type'] == "1"){
								$help_type = "器材問題";
							}else if($row['type'] == "2"){
								$help_type = "如何進行";
							}else if($row['type'] == "3"){
								$help_type = "小組溝通";
							}
							echo "<tr>".
									"<td>".$pname."</td>".
								  	"<td>".$help_type."</td>".
								  	"<td>".$row['description']."</td>".
								  	"<td>".date('Y-m-d', strtotime($row['help_time']))."</td>".
								  "</tr>";
						}
					}else{
						echo "<tr><td colspan='5'>尚未有任何求助內容。</td></tr>";
					}
				?>
			</table>
		</fieldset>
	</div>
	<div id="rightcolumn">
		<fieldset id="info_field" style="min-height: 340px;">
			<legend>待完成任務</legend>
			<?php
				// 抓取專題stage-----------------------------------------------------------
				$s_sql = "SELECT `stage` FROM `project` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				$s_row = mysql_fetch_array($s_qry);
					$project_stage = $s_row["stage"];
				// 抓取stage內容-----------------------------------------------------------
				$sql = "SELECT `stage`, `name`, `description` FROM `stage` WHERE `stage`= '".$project_stage."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<div class='stage_menu'>".
								"<div class='stage_title'>".
									$row['stage']." ".$row['name'].
								"</div>".
								"<div class='stage_describe'>".
									$row['description'].
									"<div class='stage_go log_btn' id='".$project_stage."' value='前往協作 》'>前往協作 》</div>".
								"</div>".
							 "</div>";
					}
				}else{
					echo "<div class='stage_menu'>".
								"<div class='stage_title'>".
									"已完成所有任務".
								"</div>".
								"<div class='stage_describe'>".
									"<div><center>目前無需進行之任務！</center></div>".
								"</div>".
							 "</div>";
				}
			?>
		</fieldset>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>