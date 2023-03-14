<?php
	include("../../model/db_coin.php");
	$action = $_POST["action"];
	switch($action){
		case 'login':		// --------------------------登入系統----------------------
			$identity = urlencode(trim($_POST["identity"]));
			$account = urlencode(trim($_POST["account"]));
			$password = urlencode(trim($_POST["password"]));
			$project = urlencode(trim($_POST["project"]));

			$sql = "SELECT * FROM `userinfo` WHERE `account` = '".mysql_real_escape_string($account)."'
					 AND  `password` = '".mysql_real_escape_string($password)."'
					 AND  `identity` = '".mysql_real_escape_string($identity)."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());

			// 資料庫確認是否有資料
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					$name = $row['name'];
					if($row['identity']  == 'T'){
						$arr = array( "check" => "legal", "announcement"=>"【登入】歡迎".$name."老師使用專題學習系統！", "where" => $project."/teacher/");
					}else if($row['identity']  == 'S'){
						$arr = array( "check" => "legal", "announcement"=>"【登入】歡迎".$name."同學使用專題學習系統！", "where" => $project."/student/entrance.php");
					}else if($row['identity']  == 'A'){
						$arr = array( "check" => "legal", "announcement"=>"【登入】歡迎".$name."管理員使用專題學習系統！", "where" => "administrator/");
					}
					$sql = "UPDATE `userinfo` SET `recent_login_time` = NOW() WHERE `account` = '".$account."'";
					mysql_query($sql, $link) or die(mysql_error());

					// 設定SESSION
					if(session_id() == ''){
						session_start();
					}
					$_SESSION['UID']        = $row["u_id"];		// UID
					$_SESSION['name'] 		= $row["name"];		// 姓名
					$_SESSION['nickname'] 	= $row["nickname"];	// 暱稱
					$_SESSION['birthday'] 	= $row["birthday"];	// 出生年月日(西元年)
					$_SESSION['gender'] 	= $row["gender"];	// 性別(男/女)
					$_SESSION['account'] 	= $row["account"];	// 使用者帳號
					$_SESSION['password'] 	= $row["password"];	// 使用者密碼
					$_SESSION['identity'] 	= $row["identity"];	// 使用者身分(S學生/T老師/A管理員)
					$_SESSION['county'] 	= $row["county"];	// 市(縣)
					$_SESSION['school'] 	= $row["school"];	// 校名
					$_SESSION['grade'] 		= $row["grade"];	// 年級
					$_SESSION['teacher'] 	= $row["teacher"];	// 指導老師
					$_SESSION['email'] 		= $row["email"];	// 使用者信箱
					$_SESSION['db_del'] 	= $row["db_del"];	// 刪除資料
				}
			}else{
				exit('{"check":"illegal", "announcement":"【警告】無此帳號，請確認輸入內容是否正確！"}');
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'register':	// --------------------------註冊系統----------------------
			$account 	 = $_POST['account'];		// 帳號
			$password1   = $_POST['password1'];		// 密碼
			$last_name   = $_POST['last_name'];		// 姓
			$first_name  = $_POST['first_name'];	// 名
			$nickname 	 = $_POST['nickname'];		// 暱稱
			$year 		 = $_POST['year'];			// 年
			$month 		 = $_POST['month'];			// 月
			$day 		 = $_POST['day'];			// 日
			$gender 	 = $_POST['gender'];		// 性別
			$identity 	 = $_POST['identity'];		// 身份
			$county 	 = $_POST['county'];		// 區域
			$school_name = $_POST['school_name'];	// 學校名稱
			$school_type = $_POST['school_type'];	// 學校類別
			$grade 		 = $_POST['grade'];			// 年級S
			$teacher 	 = $_POST['teacher'];		// 指導老師S
			$email 		 = $_POST['email'];			// 信箱

			// 姓名
			$name = $last_name.$first_name;
			// 生日
			$birthday = $year."-".$month."-".$day;
			// 學校
			$school = $school_name.$school_type;

			// 檢查帳號密碼長短是否正確
			if(strlen($account) < 4 || strlen($password1) < 6){
				exit("ERROR");
			}

			// 檢查帳號密碼是否重複
			$sql = "SELECT `account` FROM `userinfo` WHERE `account` = '".$account."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			if(mysql_num_rows($qry) != 0){
				exit("IR"); //已經有相同帳號
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
												'".$password1."',
												'".$identity."',
												'".$county."',
												'".$school."',
												'".$grade."',
												'".$teacher."',
												'".$email."',
												now())";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取註冊人資料----------------------------------------------------------
				$u_sql = "SELECT `u_id`, `register_time` FROM `userinfo` WHERE `name` = '".$name."' AND `birthday` = '".$birthday."' AND `account` = '".$account."' AND `password` = '".$password1."' ORDER BY `register_time` DESC LIMIT 0, 1";
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
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'forget':		// --------------------------忘記密碼----------------------
			// 接收E-mail地址
			$email = $_POST['email'];

			require("../../plugin/PHPMailer/class.phpmailer.php");	// 引入PHPMailer

			$mail = new PHPMailer();								// 建立新物件

			$mail->IsSMTP();										// 設定使用SMTP方式寄信
			$mail->SMTPAuth = true;									// 設定SMTP需要驗證

			$mail->SMTPSecure = "ssl";								// Gmail的SMTP主機需要使用SSL連線
			$mail->Host = "SMTP.gmail.com";							// Gmail的SMTP主機
			$mail->Port = 465;										// Gmail的SMTP主機的port為465
			$mail->CharSet = "utf-8";								// 設定郵件編碼
			$mail->Encoding = "base64";
			$mail->WordWrap = 50;									// 每50個字元自動斷行

			$mail->Username = "wuretworkshop@gmail.com";			// 設定驗證帳號
			$mail->Password = "Wulab35415";							// 設定驗證密碼

			$mail->From = "wuretworkshop@gmail.com";				// 設定寄件者信箱
			$mail->FromName = "WURET教學團隊";						// 設定寄件者姓名

			$mail->Subject = "【Let's Inquiry】忘記帳號/密碼！！";	// 設定郵件標題

			$mail->IsHTML(true);									// 設定郵件內容為HTML

			$mail->AddAddress($email);								// 收件者郵件及名稱

			$mail->Body = "親愛的使用者您好：".
						  "<br><br>本信件為系統通知信，請勿直接回覆。".
						  "<br>以下為您於【Let's Inquiry】專題學習系統的帳號資訊如下：";

			$sql = "SELECT * FROM `userinfo` WHERE `email` = '".$email."'";
			$qry = mysql_query($sql, $link) or die(mysql_error());

			// 找到此信箱
			if(mysql_num_rows($qry) > 0){
				while($row = mysql_fetch_array($qry)){
					$mail->Body .="<br><br>使用者名稱：".$row['name'].
							  		"<br>使用者帳號：".$row['account'].
							  		"<br>使用者密碼：".$row['password'];
				}
				$mail->Body .= "<br><br>【Let's Inquiry】管理員，祝您事事順心^^"; // 郵件內容

				// 是否寄出信件
				if(!$mail->Send()){									// 郵件寄出
					exit("【警告】網路發生異常！請確認「網路連線」是否正常！");
				}else{
					exit("【系統】已將密碼寄送至信箱，請檢查信箱內容！由於經系統發送，可能會落入垃圾郵件中，再麻煩您確認，謝謝。");
				}
			}else{
				exit("【警告】查無此信箱，請輸入正確「信箱位址」！");
			}
			break;
		default:
			exit ('error');
			break;
	}
?>
