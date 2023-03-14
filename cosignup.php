<?php
	// 此為共同註冊區----------------------------------------------------------
	include("model/db_coin.php");

	$name  		 = $_POST['name'];			// 姓名V
	$nickname 	 = 'NULL';					// 暱稱
	$birthday 	 = '0000-00-00';			// 生日
	$gender 	 = '男';					// 性別
	$account 	 = $_POST['account'];		// 帳號V
	$password    = $_POST['password'];		// 密碼V
	$type        = $_POST['type']; 			// 身分(0->S / 1->T)V
	$county 	 = $_POST['location'];		// 區域V
	$school_name = $_POST['school'];		// 學校名稱V
	$school_type = $_POST['level'];			// 學校類別V
	$grade 		 = 'NULL';					// 年級S
	$teacher 	 = 'NULL';					// 指導老師S
	$email 		 = $_POST['email'];			// 信箱V

	// 身分轉換
	if($type == '0'){
		$identity 	 = 'S';					// 學生
	}else{
		$identity 	 = 'T';					// 老師
	}

	// 學校
	$school = $school_name.$school_type;

	// 檢查帳號密碼長短是否正確
	// if(strlen($account) < 4 || strlen($password) < 6){
	// 	exit("ERROR");
	// }

	// 檢查帳號密碼是否重複
	$sql = "SELECT `account` FROM `userinfo` WHERE `account` = '".$account."'";
	$qry = mysql_query($sql, $link) or die(mysql_error());
	if(mysql_num_rows($qry) != 0){
		exit("IR"); // 已經有相同帳號
	}else{
		$sql = "INSERT INTO `userinfo`( `name`,
										`nickname`,
										`birthday`,
										`gender`,
										`account`,
										`password`,
										`identity`,
										`county`,
										`school`,
										`grade`,
										`teacher`,
										`email`,
										`register_time`)
								VALUES ('".$name."',
										'".$nickname."',
										'".$birthday."',
										'".$gender."',
										'".$account."',
										'".$password."',
										'".$identity."',
										'".$county."',
										'".$school."',
										'".$grade."',
										'".$teacher."',
										'".$email."',
										now())";
			mysql_query($sql, $link) or die(mysql_error());
	// 讀取註冊人資料----------------------------------------------------------
	$u_sql = "SELECT `u_id`, `register_time` FROM `userinfo` WHERE `name` = '".$name."' AND `birthday` = '".$birthday."' AND `account` = '".$account."' AND `password` = '".$password."' ORDER BY `register_time` DESC LIMIT 0, 1";
	$u_qry = mysql_query($u_sql, $link) or die(mysql_error());
	$u_row = mysql_fetch_array($u_qry);
	$userID = $u_row["u_id"];
	// 新增最新消息------------------------------------------------------------
	if($identity == 'T'){
		$url = 'teacher/';
	}else if($identity == 'S'){
		$url = 'student/entrance.php';
	}
	$sql = "INSERT INTO `news`( `u_id`,
								`type`,
								`title`,
								`page_url`,
								`news_time`)
						VALUES ('".$userID."',
								'0',
								'".$name." 您好，歡迎您加入專題探究學習系統！！',
								'/co.in/science/".$url."',
								NOW())";
		mysql_query($sql, $link) or die(mysql_error());
		exit("SUCCESS");
	}
?>