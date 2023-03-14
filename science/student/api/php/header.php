<?php
	if(session_id() == ''){
		session_start();
	}
	// 絕對路徑
	define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);							// C:/xampp/htdocs
	define('SITE_ROOT', DOC_ROOT.'/co.in/'); 								// C:/xampp/htdocs/co.in/
	define('SITE_PATH', 'http://'.$_SERVER['HTTP_HOST'].'/co.in/science/');	// C:/xampp/htdocs/co.in/science/

	include(SITE_ROOT."model/db_coin.php");

	$sql = "SELECT SUM(news_read) AS unread FROM `news` WHERE `u_id` = '".$_SESSION['UID']."' ORDER BY `n_id`";
	$qry = mysql_query($sql, $link) or die(mysql_error());
	$row = mysql_fetch_array($qry);
	if($row['unread'] == 0){
		$unread = "";
	}else{
		$unread = "<span id='news_bubble'>".$row['unread']."</span>";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>專題探究學習系統 | 學生</title>
	<!-- Add icon、style CSS、jQuery library、science js-->
	<link rel="icon" href="<?php echo SITE_PATH; ?>model/images/coin.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo SITE_PATH; ?>student/api/css/style.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<!-- Add jQuery UI -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<!-- Add jQuery form -->
	<script src="http://malsup.github.com/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo SITE_PATH; ?>student/api/js/science.js"></script>
	<!-- Add loading AJAX setting -->
	<script type="text/javascript" src="/co.in/plugin/Loading/loading.js"></script>
	<!-- Add loading introjs.css setting -->
	<link href="/co.in/plugin/Intro/introjs.css" rel="stylesheet">
	<script type="text/javascript" src="/co.in/plugin/Intro/intro.js"></script>
</head>
<body class="loading">
<div id="wrapper">
	<div id="header">
		<button class="log_btn" id="func_btn" value="個人區" data-step="3" data-intro="個人區：觀看個人帳號、問題集、系統操作說明和登出系統。" data-position='left'><?php echo $_SESSION['name'];?></button>
		<button class="log_btn" id="news_btn" value="最新通知" data-step="2" data-intro="通知區：這裡可以收到最新通知，以便知道最新的小組情形。">通知<?php echo $unread;?></button>
		<div class="log_btn" id="index_btn" value="專題探究學習系統LOGO" data-step="1" data-intro="您好，歡迎來到Let's Inquiry！在這裡跟小組一起完成科展專題吧: D">
			<div id="logo"><img src="<?php echo SITE_PATH; ?>model/images/logo.png"></div>
			<div id="sub_logo"><img src="<?php echo SITE_PATH; ?>model/images/logo_title.png"></div>
			<!-- <h1 id="topic">專題探究學習系統</h1>
			<h3 id="sub_topic">Online Science Fair Inquiry System</h3> -->
		</div>
	</div>
	<div id="news_table">
		<table width='100%'>
			<?php
				$sql = "SELECT * FROM `news` WHERE `u_id` = '".$_SESSION['UID']."' ORDER BY `n_id` DESC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){
							$type = '系統通知';
						}else if($row['type'] == '1'){
							$type = '求助回覆';
						}else if($row['type'] == '2'){
							$type = '審核通知';
						}else if($row['type'] == '3'){
							$type = '學習日誌';
						}else{
							$type = '其他';
						}
						echo "<tr>
								<th width='16%' valign='top'>【".$type."】</th>
								<td width='84%'><a href='".$row['page_url']."'>".$row['title']."</a></td>
							  </tr>";
					}
					echo "<tr>
							<td align='center' colspan='2' style='border-top: 1px solid #000000; padding-top: 5px;'>
								<span class='log_btn' id='news_more' value='最新通知[more...]'>顯示更多消息...</span>
							</td>
						  </tr>";
				}else{
					echo "<tr colspan='2'><td align='center'>尚未有任何通知。</td></tr>";
				}
			?>
		</table>
	</div>
	<div id="func_table">
		<ul>
			<li class='log_btn' value="帳號管理" onclick="location.href='<?php echo SITE_PATH; ?>student/func_account.php'">帳號管理</li>
			<li class='log_btn' id="system_btn" value="系統操作">系統操作</li>
			<li class='log_btn' value="問題集" onclick="location.href='<?php echo SITE_PATH; ?>student/func_question.php'">問題集</li>
			<li class='log_btn' id="logout_btn" value="登出">登出</li>
		</ul>
	</div>
	<div id="nav" class="<?php if(isset($_SESSION['p_id']) == ""){ echo 'display'; }?>">
		<button class='log_btn' value="小組首頁" onclick="location.href='<?php echo SITE_PATH; ?>student/index.php'" >小組首頁</button>
		<button class='log_btn' value="小組協作空間" onclick="location.href='<?php echo SITE_PATH; ?>student/nav_project.php'">小組協作空間</button>
		<button class='log_btn' value="日誌專區" onclick="location.href='<?php echo SITE_PATH; ?>student/nav_diary.php'">日誌專區</button>
		<p class="log_btn" value="現在位置">現在位置：<?php echo $page_url; ?></p>
	</div>
	<div class="guide_scroll">
		<div class="guide_tab <?php if(isset($_SESSION['p_id']) == ""){ echo "display"; }?> log_btn" value="任務地圖">
			任務地圖
		</div>
		<div class="guide_content">
			<ul class="guide_map_list">
			<?php
				// 抓取專題stage-----------------------------------------------------------
				$s_sql = "SELECT `stage` FROM `project` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				$s_row = mysql_fetch_array($s_qry);
					$project_stage = $s_row["stage"];
					
				if($project_stage == '1-1'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1'>2-1</li>
						  <li id='guide_map_2-2'>2-2</li>
						  <li id='guide_map_2-3'>2-3</li>
						  <li id='guide_map_2-4'>2-4</li>
						  <li id='guide_map_3-1'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '2-1'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2'>2-2</li>
						  <li id='guide_map_2-3'>2-3</li>
						  <li id='guide_map_2-4'>2-4</li>
						  <li id='guide_map_3-1'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '2-2'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3'>2-3</li>
						  <li id='guide_map_2-4'>2-4</li>
						  <li id='guide_map_3-1'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '2-3'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4'>2-4</li>
						  <li id='guide_map_3-1'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '2-4'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '3-1'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '3-2'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '3-3'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '4-1'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '4-2'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '5-1'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1' style='background-color: #FFEAAE;'>5-1</li>
						  <li id='guide_map_5-2'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '5-2'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1' style='background-color: #FFEAAE;'>5-1</li>
						  <li id='guide_map_5-2' style='background-color: #FFEAAE;'>5-2</li>
						  <li id='guide_map_5-3'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '5-3'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1' style='background-color: #FFEAAE;'>5-1</li>
						  <li id='guide_map_5-2' style='background-color: #FFEAAE;'>5-2</li>
						  <li id='guide_map_5-3' style='background-color: #FFEAAE;'>5-3</li>
						  <li id='guide_map_5-4'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '5-4'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1' style='background-color: #FFEAAE;'>5-1</li>
						  <li id='guide_map_5-2' style='background-color: #FFEAAE;'>5-2</li>
						  <li id='guide_map_5-3' style='background-color: #FFEAAE;'>5-3</li>
						  <li id='guide_map_5-4' style='background-color: #FFEAAE;'>5-4</li>
						  <li id='guide_map_finish'>完成</li>";
				}else if($project_stage == '5-5'){
					echo "<li id='guide_map_1-1' style='background-color: #FFEAAE;'>1-1</li>
						  <li id='guide_map_2-1' style='background-color: #FFEAAE;'>2-1</li>
						  <li id='guide_map_2-2' style='background-color: #FFEAAE;'>2-2</li>
						  <li id='guide_map_2-3' style='background-color: #FFEAAE;'>2-3</li>
						  <li id='guide_map_2-4' style='background-color: #FFEAAE;'>2-4</li>
						  <li id='guide_map_3-1' style='background-color: #FFEAAE;'>3-1</li>
						  <li id='guide_map_3-2' style='background-color: #FFEAAE;'>3-2</li>
						  <li id='guide_map_3-3' style='background-color: #FFEAAE;'>3-3</li>
						  <li id='guide_map_4-1' style='background-color: #FFEAAE;'>4-1</li>
						  <li id='guide_map_4-2' style='background-color: #FFEAAE;'>4-2</li>
						  <li id='guide_map_5-1' style='background-color: #FFEAAE;'>5-1</li>
						  <li id='guide_map_5-2' style='background-color: #FFEAAE;'>5-2</li>
						  <li id='guide_map_5-3' style='background-color: #FFEAAE;'>5-3</li>
						  <li id='guide_map_5-4' style='background-color: #FFEAAE;'>5-4</li>
						  <li id='guide_map_finish' style='background-color: #FFEAAE;'>完成</li>";
				}
			?>
			</ul>
			<div id="guide_mascot_<?php if(isset($_SESSION['p_id']) != ""){ echo $project_stage; } ?>"><img src="<?php if(isset($_SESSION['p_id']) != ""){ echo $_SESSION['mascot']; } ?>"></div>
			<div class="guide_illustrate">
				<h3>地圖說明</h3>
				<p>此為任務地圖，吉祥物所在為"當前階段"，黃色表示"已完成任務"，灰色表示"尚未開始此任務"，可點選階段觀看成果...</p>
				<button class="guide_examine log_btn" value="審核紀錄">審核紀錄</button>
			</div>
			<div class="guide_box">
				<h3 class="guide_stage"></h3>
				<span class="guide_result"></span><br />
				<span class="guide_comment"></span><br />
				<span class="guide_complete log_btn" id="<?php echo $project_stage; ?>" value="觀看成果 <?php echo $project_stage; ?>">觀看成果...</span>
			</div>
		</div>
		<div class="fancybox_box" id="guide_examine_fancybox">
			<div class="fancybox_area" id="guide_examine_area">
				<div class="fancybox_cancel"><img src="<?php echo SITE_PATH; ?>model/images/project_close.png" width="20px"></div>
				<h2>- 審核紀錄 -</h2>
				<table class="guide_examine_table">
					<tr>
						<th width="15%">階段</th>
						<th width="20%">狀態</th>
						<th width="45%">評語</th>
						<th width="20%">時間</th>
					</tr>
					<?php
						// 任務審核
						$sql = "SELECT * FROM `project_examine` WHERE `p_id` = '".$_SESSION['p_id']."'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
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
										"<td align='center'>".$guide_result."</td>".
										"<td>".$row['comment']."</td>".
										"<td>".$examine_end_time."</td>".
									 "</tr>";
							}
						}else{
							echo "<tr><td colspan='4'>尚未有任何審核紀錄！</td></tr>";
						}
						mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
				<input type="button" class="fancybox_btn" id="guide_examine_check" value="確定">
			</div>
		</div>
	</div>
	<div class="tool_scroll">
		<div class="tool_tab <?php if(isset($_SESSION['p_id']) == ""){ echo "display"; }?>  log_btn" value="工具包">
			工具包
		</div>
		<div class="tool_content">
			<button class='log_btn' value="觀摩專題作品" onclick="location.href='<?php echo SITE_PATH; ?>student/tool_search.php'">觀摩專題作品</button>
			<button class='log_btn' value="學生求助" onclick="location.href='<?php echo SITE_PATH; ?>student/tool_suggest.php'">學生求助</button>
			<button class='log_btn' value="常用網站" onclick="location.href='<?php echo SITE_PATH; ?>student/tool_website.php'">常用網站</button>
		</div>
	</div>
	<div class="fancybox_box" id="system_use_fancybox">
		<div class="fancybox_area" id="system_use_area">
			<div class="fancybox_cancel"><img src="/co.in/science/model/images/project_close.png" width="20px"></div>
			<h1 id="title">- 系統使用說明 -</h1>
			<div id="leftsystem">
				<ul class="system_menu">
					<li value="1">歡迎Let's Inquiry</li>
					<li value="2">學生介面介紹</li>
					<li value="3">申請小組</li>
					<li value="4">小組協作空間</li>
					<li value="5">任務地圖</li>
					<li value="6">日誌專區</li>
					<li value="7">觀摩專題作品</li>
					<li value="8">學生求助</li>
					<li value="9">常用網站</li>
				</ul>
			</div>
			<div id="rightsystem">
				<iframe width="720px" height="500px" src="//www.youtube.com/embed/SxiW3lyGDW0" frameborder="0" allowfullscreen id="video_frame"></iframe>
			</div>
		</div>
	</div>
	<div id="content" <?php if(isset($_SESSION['p_id']) == ""){ echo "style='margin-top: 82px;'"; }?>>
	<!-- 中間內容 -->