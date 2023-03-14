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
			<li class="log_btn" id="discuss" value="討論區" style="background-color: #59B791;" data-step='7' data-intro='討論區：小組討論空間。'>討論區</li>
			<li class="log_btn" id="work" value="小組工作室" data-step='8' data-intro='小組工作室：進行專題探究任務，小組共同協作空間。');>小組工作室</li>
			<li class="log_btn" id="database" value="小組資料庫" data-step='9' data-intro='小組資料庫：將蒐集來的資料，皆存放於此。'>小組資料庫</li>
		</ul>
		<div id="page_discuss" class="function_pages">
			<div class="discuss_select">
				過濾器：<select>
							<option value="all">全部討論串</option>
							<option value="star">打星號</option>
							<option value="new">最新討論</option>
						</select>
				<span><img src='../../model/images/discuss_down.png' width='40px'>最新向上</span>
				<span><img src='../../model/images/discuss_hot.png' width='40px'>最熱門</span>
				<span><img src='../../model/images/discuss_setting.png' width='40px'>追蹤討論串設定</span>
				<ul class="discuss_stage">
					<li id="stage1">第一階段：提問</li>
					<li id="stage2">第二階段：規劃</li>
					<li id="stage3">第三階段：執行</li>
					<li id="stage4">第四階段：形成結論</li>
					<li id="stage5">第五階段：報告與展示</li>
				</ul>
			</div>
			<div id="page_stage1" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串" data-step='10' data-intro='新增討論串：按此新增討論串！'>
					+ 新增討論串
				</div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage`= '1-1' ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
							// 按讚
							if($good == '0'){
								$good_img = 'ungood';
							}else{
								$good_img = 'good';
							}
							// 星號
							if($star == '0'){
								$star_img = 'unstar';
							}else{
								$star_img = 'star';
							}
							// 追蹤
							if($bookmark == '0'){
								$bookmark_img = 'unbookmark';
							}else{
								$bookmark_img = 'bookmark';
							}
							// 計算討論區活動(GOOD)
							$num = 0;

							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							// 顯示區域
							if($row['stage'] == '1-1' || $row['stage'] == '1-2'){
								$area = 1;
							}else if($row['stage'] == '2-1' || $row['stage'] == '2-2' || $row['stage'] == '2-3' || $row['stage'] == '2-4'){
								$area = 2;
							}else if($row['stage'] == '3-1' || $row['stage'] == '3-2' || $row['stage'] == '3-3'){
								$area = 3;
							}else if($row['stage'] == '4-1' || $row['stage'] == '4-2'){
								$area = 4;
							}else if($row['stage'] == '5-1' || $row['stage'] == '5-2' || $row['stage'] == '5-3' || $row['stage'] == '5-4'){
								$area = 5;
							}
							
							echo "<div class='discuss_box discuss".$area."' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn' data-step='11' data-intro='討論區功能列：可選擇所要使用的功能(例：按讚、星號、聊天)。' data-position='right'>".
										"<span class='log_btn' value='按讚'><img src='../../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
							}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage2" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串" >
					+ 新增討論串
				</div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND (`stage`= '2-1' OR `stage`= '2-2' OR `stage`= '2-3' OR `stage`= '2-4') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
							// 按讚
							if($good == '0'){
								$good_img = 'ungood';
							}else{
								$good_img = 'good';
							}
							// 星號
							if($star == '0'){
								$star_img = 'unstar';
							}else{
								$star_img = 'star';
							}
							// 追蹤
							if($bookmark == '0'){
								$bookmark_img = 'unbookmark';
							}else{
								$bookmark_img = 'bookmark';
							}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							// 顯示區域
							if($row['stage'] == '1-1' || $row['stage'] == '1-2'){
								$area = 1;
							}else if($row['stage'] == '2-1' || $row['stage'] == '2-2' || $row['stage'] == '2-3' || $row['stage'] == '2-4'){
								$area = 2;
							}else if($row['stage'] == '3-1' || $row['stage'] == '3-2' || $row['stage'] == '3-3'){
								$area = 3;
							}else if($row['stage'] == '4-1' || $row['stage'] == '4-2'){
								$area = 4;
							}else if($row['stage'] == '5-1' || $row['stage'] == '5-2' || $row['stage'] == '5-3' || $row['stage'] == '5-4'){
								$area = 5;
							}
							
							echo "<div class='discuss_box discuss".$area."' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
							}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage3" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串" >
					+ 新增討論串
				</div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND (`stage`= '3-1' OR `stage`= '3-2' OR `stage`= '3-3') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
							// 按讚
							if($good == '0'){
								$good_img = 'ungood';
							}else{
								$good_img = 'good';
							}
							// 星號
							if($star == '0'){
								$star_img = 'unstar';
							}else{
								$star_img = 'star';
							}
							// 追蹤
							if($bookmark == '0'){
								$bookmark_img = 'unbookmark';
							}else{
								$bookmark_img = 'bookmark';
							}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							// 顯示區域
							if($row['stage'] == '1-1' || $row['stage'] == '1-2'){
								$area = 1;
							}else if($row['stage'] == '2-1' || $row['stage'] == '2-2' || $row['stage'] == '2-3' || $row['stage'] == '2-4'){
								$area = 2;
							}else if($row['stage'] == '3-1' || $row['stage'] == '3-2' || $row['stage'] == '3-3'){
								$area = 3;
							}else if($row['stage'] == '4-1' || $row['stage'] == '4-2'){
								$area = 4;
							}else if($row['stage'] == '5-1' || $row['stage'] == '5-2' || $row['stage'] == '5-3' || $row['stage'] == '5-4'){
								$area = 5;
							}
							
							echo "<div class='discuss_box discuss".$area."' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
							}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage4" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串" >
					+ 新增討論串
				</div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND (`stage`= '4-1' OR `stage`= '4-2') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
							// 按讚
							if($good == '0'){
								$good_img = 'ungood';
							}else{
								$good_img = 'good';
							}
							// 星號
							if($star == '0'){
								$star_img = 'unstar';
							}else{
								$star_img = 'star';
							}
							// 追蹤
							if($bookmark == '0'){
								$bookmark_img = 'unbookmark';
							}else{
								$bookmark_img = 'bookmark';
							}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							// 顯示區域
							if($row['stage'] == '1-1' || $row['stage'] == '1-2'){
								$area = 1;
							}else if($row['stage'] == '2-1' || $row['stage'] == '2-2' || $row['stage'] == '2-3' || $row['stage'] == '2-4'){
								$area = 2;
							}else if($row['stage'] == '3-1' || $row['stage'] == '3-2' || $row['stage'] == '3-3'){
								$area = 3;
							}else if($row['stage'] == '4-1' || $row['stage'] == '4-2'){
								$area = 4;
							}else if($row['stage'] == '5-1' || $row['stage'] == '5-2' || $row['stage'] == '5-3' || $row['stage'] == '5-4'){
								$area = 5;
							}
							
							echo "<div class='discuss_box discuss".$area."' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
							}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_stage5" class="discuss_pages">
				<div class="discuss_box_new log_btn" value="新增討論串" >
					+ 新增討論串
				</div>
				<?php
					$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND (`stage`= '5-1' OR `stage`= '5-2' OR `stage`= '5-3' OR `stage`= '5-4') ORDER BY `d_id` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 抓取發言類型
							if($row['type'] == '0'){
								$type = '一般討論';
							}else if($row['type'] == '1'){
								$type = '主題討論';
							}
							// 讀取討論區活動(GOOD)
							$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
							$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
							$f_row = mysql_fetch_array($f_qry);
								$good = $f_row['good'];
								$star = $f_row['star'];
								$bookmark = $f_row['bookmark'];
							// 按讚
							if($good == '0'){
								$good_img = 'ungood';
							}else{
								$good_img = 'good';
							}
							// 星號
							if($star == '0'){
								$star_img = 'unstar';
							}else{
								$star_img = 'star';
							}
							// 追蹤
							if($bookmark == '0'){
								$bookmark_img = 'unbookmark';
							}else{
								$bookmark_img = 'bookmark';
							}
							// 計算討論區活動(GOOD)
							$g_sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$row['d_id']."' AND `good` = '1'";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$num = $g_row['num'];
							// 顯示區域
							if($row['stage'] == '1-1' || $row['stage'] == '1-2'){
								$area = 1;
							}else if($row['stage'] == '2-1' || $row['stage'] == '2-2' || $row['stage'] == '2-3' || $row['stage'] == '2-4'){
								$area = 2;
							}else if($row['stage'] == '3-1' || $row['stage'] == '3-2' || $row['stage'] == '3-3'){
								$area = 3;
							}else if($row['stage'] == '4-1' || $row['stage'] == '4-2'){
								$area = 4;
							}else if($row['stage'] == '5-1' || $row['stage'] == '5-2' || $row['stage'] == '5-3' || $row['stage'] == '5-4'){
								$area = 5;
							}
							
							echo "<div class='discuss_box discuss".$area."' id='".$row['d_id']."'>".
									"<p>".$name." 【".$row['stage']."】 <span class='discuss_type'>".$type."</span> 日期：".date("Y-m-d",strtotime($row['discussion_time']))."</p>".
									"<p class='discuss_indent'>".$row['title']."</p>".
									"<p class='discuss_btn'>".
										"<span class='log_btn' value='按讚'><img src='../../model/images/discuss_".$good_img.".png' width='45px' class='discuss_good' id='good".$row['d_id']."' value='".$good."'><span class='discuss_number' id='number".$row['d_id']."'>".$num."</span></span>".
										"<span class='log_btn' value='註記星號'><img src='../../model/images/discuss_".$star_img.".png' width='45px' class='discuss_star' id='star".$row['d_id']."' value='".$star."'></span>".
										"<span class='log_btn' value='回覆討論'><img src='../../model/images/discuss_chat.png' width='50px' class='discuss_chat'></span>".
									"</p>".
								 "</div>";
							}
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
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
			<input type='button' class='fancybox_btn log_btn' id='research_guide_submit' value='我知道了！'>
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
	<div class="fancybox_box" id="discuss_read_fancybox">
		<div class="fancybox_area" id="discuss_read_area">
			<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
			<h2>- 讀取討論串 -</h2>
			<p>發言人：<span id="discuss_user"></span></p>
			<p>標題：<span id="discuss_title"></span></p>
			<p>內容：<span id="discuss_description"></span></p>
			<p>附件：<span id="discuss_filename"></span></p>
		<hr />
			<p id="discuss_reply">目前無任何回應</p>
			<p><span id="discuss_reply_content"></span></p>
		<hr />
			<form id="discuss_read_form">
				<p style="display: none;">討論ID：<input type="text" name="discuss_id" /></p>
				<p><select name="discuss_read_type">
					<option value="1">提出個人意見</option>
					<option value="2">提出不同的意見</option>
					<option value="3">提出理由</option>
					<option value="4">提供佐證資料</option>
					<option value="5">舉例</option>
					<option value="6">做總結</option>
				</select>
				<input type="text" name="discuss_read_content" size="35" />
				<input type="button" class="fancybox_btn" id="discuss_read_add" value="回覆" /></p>
				<p id="discuss_filename">附加檔案：<input type="file" name="files" /></p>
			</form>
		</div>
	</div>
</div>
<?php
	include("../api/php/footer.php");
?>