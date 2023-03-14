<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>專題探究學習系統</title>
	<!-- Add icon、style CSS、jQuery library、coin js-->
	<link rel="icon" href="/co.in/model/images/coin.ico" type="image/x-icon">
	<link rel="stylesheet" href="/co.in/api/css/style.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="/co.in/api/js/coin.js"></script>
	<!-- Add loading AJAX setting -->
	<script type="text/javascript" src="/co.in/plugin/Loading/loading.js"></script>
</head>
<body class="loading">
<div id="wrapper">
	<div id="header">
		<h1>專題探究學習系統</h1>
		<h4>Project-Based Inquiry Learning System</h4>
	</div>
	<div id="leftcolumn">
		<img src="/co.in/model/images/science.png" width="60%">
	</div>
	<div id="rightcolumn">
		<fieldset id="login_field">
			<form method="post">
				<p><input type="radio" name="identity" value="T" checked/>老師
					<input type="radio" name="identity" value="S" style="margin-left: 30px;"/>學生</p>
				<p><label>帳號：</label><input type="text" name="account" size="20" maxlength="10"/></p>
				<p><label>密碼：</label><input type="password" name="password" size="20" maxlength="10"/></p>
				<p><label>專題類別：</label><select size="1" name="project">
							 					<option value="science">科學專題</option>
							 					<option value="society">社會專題</option>
												<option value="engineer">工程專題</option>
											</select></p>
				<p><span id="register_btn">[註冊]</span><span id="forget_btn">[忘記密碼]</span></p>
				<p>
				<?php
					// 計算註冊人數(COUNT)
					include("model/db_coin.php");
					$sql = "SELECT COUNT(*) AS num FROM `userinfo`";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					$row = mysql_fetch_array($qry);
						$num = $row['num'];
						echo "目前註冊人數：".$num." 人";
					mysql_query($sql, $link) or die(mysql_error());
				?>
				</p>
				<p><span style="color: red;">【此系統僅相容於Chrome瀏覽器】</span>
				<input type="button" id="login_btn" value="登入"/></p>
			</form>
		</fieldset>
	</div>
	<div id="footer">
		<p>copyright &copy; <img src="/co.in/model/images/wuret.png" width="4%"> 2015, All rights reserved.</p>
		<p>國立中央大學 網路學習與科技研究所 桃園縣中壢市中大路300號 研二館 03-4227151 #57874</p>
	</div>
</div>
</body>
</html>
