<?php
	$page_url = '<a href="index.php">小組首頁</a> > 小組協作空間';
	include("api/php/header.php");
?>
<style>
/*-----------------------------------小組協作空間-----------------------------------*/
#title{
	text-align: left;
}
.project_box{
	float: left;
	width: 280px;
	height: 160px;
	padding: 40px;
	margin: 10% 8%;
	border: 1px solid #000000;
	cursor: pointer;
}
.project_box:hover{
	background-color: #eee;
}
.project_lock_box{
	float: left;
	width: 280px;
	height: 160px;
	padding: 40px;
	margin: 10% 8%;
	background: rgba(0,0,0,0.33);
	border: 1px solid #000000;
	cursor: not-allowed;
}
.project_lock{
	position : relative;
	top: 20px;
	left : 80px;
}
.project_remind{
	padding-top: 220px;
	font-size: 180%;
	text-align: center;
}
.project_remind span{
	font-size: 220%;
	font-weight: bolder;
}
</style>
<script>
$(function(){
/*-----------------------------------小組協作空間-----------------------------------*/
	$(".project_box").click(function(){
		// window.location.href = "project/stage" + this.id + ".php";
		// window.location.href = "project/?stage="+ this.id;
		window.location.href = "project/discuss.php?stage="+ this.id;
	});
});
</script>
<div id="centercolumn">
	<?php
		// 反思日誌 是否已填寫完-------------------------------------------------
		$r_sql = "SELECT `reflection_state` FROM `project_group` WHERE `p_id`= '".$_SESSION['p_id']."' AND `s_id`= '".$_SESSION['UID']."'";
		$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
		$r_row = mysql_fetch_array($r_qry);
			$reflection_state = $r_row["reflection_state"];
			
		if($reflection_state != '0'){
			echo "<div class='project_remind'>請先完成<span>反思日誌</span>之撰寫！</div>";
		}else{
			echo "<h1 id='title'>你想完成哪一項任務？</h1>";

			$stage_arr = array("1-1", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4", "5-5", "5-6"); // 為配合next_stage 5-6
			// 抓取專題stage-----------------------------------------------------------
			$s_sql = "SELECT `stage` FROM `project` WHERE `p_id`= '".$_SESSION['p_id']."'";
			$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
			$s_row = mysql_fetch_array($s_qry);
				$project_stage = $s_row["stage"];
			// 與陣列比對stage-----------------------------------------------------------
			for($i = 0; $i < count($stage_arr); $i++){
				if($project_stage == $stage_arr[$i]){
					$current_stage = $stage_arr[$i];
					$next_stage = $stage_arr[$i+1];
				}
			}
			if($current_stage == '5-5'){
				echo "<div class='project_remind'>恭喜你們已完成全部專題小組任務！</div>";
			}else{
				// 抓取stage內容-----------------------------------------------------------
				$sql = "SELECT `stage`, `name`, `description` FROM `stage` WHERE `stage`= '".$current_stage."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<div class='project_box log_btn' id='".$row['stage']."' value='前往【".$row['stage']." ".$row['name']."】'>".
								"<h2>".$row['stage']." ".$row['name']."</h2>".
								"<p>".$row['description']."</p>".
							 "</div>";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());	

				$sql = "SELECT `stage`, `name`, `description` FROM `stage` WHERE `stage`= '".$next_stage."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<div class='project_lock_box'>".
								"<div class='project_lock'><img src='../model/images/project_lock.png' width='120px'></div>".
								"<div style='margin-top: -130px;'>".
									"<h2>".$row['stage']." ".$row['name']."</h2>".
									"<p>".$row['description']."</p>".
								"</div>".
							 "</div>";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}
		}
	?>
</div>
<?php
	include("api/php/footer.php");
?>