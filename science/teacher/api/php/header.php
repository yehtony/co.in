<?php
	if(session_id() == ''){
		session_start();
	}
	// 絕對路徑
	define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);							// C:/xampp/htdocs
	define('SITE_ROOT', DOC_ROOT.'/co.in/'); 								// C:/xampp/htdocs/co.in/
	define('SITE_PATH', 'http://'.$_SERVER['HTTP_HOST'].'/co.in/science/');	// C:/xampp/htdocs/co.in/science/

	include(SITE_ROOT."model/db_coin.php");

	// 消息未讀數
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
	<title>專題探究學習系統 | 老師</title>
	<!-- Add icon、style CSS、jQuery library、science js-->
	<link rel="icon" href="<?php echo SITE_PATH; ?>model/images/coin.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo SITE_PATH; ?>teacher/api/css/style.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<!-- Add jQuery form -->
	<script src="http://malsup.github.com/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo SITE_PATH; ?>teacher/api/js/science.js"></script>
	<!-- Add loading AJAX setting -->
	<script type="text/javascript" src="<?php echo SITE_PATH; ?>teacher/api/js/loading.js"></script>
	<!-- Add loading introjs.css setting -->
	<link href="/co.in/plugin/Intro/introjs.css" rel="stylesheet">
	<script type="text/javascript" src="/co.in/plugin/Intro/intro.js"></script>
</head>
<body class="loading">
<div id="wrapper">
	<div id="header">
		<button class="log_btn" id="func_btn" value="個人區" data-step="3" data-intro="個人區：觀看個人帳號、問題集、系統操作說明和登出系統。" data-position='left'><?php echo $_SESSION['name'];?></button>
		<button class="log_btn" id="news_btn" value="最新通知" data-step="2" data-intro="通知區：這裡可以收到最新通知，以便知道最新的小組情形。">通知<?php echo $unread;?></button>
		<div class="log_btn" id="index_btn" value="專題探究學習系統LOGO" data-step="1" data-intro="您好，歡迎來到Let's Inquiry！在這裡跟您的小組一起完成科學專題吧: D">
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
							$type = '學生求助';
						}else if($row['type'] == '2'){
							$type = '階段審核';
						}else if($row['type'] == '3'){
							$type = '指導日誌';
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
					echo "<tr><td colspan='2' align='center'>尚未有任何通知。</td></tr>";
				}
			?>
		</table>
	</div>
	<div id="func_table">
		<ul>
			<li class='log_btn' value="帳號管理" onclick="location.href='func_account.php'">帳號管理</li>
			<li class='log_btn' id="system_btn" value="系統操作">系統操作</li>
			<li class='log_btn' value="問題集" onclick="location.href='func_question.php'">問題集</li>
			<li class='log_btn' id="logout_btn" value="登出">登出</li>
		</ul>
	</div>
	<div id="nav" data-step="4" data-intro="主功能區：可選擇專題指導、專題管理、教師日誌和已結案專題。">
		<button class='log_btn' value="專題指導" onclick="location.href='index.php'">專題指導</button>
		<button class='log_btn' value="專題管理" onclick="location.href='nav_project.php'">專題管理</button>
		<button class='log_btn' value="教師日誌" onclick="location.href='nav_diary.php'">教師日誌</button>
		<button class='log_btn' value="已結案專題" onclick="location.href='nav_finish.php'">已結案專題</button>
 		<!-- <button onclick="location.href='nav_knowledge.php'">我的知識庫</button> -->
		<!-- <button onclick="location.href='nav_share.php'">我的專案</button> -->
		<p class="log_btn" value="現在位置">現在位置：<?php echo $page_url; ?></p>
	</div>
	<div class="guide_scroll">
		<div class="guide_tab log_btn" value="教師指導手冊" data-step="5" data-intro="教師指導手冊：可參考內容，完成其科學專題任務。" data-position='right'>
			教師指導手冊
		</div>
		<div class="guide_content">
			<div class="guide_menu">
				<ul>
					<li class='has-sub'><a href='#'>科學專題介紹</a>
						<ul>
							<li id="1"><a href='#'>一、前言</a></li>
							<li id="2"><a href='#'>二、教師時程表</a></li>
							<li id="3"><a href='#'>三、教學規劃</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>第一階段：提問</a>
						<ul>
							<li id="4"><a href='#'>一、決定研究主題</a></li>
							<li id="5"><a href='#'>二、決定研究題目</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>第二階段：規劃</a>
						<ul>
							<li id="6"><a href='#'>一、提出假設</a></li>
							<li id="7"><a href='#'>二、設計實驗</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>第三階段：執行</a>
						<ul>
							<li id="8"><a href='#'>一、進行實驗</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>第四階段：形成結論</a>
						<ul>
							<li id="9"><a href='#'>一、數計分析及繪圖</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>第五階段：報告與展示</a>
						<ul>
							<li id="10"><a href='#'>一、作品說明書</a></li>
							<li id="11"><a href='#'>二、課堂展示</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="guide_book" onselectstart="return false;" ondragstart="return false;" oncontextmenu="return false;"> <!-- 停用右鍵及反白 -->
				<div class="guide_book_content">
				<?php
					$sql = "SELECT `content` FROM `guide` WHERE `g_id`= '1'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					while($row = mysql_fetch_array($qry)){
						echo $row['content'];
					}
				?>
				</div>
			</div>
			<div class="guide_hint_area">
				<h5><img src="<?php echo SITE_PATH; ?>model/images/guide_light.png" width='18px' style='vertical-align: middle;'>閱讀提示：</h5>
			</div>
		</div>
	</div>
	<div class="tool_scroll">
		<div class="tool_tab log_btn" value="工具包" data-step="6" data-intro="工具包：觀摩專題作品、建議與回報和常用網站已做參考。" data-position='left'>
			工具包
		</div>
		<div class="tool_content">
			<button class='log_btn' value="觀摩專題作品" onclick="location.href='tool_search.php'">觀摩專題作品</button>
			<button class='log_btn' value="建議與回報" onclick="location.href='tool_suggest.php'">建議與回報</button>
			<button class='log_btn' value="常用網站" onclick="location.href='tool_website.php'">常用網站</button>
		</div>
	</div>
	<div class="fancybox_box" id="system_use_fancybox">
		<div class="fancybox_area" id="system_use_area">
			<div class="fancybox_cancel"><img src="/co.in/science/model/images/project_close.png" width="20px"></div>
			<h1 id="title">- 系統使用說明 -</h1>
			<div id="leftsystem">
				<ul class="system_menu">
					<li value="1">歡迎Let's Inquiry</li>
					<li value="2">老師介面介紹</li>
					<li value="3">教師指導手冊</li>
					<li value="4">專題管理</li>
					<li value="5">教師日誌</li>
					<li value="6">已結案專題</li>
					<li value="7">觀摩專題作品</li>
					<li value="8">建議與回報</li>
					<li value="9">常用網站</li>
				</ul>
			</div>
			<div id="rightsystem">
				<iframe width="720px" height="500px" src="//www.youtube.com/embed/SxiW3lyGDW0" frameborder="0" allowfullscreen id="video_frame"></iframe>
			</div>
		</div>
	</div>
	<div id="content">
	<!-- 中間內容 -->