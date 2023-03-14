<?php
	// 抓取stage內容-----------------------------------------------------------
	include("../../../model/db_coin.php");
	$n_sql = "SELECT `name` FROM `stage` WHERE `stage`= '".$_GET['stage']."'";
	$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
	$n_row = mysql_fetch_array($n_qry);
		$stage_name = $n_row["name"];
	//顯示現在位置-----------------------------------------------------------
	$page_url = '<a href="../index.php">小組首頁</a> > <a href="../nav_project.php">小組協作空間</a> > '.$_GET['stage'].' '.$stage_name;
	include("../api/php/header.php");
?>
<div id="centercolumn">
	<div id="leftstage">
		<fieldset id="info_field" style="min-height: 280px;" data-step="4" data-intro="待完成任務：當前階段任務，並說明過關條件。" data-position='right'>
			<legend>待完成任務</legend>
			<?php
				$sql = "SELECT `stage`, `name`, `pass` FROM `stage` WHERE `stage`= '".$_GET['stage']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 倒數時間
						$stage_arr = array("1-1", "1-2", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4");
						for($i = 0; $i < count($stage_arr); $i++){
							if($_GET['stage'] == $stage_arr[$i]){
								$t_sql = "SELECT * FROM `project_schedule` WHERE `p_id`= '".$_SESSION['p_id']."'";
								$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
								while($t_row = mysql_fetch_array($t_qry)){
									if($t_row['exp'.$stage_arr[$i]] != "0000-00-00"){
										if($i == '0'){
											$seconds = (strtotime($t_row['exp'.$stage_arr[$i+1]])-strtotime('today GMT'));
										}else{
											$seconds = (strtotime($t_row['exp'.$stage_arr[$i]])-strtotime('today GMT'));
										}
										
										$exp_time = floor($seconds/(3600*24))."天";					// 天
										$exp_time .= floor(($seconds%(3600*24))/3600)."時";			// 時
										$exp_time .= floor((($seconds%(3600*24))%3600)/60)."分";	// 分
										$exp_time .= floor((($seconds%(3600*24))%3600)%60)."秒";	// 秒
									}else{
										$exp_time = "(尚未設定)";
									}
								}
							}
						}
						echo "<div class='stage_menu_small'>".
								"<div class='stage_title_small'>".
									"<div><img src='../../model/images/project_time.png' width='12px'>倒數時間：".$exp_time."</div>".
									"<h2 style='margin: 0px;'>".$row['stage']." ".$row['name']."<img src='../../model/images/project_info.png' width='25px' title='活動指引！' style='vertical-align: top;' class='stage_guide log_btn' value='開啟活動指引' data-step='5' data-intro='活動指引：開起活動指引說明。' data-position='right'></h2>".
								"</div>".
								"<div class='stage_describe_small'>".
									"<b>過關條件：</b><br />".$row['pass'].
								"</div>".
								"<div class='stage_interface log_btn' value='介面操作說明'><a href='javascript:void(0);' onclick='javascript:introJs().start();'>介面操作說明</a></div>".
							 "</div>";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			?>
		</fieldset>
	</div>
	<div id="rightstage" data-step='6' data-intro='小組協作空間：進行專題探究任務，小組協作之空間(例：討論區、小組工作室、小組資料庫。)。' data-position='left'>
		<ul class="function_stage" id="<?php echo $_GET['stage']; ?>">
			<li class="log_btn" id="discuss" value="討論區" data-step='7' data-intro='討論區：小組討論空間。'>討論區</li>
			<li class="log_btn" id="work" value="小組工作室" style="background-color: #59B791;" data-step='8' data-intro='小組工作室：進行專題探究任務，小組共同協作空間。');>小組工作室</li>
			<li class="log_btn" id="database" value="小組資料庫" data-step='9' data-intro='小組資料庫：將蒐集來的資料，皆存放於此。'>小組資料庫</li>
		</ul>
		<div id="page_work" class="function_pages">
		<?php
			include("stage/stage".$_GET['stage'].".php");
		?>
		</div>
	</div>
	<?php
		// 判斷閱讀指引是否已閱讀----------------------------------------------------------------------
		$guide_hint = 'style="display: block;"';		// 出現

		$h_sql = "SELECT `hint_state` FROM `project_group` WHERE `p_id`= '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."' AND `hint_state`= '0'";
		$h_qry = mysql_query($h_sql, $link) or die(mysql_error());
		if(mysql_num_rows($h_qry) > 0){
			$guide_hint = 'style="display: none;"';		// 消失
		}
			mysql_query($h_sql, $link) or die(mysql_error());
	?>
	<div class="fancybox_box" id="research_guide_fancybox" <?php echo $guide_hint; ?>>
		<div class="fancybox_area" id="research_guide_area">
			<?php
				$sql = "SELECT `stage`, `name`, `guide` FROM `stage` WHERE `stage`= '".$_GET['stage']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<h2>活動指引".$row['stage']." ".$row['name']."</h2><hr />".$row['guide'];
					}
				}else{
					echo "<p>已完成所有任務，已無任務提示囉！</p>";
				}
					mysql_query($sql, $link) or die(mysql_error());
			?>
			<input type='button' class='fancybox_btn' id='research_guide_submit' value='我知道了'>
		</div>
	</div>
		<div class="fancybox_box" id="discuss_new_fancybox">
		<div class="fancybox_area" id="discuss_new_area">
			<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
			<h2>- 新增討論串 -</h2>
			<form id="discuss_new_form" method="post">
				<p>
					<label>討論主題：</label>
					<input type="text" name="discuss_title">
				</p>
				<p>
					<label>討論階段：</label>
					<select name="discuss_stage">
						<option value="default">請選擇討論階段...</option>
						<?php
							$sql = "SELECT `stage`, `name` FROM `stage`";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['stage']."'>".$row['stage']." ".$row['name']."</option>";
								}
							}
							mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<p>
					<label>討論種類：</label>
					<select name="discuss_type">
						<option value="0">一般討論</option>
						<option value="1">主題討論</option>
					</select>
				</p>
				<p>
					<label>討論說明：</label>
					<textarea name="discuss_description"></textarea>
				</p>
				<p>
					<label>附加檔案：</label>
					<input type="file" name="files">
				</p>
				<input type="button" class="fancybox_btn" id="discuss_add" value="新增討論">
			</form>
		</div>
	</div>
</div>
<?php
	include("../api/php/footer.php");
?>