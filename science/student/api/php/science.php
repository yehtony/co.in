<?php
	include("../../../../model/db_coin.php");
	if(session_id() == ''){
		session_start();
	}
	$action = $_POST["action"];
	switch($action){
		case 'logout':			// -------------------------登出系統----------------------
			$_session = array(); 	// 刪除所有的session變量..也可用unset($_session[xxx])逐個刪除。
			if(isset($_cookie[session_name()])){
				setcookie(session_name(), '', time()-42000, '/'); // 刪除sessin id.由於session默認是基於cookie的，所以使用setcookie刪除包含session id的cookie.
			}
			setcookie("PHPSESSID", '', time()-42000, '/'); // 清除session在cookie裡面的殘值
			session_destroy();		// 最後徹底銷毀session.
			break;
		case 'news_update':		// -------------------------最新消息----------------------
			$type = $_POST["type"];
			
			if($type == "read_news"){ 							// 已讀取消息
				// 更新最新消息-----------------------------------------------------------
				$sql = "UPDATE `news` SET `news_read` = '0' WHERE `u_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			break;
		case 'project_create': 	// -------------------------創立小組----------------------
			$project_pname = $_POST["project_pname"];
			$project_theme = $_POST["project_theme"];
			$project_teacher = $_POST["project_teacher"];
			$project_mascot = "/co.in/science/model/images/".$_POST["project_mascot"];
			$group_id = "";

			if(isset($_POST["group_id"])){			// 如無值則不傳遞！
				$group_id = $_POST["group_id"];
			}
			// 新增專題-----------------------------------------------------------
			$sql = "INSERT INTO `project`(  `t_m_id`,
											`pname`,
											`theme`,
											`mascot`,
											`starttime`)
									VALUES ('".$project_teacher."',
											'".$project_pname."',
											'".$project_theme."',
											'".$project_mascot."',
											NOW())";
				mysql_query($sql, $link) or die(mysql_error());
			// 抓取專題ID-----------------------------------------------------------
			$p_sql = "SELECT `p_id` FROM `project` WHERE `t_m_id` = '".$project_teacher."' AND `pname` = '".$project_pname."' AND `theme` = '".$project_theme."' AND `mascot` = '".$project_mascot."' ORDER BY `project_time` DESC LIMIT 0, 1";
			$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
			$p_row = mysql_fetch_array($p_qry);
				$projectID = $p_row["p_id"];
			// 新增專題小組組員--------------------------------------------------
				// 專題組長
				$c_sql = "INSERT INTO `project_group`(`p_id`,
													  `s_id`,
													  `chief`)
											  VALUES ('".$projectID."',
													  '".$_SESSION['UID']."',
													  '1')";
					mysql_query($c_sql, $link) or die(mysql_error());
				// 專題組員
				if($group_id != ""){
					foreach ($group_id as $value) {
						$m_sql = "INSERT INTO `project_group`(`p_id`,
															  `s_id`,
															  `chief`)
													  VALUES ('".$projectID."',
															  '".$value."',
															  '0')";
							mysql_query($m_sql, $link) or die(mysql_error());
					}
				}
			// 新增老師的最新消息----------------------------------------------
			$sql = "INSERT INTO `news`( `u_id`,
										`type`,
										`title`,
										`page_url`,
										`news_time`)
								VALUES ('".$project_teacher."',
										'0',
										'".$project_pname."：小組提出了申請，希望您可以擔任指導老師，趕快進入專題管理查看吧！',
										'/co.in/science/teacher/nav_project.php',
										NOW())";
				mysql_query($sql, $link) or die(mysql_error());
			// 新增專題時間表--------------------------------------------------
			$sql = "INSERT INTO `project_schedule`(	`p_id`)
										VALUES ('".$projectID."')";
				mysql_query($sql, $link) or die(mysql_error());
			// 新增專題表現----------------------------------------------------
			$sql = "INSERT INTO `project_perform`( `p_id`,
												   `times_login`,
												   `times_discuss`,
												   `times_examine`)
											VALUES( '".$projectID."',
													NOW(),
													NOW(),
													NOW())";
				mysql_query($sql, $link) or die(mysql_error());
			// 新增小組成績----------------------------------------------------
			$sql = "INSERT INTO `score_group`(`p_id`)
									VALUES ('".$projectID."')";
				mysql_query($sql, $link) or die(mysql_error());
			// 新增個人成績----------------------------------------------------
				// 專題組長
				$c_sql = "INSERT INTO `score_personal`(	`p_id`,
														`s_id`)
											  VALUES ('".$projectID."',
													  '".$_SESSION['UID']."')";
					mysql_query($c_sql, $link) or die(mysql_error());
				// 專題組員
				if($group_id != ""){
					foreach ($group_id as $value) {
						$m_sql = "INSERT INTO `score_personal`(	`p_id`,
																`s_id`)
													VALUES ('".$projectID."',
															  '".$value."')";
						mysql_query($m_sql, $link) or die(mysql_error());
					}
				}
			// 抓取小組成員------------------------------------------------------------------------------
			$student_name = "";

			$s_sql = "SELECT `s_id`, `chief` FROM `project_group` WHERE `p_id` = '".$projectID."'";
			$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
			while($s_row = mysql_fetch_array($s_qry)){
				$n_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$s_row['s_id']."'";
				$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
				$n_row = mysql_fetch_array($n_qry);
				if($s_row['chief'] == '1'){
					$student_name = $student_name."".$n_row['name']."(組長)，";
				}else{
					$student_name = $student_name."".$n_row['name']."，";
				}
			}
			// 抓取老師信箱--------------------------------------------------------------------------------
			$e_sql = "SELECT `name`, `email` FROM `userinfo` WHERE `u_id` = '".$project_teacher."'";
			$e_qry = mysql_query($e_sql, $link) or die(mysql_error());
			$e_row = mysql_fetch_array($e_qry);
				$Tmail = $e_row['email'];
				$Tname = $e_row['name'];
			// 送信通知老師--------------------------------------------------------------------------------
			include("../../../../plugin/PHPMailer/class.phpmailer.php"); 	// 引入PHPMailer

			$mail = new PHPMailer();										// 建立新物件

			$mail->IsSMTP();												// 設定使用SMTP方式寄信
			$mail->SMTPAuth = true;											// 設定SMTP需要驗證

			$mail->SMTPSecure = "ssl";										// Gmail的SMTP主機需要使用SSL連線
			$mail->Host = "SMTP.gmail.com";									// Gmail的SMTP主機
			$mail->Port = 465;												// Gmail的SMTP主機的port為465
			$mail->CharSet = "utf-8";										// 設定郵件編碼
			$mail->Encoding = "base64";
			$mail->WordWrap = 50;											// 每50個字元自動斷行

			$mail->Username = "wuretworkshop@gmail.com";					// 設定驗證帳號
			$mail->Password = "Wulab35415";									// 設定驗證密碼

			$mail->From = "wuretworkshop@gmail.com";						// 設定寄件者信箱
			$mail->FromName = "WURET教學團隊";								// 設定寄件者姓名

			$mail->Subject = "【Let's Inquiry】有學生向您申請為指導老師，請確認！！"; 	// 設定郵件標題

			$mail->IsHTML(true);											// 設定郵件內容為HTML

			$mail->AddAddress($Tmail, $Tname);								// 收件者郵件及名稱

			$mail->Body = "<u>".$Tname."</u>老師 您好：<br/><br/>
							[".$project_pname."]小組向您申請為指導老師，成員有".$student_name."<br/><br/>
							請確認是否接受該小組，並於<a href='http://140.115.126.189/co.in/'>專題探究學習系統<a>回覆，<br/><br/>
							本信為系統自動通知，有任何問題請到系統中詢問。<br/><br/>
							【Let's Inquiry】管理員，祝您事事順心^^";		// 郵件內容

				// 是否寄出信件
				if(!$mail->Send()){											// 郵件寄出
					echo $mail->ErrorInfo."<br/>";
				}
			if(mysql_error()){
				exit('{"Error":"Error"}');
			}else{
				exit('{"Success":"Success"}');
			}
			break;
		case 'project_re_teacher':	// -------------------重選選擇老師--------------------
			$p_id = $_POST['p_id'];					// 專題ID
			$re_teacher = $_POST['re_teacher'];		// 老師ID

			// 更新指導老師------------------------------------------------------------
			$sql = "UPDATE `project` SET `t_m_id` = '".$re_teacher."', `state` = '2' WHERE `p_id`= '".$p_id."'";
				mysql_query($sql, $link) or die(mysql_error());
			// 抓取小組名稱------------------------------------------------------------------------------
			$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
			$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
			$p_row = mysql_fetch_array($p_qry);
				$pname = $p_row['pname'];
			// 新增老師的最新消息----------------------------------------------
			$sql = "INSERT INTO `news`( `u_id`,
										`type`,
										`title`,
										`page_url`,
										`news_time`)
								VALUES ('".$re_teacher."',
										'0',
										'".$pname."：小組提出了申請，希望您可以擔任指導老師，趕快進入專題管理查看吧！',
										'/co.in/science/teacher/nav_project.php',
										NOW())";
				mysql_query($sql, $link) or die(mysql_error());
			// 抓取小組成員------------------------------------------------------------------------------
			$student_name = "";

			$s_sql = "SELECT `s_id`, `chief` FROM `project_group` WHERE `p_id` = '".$p_id."'";
			$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
			while($s_row = mysql_fetch_array($s_qry)){
				$n_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$s_row['s_id']."'";
				$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
				$n_row = mysql_fetch_array($n_qry);
				if($s_row['chief'] == '1'){
					$student_name = $student_name."".$n_row['name']."(組長)，";
				}else{
					$student_name = $student_name."".$n_row['name']."，";
				}
			}
			// 抓取老師信箱--------------------------------------------------------------------------------
			$e_sql = "SELECT `name`, `email` FROM `userinfo` WHERE `u_id` = '".$re_teacher."'";
			$e_qry = mysql_query($e_sql, $link) or die(mysql_error());
			$e_row = mysql_fetch_array($e_qry);
				$Tmail = $e_row['email'];
				$Tname = $e_row['name'];
			// 送信通知老師--------------------------------------------------------------------------------
			include("../../../../plugin/PHPMailer/class.phpmailer.php");	// 引入PHPMailer

			$mail = new PHPMailer();										// 建立新物件

			$mail->IsSMTP();												// 設定使用SMTP方式寄信
			$mail->SMTPAuth = true;											// 設定SMTP需要驗證

			$mail->SMTPSecure = "ssl";										// Gmail的SMTP主機需要使用SSL連線
			$mail->Host = "SMTP.gmail.com";									// Gmail的SMTP主機
			$mail->Port = 465;												// Gmail的SMTP主機的port為465
			$mail->CharSet = "utf-8";										// 設定郵件編碼
			$mail->Encoding = "base64";
			$mail->WordWrap = 50;											// 每50個字元自動斷行

			$mail->Username = "wuretworkshop@gmail.com";					// 設定驗證帳號
			$mail->Password = "Wulab35415";									// 設定驗證密碼

			$mail->From = "wuretworkshop@gmail.com";						// 設定寄件者信箱
			$mail->FromName = "WURET教學團隊";								// 設定寄件者姓名

			$mail->Subject = "【Let's Inquiry】有學生向您申請為指導老師，請確認！！"; 	// 設定郵件標題

			$mail->IsHTML(true);											// 設定郵件內容為HTML

			$mail->AddAddress($Tmail, $Tname);								// 收件者郵件及名稱

			$mail->Body = "<u>".$Tname."</u>老師 您好：<br/><br/>
							[".$pname."]小組向您申請為指導老師，成員有".$student_name."<br/><br/>
							請確認是否接受該小組，並於<a href='http://140.115.126.189/co.in/'>專題探究學習系統<a>回覆，<br/><br/>
							本信為系統自動通知，有任何問題請到系統中詢問。<br/><br/>
							【Let's Inquiry】管理員，祝您事事順心^^";		// 郵件內容

				// 是否寄出信件
				if(!$mail->Send()){											// 郵件寄出
					echo $mail->ErrorInfo."<br/>";
				}
			if(mysql_error()){
				exit('{"Error":"Error"}');
			}else{
				exit('{"Success":"Success"}');
			}
			break;
		case 'project_enter': 	// -------------------------進入專題----------------------
			$p_id = $_POST["p_id"];

			$sql = "SELECT * FROM  `project`
						LEFT OUTER JOIN `project_group`
						ON `project`.`p_id` = `project_group`.`p_id`
						WHERE `project_group`.`s_id` = '".$_SESSION['UID']."' AND `project`.`state`='0' AND `project`.`p_id` = '".$p_id."' LIMIT 0, 1";
			$qry = mysql_query($sql, $link) or die(mysql_error());
			$row = mysql_fetch_array($qry);	// 一筆這樣使用
			if(isset($row['stage'])){	
				$_SESSION['p_id'] 				= $p_id;					// 專題ID
				$_SESSION['t_m_id'] 			= $row['t_m_id'];			// 指導老師(主)
				$_SESSION['t_s_id'] 			= $row['t_s_id'];			// 指導老師(輔)
				$_SESSION['pname'] 				= $row['pname'];			// 專題名稱
				$_SESSION['theme'] 				= $row['theme'];			// 學科類別
				$_SESSION['mascot'] 			= $row['mascot'];			// 科學專題吉祥物
				$_SESSION['stage'] 				= $row['stage'];			// 任務階段
				$_SESSION['examine'] 			= $row['examine'];			// 專題審核
				$_SESSION['starttime'] 			= $row['starttime'];		// 專案起始時間
				$_SESSION['endtime'] 			= $row['endtime'];			// 專案結束時間
				$_SESSION['finish'] 			= $row['finish'];			// 完成專案
				$_SESSION['chief'] 				= $row['chief'];			// 職位(0:組員 1:組長)
				$_SESSION['hint_state'] 		= $row['hint_state'];		// 閱讀指引(0:已閱讀 1:未閱讀)
				$_SESSION['reflection_stage'] 	= $row['reflection_stage'];	// 反思日誌階段
				$_SESSION['reflection_state'] 	= $row['reflection_state'];	// 反思日誌(0:已填寫; 1:未填寫)
				// 更新小組登入頻率
				$p_sql = "UPDATE `project_perform` SET `times_login` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$p_id."'";
					mysql_query($p_sql, $link) or die(mysql_error());
				// 前往網頁
				if($row['reflection_state'] == '1'){	// 未填寫反思日誌，跳到反思日誌
					exit("/co.in/science/student/nav_diary.php");
				}else if($row['examine'] == '1'){		// 等待老師審核，跳到審核畫面
					exit("/co.in/science/student/project/work.php?stage=".$row['stage']);
				}else{									// 以上事項皆沒有，跳至專題任務首頁
					exit("index.php");
				}
			}
			break;
		case 'project_out':		// -------------------------更換小組----------------------
			unset($_SESSION['p_id']);
			break;
		case 'account_update':	// -------------------------帳號管理----------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/account/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "upload_photo"){						// 上傳照片
				$sql = "UPDATE `userinfo` SET `photo` = '/co.in/science/model/document/account/".$save_name."' WHERE `u_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "info_fixed"){					// 修改資料
				$info_name = $_POST["info_name"];
				$info_nickname = $_POST["info_nickname"];
				$info_password = $_POST["info_password"];
				$info_email = $_POST["info_email"];

				if($info_name != ''){
					$sql = "UPDATE `userinfo` SET `name` = '".$info_name."' WHERE `u_id` = '".$_SESSION['UID']."'";
						mysql_query($sql, $link) or die(mysql_error());
				}
				if($info_nickname != ''){
					$sql = "UPDATE `userinfo` SET `nickname` = '".$info_nickname."' WHERE `u_id` = '".$_SESSION['UID']."'";
						mysql_query($sql, $link) or die(mysql_error());
				}
				if($info_password != ''){
					$sql = "UPDATE `userinfo` SET `password` = '".$info_password."' WHERE `u_id` = '".$_SESSION['UID']."'";
						mysql_query($sql, $link) or die(mysql_error());
				}
				if($info_email != ''){
					$sql = "UPDATE `userinfo` SET `email` = '".$info_email."' WHERE `u_id` = '".$_SESSION['UID']."'";
						mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'guide_update':	// ---------------------任務地圖&活動指引-----------------
			$type = $_POST["type"];
			
			if($type == "read_guide"){
				$stage = $_POST['stage'];			// 任務階段
				// 讀取階段任務--------------------------------------------------------
				$sql = "SELECT `stage`, `name` FROM `stage` WHERE `stage` = '".$stage."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 任務審核
						$e_sql = "SELECT * FROM `project_examine` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '".$row["stage"]."'";
						$e_qry = mysql_query($e_sql, $link) or die(mysql_error());
						if(mysql_num_rows($e_qry) > 0){
							while($e_row = mysql_fetch_array($e_qry)){
								// 審核結果
								if($e_row["result"] == '0'){
									$guide_result = '過關';
								}else if($e_row["result"] == '1'){
									$guide_result = '審核中...';
								}else if($e_row["result"] == '2'){
									$guide_result = '未過關';
								}
								// 審核評語
								if($e_row["comment"] == NULL){
									$guide_comment = '(尚未評語)';
								}else{
									$guide_comment = $e_row["comment"];
								}
							}
							$arr[] = array( "guide_stage"			=> $row["stage"],
											"guide_name"			=> $row["name"],
											"guide_result"			=> $guide_result,
											"guide_comment"			=> $guide_comment);
						}else{
							$arr[] = array( "guide_stage"			=> $row["stage"],
											"guide_name"			=> $row["name"],
											"guide_result"			=> '任務進行中...',
											"guide_comment"			=> '(尚未評語)');
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_guide"){
				// 確認任務指引是否已閱讀--------------------------------------------------------
				$sql = "UPDATE `project_group` SET `hint_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'diary_update':	// -------------------------日誌專區----------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/diary/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); 	// 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_group"){ 							// 讀取小組日誌
				$diary_id = $_POST['diary_id'];						// 日誌ID
				// 讀取小組日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `d_id` = '".$diary_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];

						$arr[] = array( "diary_id"				=> $row["d_id"],
										"diary_pid"				=> $row["p_id"],
										"diary_date"			=> date("Y-m-d", strtotime($row["date"])),
										"diary_user"			=> $name,
										"diary_content1"		=> $row["content_1"],
										"diary_content2" 		=> $row["content_2"],
										"diary_content3"		=> $row["content_3"],
										"diary_filename"		=> $row["filename"],
										"diary_fileurl"			=> $row["fileurl"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_group"){						// 新增小組日誌
				$diary_group_date = $_POST["diary_group_date"];
				$diary_group_problem = $_POST["diary_group_problem"];
				$diary_group_conclusion = $_POST["diary_group_conclusion"];
				$diary_group_future = $_POST["diary_group_future"];

				if($check_file_exist == 0){					// 判斷是否有附加檔案
					// 新增小組日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`stage`,
												`content_1`,
												`content_2`,
												`content_3`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$diary_group_date."',
												'2',
												'".$_SESSION['stage']."',
												'".$diary_group_problem."',
												'".$diary_group_conclusion."',
												'".$diary_group_future."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else{
					// 新增小組日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`stage`,
												`content_1`,
												`content_2`,
												`content_3`,
												`filename`,
												`fileurl`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$diary_group_date."',
												'2',
												'".$_SESSION['stage']."',
												'".$diary_group_problem."',
												'".$diary_group_conclusion."',
												'".$diary_group_future."',
												'".$save_name."',
												'/co.in/science/model/document/diary/".$save_name."')";
						mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "view_personal"){ 				// 讀取個人日誌
				$diary_id = $_POST['diary_id'];						// 日誌ID
				// 讀取個人日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$_SESSION['p_id']."' AND `d_id` = '".$diary_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];

						$arr[] = array( "diary_id"				=> $row["d_id"],
										"diary_pid"				=> $row["p_id"],
										"diary_date"			=> date("Y-m-d", strtotime($row["date"])),
										"diary_user"			=> $name,
										"diary_content1"		=> $row["content_1"],
										"diary_content2" 		=> $row["content_2"],
										"diary_content3"		=> $row["content_3"],
										"diary_content4"		=> $row["content_4"],
										"diary_filename"		=> $row["filename"],
										"diary_fileurl"			=> $row["fileurl"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_personal"){					// 新增個人日誌
				$diary_personal_date = $_POST["diary_personal_date"];
				$diary_personal_progress = $_POST["diary_personal_progress"];
				$diary_personal_discuss = $_POST["diary_personal_discuss"];
				$diary_personal_learn = $_POST["diary_personal_learn"];
				$diary_personal_future = $_POST["diary_personal_future"];

				if($check_file_exist == 0){					// 判斷是否有附加檔案
					// 新增小組日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`stage`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$diary_personal_date."',
												'0',
												'".$_SESSION['stage']."',
												'".$diary_personal_progress."',
												'".$diary_personal_discuss."',
												'".$diary_personal_learn."',
												'".$diary_personal_future."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else{
					// 新增小組日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`stage`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`,
												`filename`,
												`fileurl`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$diary_personal_date."',
												'0',
												'".$_SESSION['stage']."',
												'".$diary_personal_progress."',
												'".$diary_personal_discuss."',
												'".$diary_personal_learn."',
												'".$diary_personal_future."',
												'".$save_name."',
												'/co.in/science/model/document/diary/".$save_name."')";
						mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "add_stageI"){					// 新增第一階段反思
				$stageI_num = $_POST["stageI_num"];

				for($i = 1; $i <= $stageI_num; $i++){
					$diary_stageI[$i] = $_POST["diary_stageI_".$i];
				}
				// 新增第一階段反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`,
											`content_5`,
											`content_6`,
											`content_7`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'1',
											'".$diary_stageI[1]."',
											'".$diary_stageI[2]."',
											'".$diary_stageI[3]."',
											'".$diary_stageI[4]."',
											'".$diary_stageI[5]."',
											'".$diary_stageI[6]."',
											'".$diary_stageI[7]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_stageII"){					// 新增第二階段反思
				$stageII_num = $_POST["stageII_num"];

				for($i = 1; $i <= $stageII_num; $i++){
					$diary_stageII[$i] = $_POST["diary_stageII_".$i];
				}
				// 新增第二階段反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'2',
											'".$diary_stageII[1]."',
											'".$diary_stageII[2]."',
											'".$diary_stageII[3]."',
											'".$diary_stageII[4]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_stageIII"){					// 新增第三階段反思
				$stageIII_num = $_POST["stageIII_num"];

				for($i = 1; $i <= $stageIII_num; $i++){
					$diary_stageIII[$i] = $_POST["diary_stageIII_".$i];
				}
				// 新增第三階段反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'3',
											'".$diary_stageIII[1]."',
											'".$diary_stageIII[2]."',
											'".$diary_stageIII[3]."',
											'".$diary_stageIII[4]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_stageIV"){					// 新增第四階段反思
				$stageIV_num = $_POST["stageIV_num"];

				for($i = 1; $i <= $stageIV_num; $i++){
					$diary_stageIV[$i] = $_POST["diary_stageIV_".$i];
				}
				// 新增第四階段反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`,
											`content_5`,
											`content_6`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'4',
											'".$diary_stageIV[1]."',
											'".$diary_stageIV[2]."',
											'".$diary_stageIV[3]."',
											'".$diary_stageIV[4]."',
											'".$diary_stageIV[5]."',
											'".$diary_stageIV[6]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_stageV"){					// 新增第五階段反思
				$stageV_num = $_POST["stageV_num"];

				for($i = 1; $i <= $stageV_num; $i++){
					$diary_stageV[$i] = $_POST["diary_stageV_".$i];
				}
				// 新增第五階段反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`,
											`content_5`,
											`content_6`,
											`content_7`,
											`content_8`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'5',
											'".$diary_stageV[1]."',
											'".$diary_stageV[2]."',
											'".$diary_stageV[3]."',
											'".$diary_stageV[4]."',
											'".$diary_stageV[5]."',
											'".$diary_stageV[6]."',
											'".$diary_stageV[7]."',
											'".$diary_stageV[8]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_stageVI"){					// 新增總回顧反思
				$stageVI_num = $_POST["stageVI_num"];

				for($i = 1; $i <= $stageVI_num; $i++){
					$diary_stageVI[$i] = $_POST["diary_stageVI_".$i];
				}
				// 新增總回顧反思------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`,
											`content_4`,
											`content_5`)
									VALUES( '".$_SESSION['p_id']."',
											'".$_SESSION['UID']."',
											'".date('Y-m-d', strtotime('NOW'))."',
											'1',
											'6',
											'".$diary_stageVI[1]."',
											'".$diary_stageVI[2]."',
											'".$diary_stageVI[3]."',
											'".$diary_stageVI[4]."',
											'".$diary_stageVI[5]."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 更變反思填寫狀況-----------------------------------------------------
				$sql = "UPDATE `project_group` SET `reflection_state` = '0' WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'help_update':		// -------------------------問題活動----------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/help/";
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "read_help"){ 							// 讀取問題
				$help_id = $_POST['help_id'];					// 求助ID
				// 讀取問題---------------------------------------------------------------
				$sql = "SELECT * FROM `help` WHERE `h_id`= '".$help_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取求助者
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 抓取求對象
						$t_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["t_u_id"]."' limit 0, 1";
						$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
						$t_row = mysql_fetch_array($t_qry);
							$tname = $t_row['name'];
						// 問題類型
						if($row['type'] == "0"){
							$type = "其他";
						}else if($row['type'] == "1"){
							$type = "器材問題";
						}else if($row['type'] == "2"){
							$type = "如何進行";
						}else if($row['type'] == "3"){
							$type = "小組溝通";
						}
						$arr[] = array( "help_id"			=> $row['h_id'],
										"help_uid"			=> $name,
										"help_to"			=> $tname,
										"help_objects"		=> $row["objects"],
										"help_type" 		=> $type,
										"help_title"		=> $row["title"],
										"help_description" 	=> $row["description"],
										"help_filename" 	=> $row["filename"],
										"help_fileurl" 		=> $row["fileurl"],
										"help_reply" 		=> $row["reply"],
										"help_time" 		=> date('Y-m-d', strtotime($row["help_time"])));
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取問題回覆-----------------------------------------------------------
				$sql = "SELECT * FROM `help_reply` WHERE `h_id` = '".$help_id."' ORDER BY `h_r_id` ASC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取回覆者
						$r_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
						$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
						$r_row = mysql_fetch_array($r_qry);
							$name = $r_row['name'];

						$arr[] = array( "help_id"			=> $row['h_id'],
										"help_r_id"			=> $row['h_r_id'],
										"help_r_uid"		=> $name,
										"help_r_content"	=> $row['content'],
										"help_r_filename"	=> $row['filename'],
										"help_r_fileurl"	=> $row['fileurl'],
										"help_r_time"		=> date('Y-m-d', strtotime($row['help_reply_time'])));
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_help"){ 						// 新增問題
				$suggest_type = $_POST["suggest_type"];
				$suggest_title = $_POST["suggest_title"];
				$suggest_description = $_POST["suggest_description"];
				// 新增求助問題---------------------------------------------------------------
				if($check_file_exist == 0){				//判斷是否有附加檔案
					$sql = "INSERT INTO `help`( `p_id`,
												`u_id`,
												`t_u_id`,
												`objects`,
												`type`,
												`title`,
												`description`,
												`reply`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$_SESSION['t_m_id']."',
												'1',
												'".$suggest_type."',
												'".$suggest_title."',
												'".$suggest_description."',
												'1')";
				}else{
					$sql = "INSERT INTO `help`( `p_id`,
												`u_id`,
												`t_u_id`,
												`objects`,
												`type`,
												`title`,
												`description`,
												`filename`,
												`fileurl`,
												`reply`)
										VALUES( '".$_SESSION['p_id']."',
												'".$_SESSION['UID']."',
												'".$_SESSION['t_m_id']."',
												'1',
												'".$suggest_type."',
												'".$suggest_title."',
												'".$suggest_description."',
												'".$save_name."',
												'/co.in/science/model/document/help/".$save_name."',
												'1')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "reply_help"){ 					// 回覆問題
				$help_id = $_POST["help_id"];					// 問題ID
				$suggest_reply = $_POST["suggest_reply"];
				
				if($check_file_exist == 0){						//判斷是否有附加檔案
					$sql = "INSERT INTO `help_reply`(`h_id`,
													 `u_id`,
													 `content`)
											 VALUES('".$help_id."',
													'".$_SESSION['UID']."',
													'".$suggest_reply."')";
				}else{
					$sql = "INSERT INTO `help_reply`(`h_id`,
													 `u_id`,
													 `content`,
													 `filename`,
													 `fileurl`)
											 VALUES('".$help_id."',
													'".$_SESSION['UID']."',
													'".$suggest_reply."',
													'".$save_name."',
													'/co.in/science/model/document/help/".$save_name."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更新已回覆狀態
				$sql = "UPDATE `help` SET `reply` = '1' WHERE `h_id`= '".$help_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'discuss_update':	// ------------------------討論區活動---------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/discussion/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "add_discuss"){ 						// 新增討論串
				$discuss_title = $_POST['discuss_title'];					// 討論主題
				$discuss_stage = $_POST['discuss_stage'];					// 討論階段
				$discuss_type = $_POST['discuss_type'];						// 討論類型
				$discuss_description = $_POST['discuss_description'];		// 討論說明
				// 檢查是否有相同的討論串
				$l_sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '".$discuss_stage."' AND `type` = '".$discuss_type."' AND `description` = '".$discuss_description."' ORDER BY `d_id` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的討論串
					exit('【系統】已有相同討論串！');
				}else{
					if($check_file_exist == 0){				//判斷是否有附加檔案
						$sql = "INSERT INTO `discussion`(`p_id`,
														 `u_id`,
														 `type`,
														 `stage`,
														 `title`,
														 `description`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$discuss_type."',
														'".$discuss_stage."',
														'".$discuss_title."',
														'".$discuss_description."')";
					}else{
						$sql = "INSERT INTO `discussion`(`p_id`,
														 `u_id`,
														 `type`,
														 `stage`,
														 `title`,
														 `description`,
														 `filename`,
														 `fileurl`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$discuss_type."',
														'".$discuss_stage."',
														'".$discuss_title."',
														'".$discuss_description."',
														'".$save_name."',
														'/co.in/science/model/document/discussion/".$save_name."')";
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取討論ID-----------------------------------------------------------
				$d_sql = "SELECT `d_id` FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '".$discuss_stage."' AND `title` = '".$discuss_title."' AND `description` = '".$discuss_description."'";
				$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
				$d_row = mysql_fetch_array($d_qry);
					$discussionID = $d_row["d_id"];
				// 抓取老師ID----------------------------------------------------------
				$t_sql = "SELECT `t_m_id` FROM `project` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				$t_row = mysql_fetch_array($t_qry);
					$t_m_id = $t_row['t_m_id'];
					// $t_s_id = $t_row['t_s_id']; `t_s_id`
				// 新增討論狀況(老師)-----------------------------------------------------------
				$sql = "INSERT INTO `discussion_active`(`d_id`,
														`u_id`)
												VALUES( '".$discussionID."',
														'".$t_m_id."')";
					mysql_query($sql, $link) or die(mysql_error());
				// $sql = "INSERT INTO `discussion_active`(`d_id`,
				// 										`u_id`)
				// 								VALUES( '".$discussionID."',
				// 										'".$t_s_id."')";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增討論狀況-----------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `discussion_active`(`d_id`,
															`u_id`)
													VALUES( '".$discussionID."',
															'".$value."')";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 更新小組討論頻率------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_discuss` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "reply_discuss"){ 				// 讀取討論區
				$discuss_id = $_POST['discuss_id'];							// 討論ID
				$discuss_read_type = $_POST['discuss_read_type'];			// 回覆類型
				$discuss_read_content = $_POST['discuss_read_content'];		// 回覆內容
				
				if($check_file_exist == 0){				//判斷是否有附加檔案
					$sql = "INSERT INTO `discussion_reply`( `d_id`,
															`r_u_id`,
															`category`,
															`content`)
													VALUES( '".$discuss_id."',
															'".$_SESSION['UID']."',
															'".$discuss_read_type."',
															'".$discuss_read_content."')";
				}else{
					$sql = "INSERT INTO `discussion_reply`( `d_id`,
															`r_u_id`,
															`category`,
															`content`,
															`filename`,
															`fileurl`)
													VALUES( '".$discuss_id."',
															'".$_SESSION['UID']."',
															'".$discuss_read_type."',
															'".$discuss_read_content."',
															'".$save_name."',
															'/co.in/science/model/document/discussion/".$save_name."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取回覆討論串--------------------------------------------------------
				$sql = "SELECT * FROM `discussion_reply` WHERE `d_id` = '".$discuss_id."' ORDER BY `d_r_id` DESC limit 0, 1";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["r_u_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 抓取回覆類型
						if($row['category'] == '1'){
							$category = '提出個人意見';
						}else if($row['category'] == '2'){
							$category = '提出不同的意見';
						}else if($row['category'] == '3'){
							$category = '提出理由';
						}else if($row['category'] == '4'){
							$category = '提供佐證資料';
						}else if($row['category'] == '5'){
							$category = '舉例';
						}else if($row['category'] == '6'){
							$category = '做總結';
						}
						$arr[] = array( "discuss_id"			=> $row["d_id"],
										"discuss_r_uid"			=> $name,
										"discuss_r_category"	=> $category,
										"discuss_r_content" 	=> $row["content"],
										"discuss_r_filename"	=> $row["filename"],
										"discuss_r_fileurl"	 	=> $row["fileurl"],
										"discuss_r_time"		=> date("Y-m-d",strtotime($row["discussion_reply_time"])));
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組討論頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_discuss` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "get_discuss"){ 					// 讀取討論區
				$discuss_id = $_POST["discuss_id"];
				// 讀取討論區--------------------------------------------------------
				$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `d_id` = '".$discuss_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取提議人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 主題來源
						if($row["type"] == '0'){
							$type = '一般討論';
						}else if($row["type"] == '1'){
							$type = '主題討論';
						}
						// 讀取討論區活動(GOOD)
						$f_sql = "SELECT * FROM `discussion_active` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$row['d_id']."'";
						$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
						$f_row = mysql_fetch_array($f_qry);
							$good = $f_row['good'];
							$star = $f_row['star'];
							$bookmark = $f_row['bookmark'];

						$arr[] = array( "discuss_id"			=> $row["d_id"],
										"discuss_pid"			=> $row["p_id"],
										"discuss_user"			=> $name,
										"discuss_title"			=> $row["title"],
										"discuss_description"	=> $row["description"],
										"discuss_good"			=> $good,
										"discuss_star"			=> $star,
										"discuss_bookmark"		=> $bookmark,
										"discuss_filename"		=> $row["filename"],
										"discuss_fileurl"		=> $row["fileurl"],
										"discuss_time"			=> date("Y-m-d",strtotime($row["discussion_time"])));
					}
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 讀取討論回覆-----------------------------------------------------------
				$sql = "SELECT * FROM `discussion_reply` WHERE `d_id` = '".$discuss_id."' ORDER BY `d_r_id` ASC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取回覆者
						$r_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["r_u_id"]."' limit 0, 1";
						$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
						$r_row = mysql_fetch_array($r_qry);
							$name = $r_row['name'];

						$arr[] = array( "discuss_id"			=> $row["d_id"],
										"discuss_r_uid"			=> $name,
										"discuss_r_category"	=> $row['category'],
										"discuss_r_content"		=> $row['content'],
										"discuss_r_filename"	=> $row['filename'],
										"discuss_r_fileurl"		=> $row['fileurl'],
										"discuss_r_time"		=> date('Y-m-d', strtotime($row['discussion_reply_time'])));
					}
					mysql_query($sql, $link) or die(mysql_error());
				}	
			}else if($type == "good_discuss"){ 					// 按讚討論
				$discuss_id = $_POST["discuss_id"];
				// 對討論按讚------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `good` = '1' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動(COUNT)-----------------------------------------------------------
				$sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$discuss_id."' AND `good` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "discuss_id"			=> $discuss_id,
										"discuss_number"		=> $row["num"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "ungood_discuss"){ 				// 取消讚討論
				$discuss_id = $_POST["discuss_id"];
				// 取消對討論按讚------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `good` = '0' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動(COUNT)-----------------------------------------------------------
				$sql = "SELECT COUNT(good) AS num FROM `discussion_active` WHERE `d_id` = '".$discuss_id."' AND `good` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "discuss_id"			=> $discuss_id,
										"discuss_number"		=> $row["num"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "star_discuss"){ 					// 星號註記討論
				$discuss_id = $_POST["discuss_id"];
				// 對討論星號註記------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `star` = '1' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
				$arr[] = array( "discuss_id"		=> $discuss_id);
			}else if($type == "unstar_discuss"){ 				// 取消星號註記討論
				$discuss_id = $_POST["discuss_id"];
				// 取消對討論星號註記----------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `star` = '0' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
				$arr[] = array( "discuss_id"		=> $discuss_id);
			}else if($type == "bookmark_discuss"){ 				// 做書籤討論
				$discuss_id = $_POST["discuss_id"];
				// 對討論星號註記------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `bookmark` = '1' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
				$arr[] = array( "discuss_id"		=> $discuss_id);
			}else if($type == "unbookmark_discuss"){ 			// 取消書籤討論
				$discuss_id = $_POST["discuss_id"];
				// 取消對討論星號註記----------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `bookmark` = '0' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
				$arr[] = array( "discuss_id"		=> $discuss_id);
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage1-1_update':	// -----------------------階段1-1活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage1-1/";
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "get_theme"){ 							// 獲得主題
				$theme_id = $_POST["theme_id"];
				// 讀取研究主題--------------------------------------------------------
				$sql = "SELECT * FROM `research_theme` WHERE `p_id` = '".$_SESSION['p_id']."' AND `t_id` = '".$theme_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取提議人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 主題來源
						if($row["info_src"] == '0'){
							$info_src = '其他';
						}else if($row["info_src"] == '1'){
							$info_src = '生活中';
						}else if($row["info_src"] == '2'){
							$info_src = '課本中';
						}else if($row["info_src"] == '3'){
							$info_src = '參考別人的題目';
						}
						$arr[] = array( "theme_id"			=> $row["t_id"],
										"theme_pid"			=> $row["p_id"],
										"theme_user"		=> $name,
										"theme_name"		=> $row["theme"],
										"theme_src" 		=> $info_src,
										"theme_description"	=> $row["description"],
										"theme_filename"	=> $row["filename"],
										"theme_fileurl"		=> $row["fileurl"],
										"theme_research"	=> $row["research"],
										"theme_time"		=> date("Y-m-d",strtotime($row["research_time"])));
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_theme"){ 					// 新增主題
				$theme_name = $_POST["theme_name"];
				$theme_src = $_POST["theme_src"];
				$theme_reason = $_POST["theme_reason"];
				// 新增主題------------------------------------------------------------
				if($check_file_exist == 0){						// 判斷是否有附加檔案
					$sql = "INSERT INTO `research_theme`(`p_id`,
														`s_id`,
														`theme`,
														`info_src`,
														`description`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$theme_name."',
														'".$theme_src."',
														'".$theme_reason."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else{
					$sql = "INSERT INTO `research_theme`(`p_id`,
														`s_id`,
														`theme`,
														`info_src`,
														`description`,
														`filename`,
														`fileurl`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$theme_name."',
														'".$theme_src."',
														'".$theme_reason."',
														'".$save_name."',
														'/co.in/science/model/document/uploads_stage1-1/".$save_name."')";
						mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "check_theme"){ 					// 決定主題
				$theme_id = $_POST["theme_id"];
				// 先取消原先決定theme------------------------------------------------------------
				$u_sql = "UPDATE `research_theme` SET `research` = '0' WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($u_sql, $link) or die(mysql_error());
				// 決定theme------------------------------------------------------------
				$n_sql = "UPDATE `research_theme` SET `research` = '1' WHERE `t_id`= '".$theme_id."'";
					mysql_query($n_sql, $link) or die(mysql_error());
			}else if($type == "uncheck_theme"){ 				// 取消主題
				$theme_id = $_POST["theme_id"];
				// 取消原先決定theme------------------------------------------------------------
				$u_sql = "UPDATE `research_theme` SET `research` = '0' WHERE `t_id`= '".$theme_id."'";
					mysql_query($u_sql, $link) or die(mysql_error());
			}else if($type == "add_check_theme"){ 				// 送出主題審核表
				$check_num = $_POST["check_num"];

				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '1-1' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['UID']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '1-1'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`,
													`no_08`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'1-1',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."',
													'".$check_no[8]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real1-1` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage1-2_update':	// -----------------------階段1-2活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage1-2/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "add_topic"){ 							// 新增題目
				$topic_name = $_POST["topic_name"];
				$topic_reason = $_POST["topic_reason"];
				$data_id = $_POST["data_id"];
				// 新增題目------------------------------------------------------------
				if($check_file_exist == 0){						// 判斷是否有附加檔案
					$sql = "INSERT INTO `research_topic`(`p_id`,
														`s_id`,
														`topic`,
														`description`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$topic_name."',
														'".$topic_reason."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else{
					$sql = "INSERT INTO `research_topic`(`p_id`,
														`s_id`,
														`topic`,
														`description`,
														`filename`,
														`fileurl`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$topic_name."',
														'".$topic_reason."',
														'".$save_name."',
														'/co.in/science/model/document/uploads_stage1-2/".$save_name."')";
						mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取主題ID-----------------------------------------------------------
				$t_sql = "SELECT `t_id` FROM `research_topic` WHERE `topic` = '".$topic_name."' AND `description` = '".$topic_reason."' AND `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."'
							ORDER BY `research_time` DESC LIMIT 0, 1";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				$t_row = mysql_fetch_array($t_qry);
				$topicID = $t_row["t_id"];
				// 新增主題相關資源------------------------------------------------------
				foreach ($data_id as $value) {
					$d_sql = "INSERT INTO `research_topic_data`(`t_id`,
																`d_id`)
														VALUES ('".$topicID."',
																'".$value."')";
					mysql_query($d_sql, $link) or die(mysql_error());
				}
			}else if($type == "check_topic"){ 					// 決定題目
				$topic_id = $_POST["topic_id"];
				// 先取消原先決定題目--------------------------------------------------
				$u_sql = "UPDATE `research_topic` SET `research` = '0' WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($u_sql, $link) or die(mysql_error());
				// 決定題目------------------------------------------------------------
				$n_sql = "UPDATE `research_topic` SET `research` = '1' WHERE `t_id`= '".$topic_id."'";
					mysql_query($n_sql, $link) or die(mysql_error());
			}else if($type == "uncheck_topic"){ 				// 取消題目
				$topic_id = $_POST["topic_id"];
				// 取消原先決定題目----------------------------------------------------
				$u_sql = "UPDATE `research_topic` SET `research` = '0' WHERE `t_id`= '".$topic_id."'";
					mysql_query($u_sql, $link) or die(mysql_error());
			}else if($type == "add_check_topic"){ 				// 送出題目審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '1-2' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '1-2'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'1-2',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新個人反思階段----------------------------------------------------------
				// $sql = "UPDATE `project_group` SET `reflection_stage` = '1', `reflection_state` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real1-2` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'1-1',
														'1',
														'1-1',
														'2-1')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了1-1階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了1-1階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "get_topic"){ 					// 獲得題目
				$topic_id = $_POST["topic_id"];
				// 讀取研究題目--------------------------------------------------------
				$sql = "SELECT * FROM `research_topic` WHERE `p_id` = '".$_SESSION['p_id']."' AND `t_id` = '".$topic_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取提議人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 相關資源
						$related_data = "";
						$d_sql = "SELECT `d_id` FROM `research_topic_data` WHERE `t_id` = '".$topic_id."'";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							// 抓取資料庫
							$s_sql = "SELECT `title` FROM `database` WHERE `d_id` = '".$d_row["d_id"]."' limit 0, 1";
							$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
							$s_row = mysql_fetch_array($s_qry);

							$related_data .= "<div style='margin-left: 40px;'>".$s_row['title']."</div>";
						}
						
						$arr[] = array( "topic_id"			=> $row["t_id"],
										"topic_pid"			=> $row["p_id"],
										"topic_user"		=> $name,
										"topic_name"		=> $row["topic"],
										"topic_data" 		=> $related_data,
										"topic_description"	=> $row["description"],
										"topic_filename"	=> $row["filename"],
										"topic_fileurl"		=> $row["fileurl"],
										"topic_research"	=> $row["research"],
										"topic_time"		=> date("Y-m-d",strtotime($row["research_time"])));
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage2-1_update':	// -----------------------階段2-1活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage2-1/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "new_think"){ 							// 新增發散性思考
				$think_type = $_POST["think_type"];
				$think_idea = $_POST["think_idea"];
				$order = "1";
				// 是否已有發散性思考(增加order)
				$q_sql = "SELECT `order` FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' ORDER BY `q_id` DESC limit 0, 1";
				$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
				if(mysql_num_rows($q_qry) > 0){
					while($q_row = mysql_fetch_array($q_qry)){
						$order = $q_row["order"]+1;
					}
				}
				// 新增發散性思考
				$sql = "INSERT INTO `research_question`(`p_id`,
														`s_id`,
														`order`,
														`5W1H`,
														`question`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$order."',
														'".$think_type."',
														'".$think_idea."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取研究問題
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `s_id` = '".$_SESSION['UID']."' AND `5W1H` = '".$think_type."' AND `question` = '".$think_idea."' ORDER BY `q_id` DESC limit 0, 1";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "think_id"			=> $row['q_id'],
										"think_pid"			=> $row["p_id"],
										"think_type"		=> $row["5W1H"],
										"think_name"		=> $row["question"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "delete_think"){					// 刪除發散性思考
				$think_id = $_POST["think_id"];
				// 刪除發散性思考
				$sql = "DELETE FROM `research_question` WHERE `q_id` = '".$think_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_var"){ 						// 加入變因
				$add_name = $_POST["add_name"];
				// 新增變數------------------------------------------------------------
				$sql = "INSERT INTO `research_question_var`(`p_id`,
															`name`)
													VALUES( '".$_SESSION['p_id']."',
															'".$add_name."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取變數-----------------------------------------------------------
				$sql = "SELECT * FROM `research_question_var` WHERE `p_id` = '".$_SESSION['p_id']."' AND `name` = '".$add_name."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				while($row = mysql_fetch_array($qry)){
					$arr[] = array( "var_id"			=> $row["q_v_id"],
									"var_name"			=> $row["name"]);
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "delete_var"){ 					// 刪除變因
				$delete_name = $_POST["delete_name"];
				// 讀取變數-----------------------------------------------------------
				$sql = "SELECT * FROM `research_question_var` WHERE `p_id` = '".$_SESSION['p_id']."' AND `name` = '".$delete_name."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				while($row = mysql_fetch_array($qry)){
					$arr[] = array( "var_id"			=> $row["q_v_id"],
									"var_name"			=> $row["name"]);
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 刪除變數------------------------------------------------------------
				$sql = "DELETE FROM `research_question_var` WHERE `p_id` = '".$_SESSION['p_id']."' AND `name` = '".$delete_name."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "save_name"){ 					// 儲存研究題目
				$question_id = $_POST["question_id"];
				$question_name = $_POST["question_name"];

				$sql = "UPDATE `research_question` SET `question` = '".$question_name."' WHERE `q_id`= '".$question_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "save_question"){ 				// 儲存研究問題
				$question_id = $_POST["question_id"];
				$question_assume = $_POST["question_assume".$question_id];
				$question_independent = $_POST["question_independent".$question_id];
				$question_dependent = $_POST["question_dependent".$question_id];

				$sql = "UPDATE `research_question` SET `assume` = '".$question_assume."', `independent_var` = '".$question_independent."', `dependent_var` = '".$question_dependent."' WHERE `q_id`= '".$question_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_question"){ 				// 送出研究問題審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-1' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-1'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`,
													`no_08`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'2-1',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."',
													'".$check_no[8]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real2-1` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'2-1',
														'1',
														'1-1',
														'2-2')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了2-1階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了2-1階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage2-2_update':	// -----------------------階段2-2活動---------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage2-2/";
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "get_question"){ 						// 抓取研究問題
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "save_material"){ 				// 儲存研究工具與材料
				$question_id = $_POST['question_id'];			// 問題ID
				$material_type = $_POST['material_type'];
				$material_name = $_POST['material_name'];
				$material_description = $_POST['material_description'];
				$material_number = $_POST['material_number'];

				if($check_file_exist == 0){					// 判斷是否有附加檔案
					// 新增工具與材料------------------------------------------------------
					$sql = "INSERT INTO `research_idea`(`q_id`,
														`p_id`,
														`s_id`,
														`type`,
														`name`,
														`number`,
														`description`)
												VALUES( '".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$material_type."',
														'".$material_name."',
														'".$material_number."',
														'".$material_description."')";
						mysql_query($sql, $link) or die(mysql_error());
					// 抓取構想表ID-----------------------------------------------------------
					$i_sql = "SELECT `i_id` FROM `research_idea` WHERE `q_id` = '".$question_id."' AND `type` = '".$material_type."' AND `name` = '".$material_name."' AND `description` = '".$material_description."' AND `number` = '".$material_number."'";
					$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
					$i_row = mysql_fetch_array($i_qry);
						$ideaID = $i_row["i_id"];
					// 新增工具與材料(預設圖片)-----------------------------------------------
					$sql = "INSERT INTO `research_idea_pic`(`i_id`,
															`pic_url`)
													VALUES( '".$ideaID."',
															'/co.in/science/model/images/project_null.jpg')";
						mysql_query($sql, $link) or die(mysql_error());
				}else{
					// 新增工具與材料------------------------------------------------------
					$sql = "INSERT INTO `research_idea`(`q_id`,
														`p_id`,
														`s_id`,
														`type`,
														`name`,
														`number`,
														`description`)
												VALUES( '".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$material_type."',
														'".$material_name."',
														'".$material_number."',
														'".$material_description."')";
						mysql_query($sql, $link) or die(mysql_error());
					// 抓取構想表ID-----------------------------------------------------------
					$i_sql = "SELECT `i_id` FROM `research_idea` WHERE `q_id` = '".$question_id."' AND `type` = '".$material_type."' AND `name` = '".$material_name."' AND `description` = '".$material_description."' AND `number` = '".$material_number."'";
					$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
					$i_row = mysql_fetch_array($i_qry);
						$ideaID = $i_row["i_id"];
					// 新增工具與材料(圖片)------------------------------------------------------
					$sql = "INSERT INTO `research_idea_pic`(`i_id`,
															`pic_url`)
													VALUES( '".$ideaID."',
															'/co.in/science/model/document/uploads_stage2-2/".$save_name."')";
						mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "delete_material"){ 				// 刪除研究工具與材料
				$material_id = $_POST['material_id'];			// 材料ID
				// 刪除材料與工具---------------------------------------------------------
				$sql = "DELETE FROM `research_idea` WHERE `i_id` = '".$material_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "save_steps"){ 					// 儲存步驟
				$question_id = $_POST['question_id'];			// 問題ID
				$steps_content = $_POST['steps_content'];
				$steps_order = 1;
				// 刪除步驟-------------------------------------------------
				$d_sql = "DELETE FROM `research_idea_steps` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($d_sql, $link) or die(mysql_error());
				
				// 新增步驟-------------------------------------------------
				for($i = 0; $i < count($steps_content); $i++){
					$sql = "INSERT INTO `research_idea_steps`(  `q_id`,
																`p_id`,
																`s_id`,
																`steps_order`,
																`steps_name`)
														VALUES ( '".$question_id."',
																 '".$_SESSION['p_id']."',
																 '".$_SESSION['UID']."',
																 '".$steps_order."',
																 '".$steps_content[$i]."')";
					mysql_query($sql, $link) or die(mysql_error());
					
					$steps_order++;
				}
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
			}else if($type == "save_record"){ 					// 儲存記錄方式
				$question_id = $_POST['question_id'];			// 問題ID
				$record_way = $_POST['record_way'];				// 記錄方式

				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `type` = '0'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					// 更新記錄方式------------------------------------------------------
					$r_sql = "UPDATE `research_idea` SET `name` = '".$record_way."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id`= '".$question_id."' AND `type` = '0'";
						mysql_query($r_sql, $link) or die(mysql_error());
				}else{
					// 新增記錄方式------------------------------------------------------
					$r_sql = "INSERT INTO `research_idea`(`q_id`,
														`p_id`,
														`s_id`,
														`type`,
														`name`,
														`number`)
												VALUES( '".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'0',
														'".$record_way."',
														'1')";
						mysql_query($r_sql, $link) or die(mysql_error());
				}
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-2' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-2'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`,
													`no_08`,
													`no_09`,
													`no_10`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'2-2',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."',
													'".$check_no[8]."',
													'".$check_no[9]."',
													'".$check_no[10]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real2-2` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'2-2',
														'1',
														'2-1',
														'2-3')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了2-2階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了2-2階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage2-3_update':	// -----------------------階段2-3活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage2-3/";
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_idea"){ 							// 觀看研究構想
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "upload_record"){ 				// 上傳表格
				$question_id = $_POST["question_id"];

				$sql = "SELECT * FROM `research_form` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					$sql = "UPDATE `research_form` SET `fileurl` = '/co.in/science/model/document/uploads_stage2-3/".$save_name."' WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-3'";
				}else{
					$sql = "INSERT INTO `research_form`( `q_id`,
														 `p_id`,
														 `s_id`,
														 `stage`,
														 `fileurl`)
												VALUES ('".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'2-3',
														'/co.in/science/model/document/uploads_stage2-3/".$save_name."')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-3' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-3'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'2-3',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real2-3` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'2-3',
														'1',
														'2-2',
														'2-4')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了2-3階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了2-3階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage2-4_update':	// -----------------------階段2-4活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage2-4/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_idea"){ 							// 觀看研究構想
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_result"){ 					// 觀看實驗結果
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_pilot` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "result_id"				=> $row['q_id'],
										"result_pid"			=> $row["p_id"],
										"result_result"			=> $row["result"],
										"result_description"	=> $row["description"],
										"result_attention" 		=> $row["attention"],
										"result_fileurl"		=> $row["fileurl"],
										"result_fixed" 			=> $row["fixed"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "delete_result"){ 				// 刪除實驗結果
				$question_id = $_POST['question_id'];			// 問題ID
				// 刪除嘗試性實驗結果------------------------------------------------
				$sql = "DELETE FROM `research_pilot` WHERE `q_id` = '".$question_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "record_result"){ 				// 紀錄實驗結果
				$question_id = $_POST["question_id"];
				$research_result = $_POST["research_result"];
				$research_description = $_POST["research_description"];
				$research_attention = $_POST["research_attention"];
				$research_fixed = $_POST["research_fixed"];

				$sql = "SELECT * FROM `research_pilot` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					if($check_file_exist == 0){					// 判斷是否有附加檔案
						$sql = "UPDATE `research_pilot` SET `s_id` = '".$_SESSION['UID']."', `result` = '".$research_result."', `description` = '".$research_description."', `attention` = '".$research_attention."', `fixed` = '".$research_fixed."', `fileurl` = NULL WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					}else{
						$sql = "UPDATE `research_pilot` SET `s_id` = '".$_SESSION['UID']."', `result` = '".$research_result."', `description` = '".$research_description."', `attention` = '".$research_attention."', `fixed` = '".$research_fixed."', `fileurl` = '/co.in/science/model/document/uploads_stage2-4/".$save_name."' WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					}
				}else{
					if($check_file_exist == 0){					// 判斷是否有附加檔案
						$sql = "INSERT INTO `research_pilot`(`q_id`,
														 	`p_id`,
														 	`s_id`,
														 	`result`,
														 	`description`,
														 	`attention`,
														 	`fixed`)
													VALUES ('".$question_id."',
															'".$_SESSION['p_id']."',
															'".$_SESSION['UID']."',
															'".$research_result."',
															'".$research_description."',
															'".$research_attention."',
															'".$research_fixed."')";
					}else{
						$sql = "INSERT INTO `research_pilot`(`q_id`,
														 	`p_id`,
														 	`s_id`,
														 	`result`,
														 	`description`,
														 	`attention`,
														 	`fileurl`,
														 	`fixed`)
													VALUES ('".$question_id."',
															'".$_SESSION['p_id']."',
															'".$_SESSION['UID']."',
															'".$research_result."',
															'".$research_description."',
															'".$research_attention."',
															'/co.in/science/model/document/uploads_stage2-4/".$save_name."',
															'".$research_fixed."')";
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題順序
				$o_sql = "SELECT * FROM `research_question` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
				$o_qry = mysql_query($o_sql, $link) or die(mysql_error());
					while($o_row = mysql_fetch_array($o_qry)){
						$order = $o_row['order'];
					}
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了2-4(".$order.")的嘗試性實驗，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/nav_project.php',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了2-4(".$order.")的嘗試性實驗，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/nav_project.php',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'2-4(".$order.")',
														'1',
														'2-3',
														'2-4')";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-4' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '2-4'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'2-4',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新個人反思階段----------------------------------------------------------
				// $sql = "UPDATE `project_group` SET `reflection_stage` = '2', `reflection_state` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real2-4` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'2-4',
														'1',
														'2-3',
														'3-1')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了2-4階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了2-4階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage3-1_update':	// -----------------------階段3-1活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage3-1/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_idea"){ 							// 觀看研究構想
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_experiment"){ 				// 觀看實驗日誌
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "experiment_id"				=> $row['q_id'],
										"experiment_pid"			=> $row["p_id"],
										"experiment_date"			=> $row["date"],
										"experiment_result"			=> $row["result"],
										"experiment_fileurl"		=> $row["fileurl"],
										"experiment_description" 	=> $row["description"],
										"experiment_time" 			=> $row["research_time"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_experiment"){ 				// 新增實驗日誌
				$question_id = $_POST["question_id"];
				$research_date = $_POST["research_date"];
				$research_result = $_POST["research_result"];
				$research_description = $_POST["research_description"];

				$sql = "SELECT * FROM `research_experiment` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					if($check_file_exist == 0){					// 判斷是否有附加檔案
						$sql = "UPDATE `research_experiment` SET `s_id` = '".$_SESSION['UID']."', `date` = '".$research_date."', `result` = '".$research_result."', `description` = '".$research_description."', `fileurl` = NULL WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					}else{
						$sql = "UPDATE `research_experiment` SET `s_id` = '".$_SESSION['UID']."', `date` = '".$research_date."', `result` = '".$research_result."', `description` = '".$research_description."', `fileurl` = '/co.in/science/model/document/uploads_stage3-1/".$save_name."' WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
					}
				}else{
					if($check_file_exist == 0){					// 判斷是否有附加檔案
						$sql = "INSERT INTO `research_experiment`(`q_id`,
																  `p_id`,
																  `s_id`,
																  `date`,
																  `result`,
																  `description`)
														  VALUES ('".$question_id."',
																  '".$_SESSION['p_id']."',
																  '".$_SESSION['UID']."',
																  '".$research_date."',
																  '".$research_result."',
																  '".$research_description."')";
					}else{
						$sql = "INSERT INTO `research_experiment`(`q_id`,
																  `p_id`,
																  `s_id`,
																  `date`,
																  `result`,
																  `description`,
																  `fileurl`)
														  VALUES ('".$question_id."',
																  '".$_SESSION['p_id']."',
																  '".$_SESSION['UID']."',
																  '".$research_date."',
																  '".$research_result."',
																  '".$research_description."',
																  '/co.in/science/model/document/uploads_stage3-1/".$save_name."')";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-1' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-1'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'3-1',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real3-1` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'3-1',
														'1',
														'2-4',
														'3-2')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了3-1階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了3-1階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage3-2_update':	// -----------------------階段3-2活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage3-2/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_experiment"){ 					// 觀看實驗日誌
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取實驗日誌-----------------------------------------------------------
				$sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 實驗結果
						if($row['result'] == '1'){
							$result = '成功';
						}else{
							$result = '失敗';
						}
						$arr[] = array( "question_id"				=> $question_id,
										"experiment_date"			=> $row['date'],
										"experiment_result"			=> $result,
										"experiment_record"			=> $row['fileurl'],
										"experiment_description"	=> $row['description']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "upload_analysis"){ 				// 上傳實驗分析
				$question_id = $_POST["question_id"];

				$sql = "SELECT * FROM `research_form` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-2'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					$sql = "UPDATE `research_form` SET `fileurl` = '/co.in/science/model/document/uploads_stage3-2/".$save_name."' WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-2'";
				}else{
					$sql = "INSERT INTO `research_form`( `q_id`,
														 `p_id`,
														 `s_id`,
														 `stage`,
														 `fileurl`)
												VALUES ('".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'3-2',
														'/co.in/science/model/document/uploads_stage3-2/".$save_name."')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-2' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-2'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`,
													`no_08`,
													`no_09`,
													`no_10`,
													`no_11`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'3-2',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."',
													'".$check_no[8]."',
													'".$check_no[9]."',
													'".$check_no[10]."',
													'".$check_no[11]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real3-2` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'3-2',
														'1',
														'3-1',
														'3-3')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了3-2階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了3-2階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage3-3_update':	// -----------------------階段3-3活動---------------------
			$type = $_POST["type"];
			
			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage3-3/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "view_idea"){ 							// 觀看研究構想
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題步驟-----------------------------------------------------------
				$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "question_id"			=> $question_id,
										"steps_order"			=> $row['steps_order'],
										"steps_name"			=> $row['steps_name']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取問題工具&紀錄表---------------------------------------------------
				$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."' AND `kind` = '1'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						if($row['type'] == '0'){			// 記錄方式
							$arr[] = array( "question_id"			=> $question_id,
											"record_id"				=> $row['i_id'],
											"record_name"			=> $row['name']);
						}else{								// 工具&材料
							// 抓取材料&工具(圖片)
							$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							while($c_row = mysql_fetch_array($c_qry)){
								$material_pic = $c_row['pic_url'];
							}

							$arr[] = array( "question_id"			=> $question_id,
											"material_id"			=> $row['i_id'],
											"material_name"			=> $row['name'],
											"material_number"		=> $row['number'],
											"material_description"	=> $row['description'],
											"material_pic"			=> $material_pic);
						}
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "view_experiment"){ 				// 觀看實驗日誌
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取研究問題--------------------------------------------------------
				$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取發言人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 操縱變因
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						// 應變變因
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."' limit 0, 1";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}

						$arr[] = array( "question_id"			=> $row['q_id'],
										"question_pid"			=> $row["p_id"],
										"question_user"			=> $name,
										"question_name"			=> $row["question"],
										"question_assume" 		=> $row["assume"],
										"question_independent"	=> $independent_name,
										"question_dependent" 	=> $dependent_name);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取實驗日誌-----------------------------------------------------------
				$sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$_SESSION['p_id']."' AND `q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 實驗結果
						if($row['result'] == '1'){
							$result = '成功';
						}else{
							$result = '失敗';
						}
						$arr[] = array( "question_id"				=> $question_id,
										"experiment_date"			=> $row['date'],
										"experiment_result"			=> $result,
										"experiment_record"			=> $row['fileurl'],
										"experiment_description"	=> $row['description']);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "upload_result"){ 				// 上傳研究結果
				$question_id = $_POST["question_id"];

				$sql = "SELECT * FROM `research_form` WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					$sql = "UPDATE `research_form` SET `fileurl` = '/co.in/science/model/document/uploads_stage3-3/".$save_name."' WHERE `q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-3'";
				}else{
					$sql = "INSERT INTO `research_form`( `q_id`,
														 `p_id`,
														 `s_id`,
														 `stage`,
														 `fileurl`)
												VALUES ('".$question_id."',
														'".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'3-3',
														'/co.in/science/model/document/uploads_stage3-3/".$save_name."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-3' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '3-3'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'3-3',
													'".$check_no[1]."',
													'".$check_no[2]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新個人反思階段----------------------------------------------------------
				// $sql = "UPDATE `project_group` SET `reflection_stage` = '3', `reflection_state` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real3-3` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'3-3',
														'1',
														'3-2',
														'4-1')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了3-3階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了3-3階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage4-1_update':	// -----------------------階段4-1活動---------------------
			$type = $_POST["type"];

			if($type == "read_general"){ 						// 觀看一般性討論
				$question_id = $_POST['question_id'];			// 問題ID
				// 讀取一般性討論--------------------------------------------------------
				$sql = "SELECT * FROM `research_discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `r_q_id` = '".$question_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "discussion_id"				=> $row['d_id'],
										"discussion_pid"			=> $row["p_id"],
										"discussion_type"			=> $row["type"],
										"discussion_related"		=> $row["r_q_id"],
										"discussion_description" 	=> $row["description"],
										"discussion_time" 			=> $row["research_time"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_general"){ 					// 新增一般性討論
				$question_id = $_POST["question_id"];
				$research_type = $_POST["research_type"];
				$research_description = $_POST["research_description"];

				$sql = "SELECT * FROM `research_discussion` WHERE `r_q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					$sql = "UPDATE `research_discussion` SET `s_id` = '".$_SESSION['UID']."', `description` = '".$research_description."' WHERE `r_q_id` = '".$question_id."' AND `p_id` = '".$_SESSION['p_id']."'";
				}else{
					$sql = "INSERT INTO `research_discussion`( `p_id`,
															   `s_id`,
															   `type`,
															   `r_q_id`,
															   `description`)
													  VALUES ('".$_SESSION['p_id']."',
															  '".$_SESSION['UID']."',
															  '".$research_type."',
															  '".$question_id."',
															  '".$research_description."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_complex"){ 					// 觀看綜合性討論
				$discussion_id = $_POST['discussion_id'];		// 問題ID
				// 讀取綜合性討論--------------------------------------------------------
				$sql = "SELECT * FROM `research_discussion` WHERE `p_id` = '".$_SESSION['p_id']."' AND `d_id` = '".$discussion_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "discussion_id"				=> $row['d_id'],
										"discussion_pid"			=> $row["p_id"],
										"discussion_type"			=> $row["type"],
										"discussion_related"		=> $row["r_q_id"],
										"discussion_description" 	=> $row["description"],
										"discussion_time" 			=> $row["research_time"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_complex"){ 					// 新增綜合性討論
				$research_type = $_POST["research_type"];
				$research_description = $_POST["research_description"];
				$questionlist = $_POST["questionlist"];

				$sql = "INSERT INTO `research_discussion`( `p_id`,
														   `s_id`,
														   `type`,
														   `r_q_id`,
														   `description`)
												 VALUES ( '".$_SESSION['p_id']."',
														  '".$_SESSION['UID']."',
														  '".$research_type."',
														  '".$questionlist."',
														  '".$research_description."')";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 				// 送出審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '4-1' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '4-1'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'4-1',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real4-1` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'4-1',
														'1',
														'3-3',
														'4-2')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了4-1階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了4-1階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage4-2_update':	// -----------------------階段4-2活動---------------------
			$type = $_POST["type"];
			
			if($type == "read_conclusion"){ 						// 編輯研究結論
				$conclusion_id = $_POST['conclusion_id'];			// 結論ID
				// 讀取研究結論----------------------------------------------------------
				$sql = "SELECT * FROM `research_conclusion` WHERE `c_id` = '".$conclusion_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "conclusion_id"				=> $row['c_id'],
										"conclusion_pid"			=> $row["p_id"],
										"conclusion_content"		=> $row["content"],
										"conclusion_time" 			=> $row["research_time"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_conclusion"){ 					// 新增研究討論
				$research_content = $_POST["research_content"];

				$sql = "INSERT INTO `research_conclusion`( `p_id`,
														   `s_id`,
														   `content`)
													VALUES ('".$_SESSION['p_id']."',
															'".$_SESSION['UID']."',
															'".$research_content."')";
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){ 					// 送出審核表
				$check_num = $_POST["check_num"];

				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '4-2' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['p_id']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '4-2'";
				}else{
					// 新增檢核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'4-2',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新個人反思階段----------------------------------------------------------
				// $sql = "UPDATE `project_group` SET `reflection_stage` = '4', `reflection_state` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real4-2` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'4-2',
														'1',
														'4-1',
														'5-1')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了4-2階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了4-2階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage5-1_update':	// -----------------------階段5-1活動---------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage5-1/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "upload_report"){ 						// 上傳作品報告書
				if($save_name != ''){							// 判斷值是否為空
					$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						$sql = "UPDATE `research_report` SET `fileurl` = '/co.in/science/model/document/uploads_stage5-1/".$save_name."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1'";
					}else{
						$sql = "INSERT INTO `research_report`(  `p_id`,
																`s_id`,
																`stage`,
																`fileurl`)
														VALUES ('".$_SESSION['p_id']."',
																'".$_SESSION['UID']."',
																'5-1',
																'/co.in/science/model/document/uploads_stage5-1/".$save_name."')";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){				// 繳交審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['UID']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-1'";
				}else{
					// 新增審核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'5-1',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."')";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real5-1` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'5-1',
														'1',
														'4-2',
														'5-2')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了5-1階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了5-1階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage5-2_update':	// -----------------------階段5-2活動---------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/uploads_stage5-2/";
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					$allowed = array('application/vnd.ms-powerpoint', 'application/msword', 'application/pdf');
					// if(in_array($sub_type, $allowed)){
						if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
							$check = 0;
							$i = 1;
							$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
							while ($check == 0) {
								$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
								if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
									$i++;
								}else{
									move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
									$save_name = $sub_new_name;	// 實際存檔的名稱
									$check++;
								}
							}
						}else{
							move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
							$save_name = $sub_name;	// 實際存檔的名稱
						}
					// }else{
					// 	echo '請上傳PPT/PDF類別檔案。';
					// }
				}
			}
			if($type == "upload_report"){ 						// 上傳海報
				$order = $_POST["order"];

				if($save_name != ''){							// 判斷值是否為空
					$sql = "SELECT * FROM `research_report` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '".$order."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						$sql = "UPDATE `research_report` SET `fileurl` = '/co.in/science/model/document/uploads_stage5-2/".$save_name."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' AND `order` = '".$order."'";
					}else{
						$sql = "INSERT INTO `research_report`(  `p_id`,
																`s_id`,
																`stage`,
																`order`,
																`fileurl`)
														VALUES ('".$_SESSION['p_id']."',
																'".$_SESSION['UID']."',
																'5-2',
																'".$order."',
																'/co.in/science/model/document/uploads_stage5-2/".$save_name."')";
					}
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "check_research"){				// 繳交審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表-----------------------------------------------------------
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['UID']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-2'";
				}else{
					// 新增審核表-----------------------------------------------------------
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'5-2',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."')";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real5-2` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'5-2',
														'1',
														'5-1',
														'5-3')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了5-2階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了5-2階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage5-3_update':	// -----------------------階段5-3活動---------------------
			$type = $_POST["type"];

			if($type == "add_video"){ 							// 新增影片
				$research_vedio = $_POST["research_vedio"];

				$sql = "SELECT * FROM `research_report` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-3'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					// 更新影片
					$sql = "UPDATE `research_report` SET `fileurl` = '".$research_vedio."' WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-3'";
				}else{
					// 上傳影片
					$sql = "INSERT INTO `research_report`(`p_id`,
														  `s_id`,
														  `stage`,
														  `fileurl`)
												  VALUES( '".$_SESSION['p_id']."',
														  '".$_SESSION['UID']."',
														  '5-3',
														  '".$research_vedio."')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_check_video"){				// 繳交審核表
				$check_num = $_POST["check_num"];
				for($i = 1; $i <= $check_num; $i++){
					$check_no[$i] = $_POST["check_".$i];
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-3' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['UID']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-3'";
				}else{
					// 新增審核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`,
													`no_01`,
													`no_02`,
													`no_03`,
													`no_04`,
													`no_05`,
													`no_06`,
													`no_07`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'5-3',
													'".$check_no[1]."',
													'".$check_no[2]."',
													'".$check_no[3]."',
													'".$check_no[4]."',
													'".$check_no[5]."',
													'".$check_no[6]."',
													'".$check_no[7]."')";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real5-3` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'5-3',
														'1',
														'5-2',
														'5-4')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了5-3階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了5-3階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'stage5-4_update':	// -----------------------階段5-4活動---------------------
			$type = $_POST["type"];

			if($type == "add_qna"){ 							// 回覆Q&A
				$orderlist = $_POST["orderlist"];
				$answerlist = $_POST["answerlist"];
				// 回答Q&A問題-----------------------------------------------
				foreach($answerlist as $key => $value){
					$sql = "UPDATE `research_qna` SET `answer` = '".$value."' WHERE `p_id`= '".$_SESSION['p_id']."' AND `order` = '".$orderlist[$key]."'";

					mysql_query($sql, $link) or die(mysql_error());		// 不可以跳出框框在關起來
				}
				// 檢查是否有相同的檢核表-----------------------------------------------
				$l_sql = "SELECT `stage` FROM `checklist` WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-4' ORDER BY `check_time` DESC LIMIT 0, 1";
				$l_qry = mysql_query($l_sql, $link) or die(mysql_error());
				if(mysql_num_rows($l_qry) > 0){ 	// 已有之前的檢核表 
					// 更新檢核表
					$sql = "UPDATE `checklist` SET `u_id` = '".$_SESSION['UID']."' WHERE `p_id` = '".$_SESSION['p_id']."' AND `stage` = '5-4'";
				}else{
					// 新增審核表
					$sql = "INSERT INTO `checklist`(`p_id`,
													`u_id`,
													`stage`)
											VALUES ('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'5-4')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 更改小組審核狀態----------------------------------------------------------
				$sql = "UPDATE `project` SET `examine` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新小組進度頻率----------------------------------------------------------
				$sql = "UPDATE `project_perform` SET `times_examine` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 更新個人反思階段----------------------------------------------------------
				// $sql = "UPDATE `project_group` SET `reflection_stage` = '5', `reflection_state` = '1' WHERE `p_id` = '".$_SESSION['p_id']."'";
				// 	mysql_query($sql, $link) or die(mysql_error());
				// 更新實際繳交時間-------------------------------------------------------
				$sql = "UPDATE `project_schedule` SET `real5-4` = NOW() WHERE `p_id`= '".$_SESSION['p_id']."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 傳送審核訊息給老師-------------------------------------------------------------
				$sql = "INSERT INTO `project_examine`(	`p_id`,
														`t_id`,
														`stage`,
														`result`,
														`last_stage`,
														`next_stage`)
												VALUES( '".$_SESSION['p_id']."',
														'".$_SESSION['t_m_id']."',
														'5-4',
														'1',
														'5-3',
														'5-4')";
					mysql_query($sql, $link) or die(mysql_error());
				// 新增老師的最新消息---------------------------------------------------------
				$sql = "INSERT INTO `news`( `u_id`,
											`type`,
											`title`,
											`page_url`,
											`news_time`)
									VALUES ('".$_SESSION['t_m_id']."',
											'2',
											'".$_SESSION['pname']."：小組完成了5-4階段，趕快進入任務審核觀看小組的成果吧！',
											'/co.in/science/teacher/',
											NOW())";
					mysql_query($sql, $link) or die(mysql_error());

				if($_SESSION['t_s_id'] != NULL){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$_SESSION['t_s_id']."',
												'2',
												'".$_SESSION['pname']."：小組完成了5-4階段，趕快進入任務審核觀看小組的成果吧！',
												'/co.in/science/teacher/',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$_SESSION['p_id']."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息------------------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`)
										VALUES ('".$value."',
												'3',
												'".$_SESSION['pname']."：到個人日誌裡紀錄一下自己的學習狀況吧，組長別忘了紀錄小組每次的討論唷！',
												'/co.in/science/student/nav_diary.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'database_update':	// ------------------------資料庫活動---------------------
			$type = $_POST["type"];

			$check_file_exist = 0;								// 判斷是否有附加檔案，0: 無 1: 有
			if(isset($_FILES["files"])){
				$check_file_exist = 1;
				$errorIndex = $_FILES["files"]["error"]; 		// 錯誤訊息
				$sub_name = $_FILES["files"]["name"];			// 檔案名稱
				$sub_type =  $_FILES["files"]["type"];			// 檔案類型
				$sub_tmp_name = $_FILES["files"]["tmp_name"];	// 暫存檔
				$save_name = "";								// 實際存檔的名稱
				$uploads_dir = "../../../model/document/database/";
				if($errorIndex > 0){							// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_name))){ // 如果檔案名稱已經存在，則在後面加上(#)
						$check = 0;
						$i = 1;
						$sub_name = explode('.', $sub_name); // 切割檔案名稱和附檔名
						while ($check == 0) {
							$sub_new_name = $sub_name[0]."(".$i.").".$sub_name[1];	// 新檔案名稱
							if(file_exists($uploads_dir.iconv("UTF-8", "big5", $sub_new_name))){
								$i++;
							}else{
								move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_new_name));
								$save_name = $sub_new_name;	// 實際存檔的名稱
								$check++;
							}
						}
					}else{
						move_uploaded_file($sub_tmp_name, $uploads_dir.iconv("UTF-8", "big5", $sub_name));
						$save_name = $sub_name;	// 實際存檔的名稱
					}
				}
			}
			if($type == "add_database"){ 						// 新增資料庫
				$kwd_type = $_POST['kwd_type'];					// 位置
				$kwd_id = $_POST['kwd_id'];
				// 表單傳輸值
				if($kwd_type == '1'){							// 網站
					$database_new_title = $_POST['database_new_title1'];
					$database_new_name = $_POST['database_new_name1'];
					$database_new_src = $_POST['database_new_src1'];
					$database_new_description = $_POST['database_new_description1'];

					$sql = "INSERT INTO `database`( `p_id`,
													`s_id`,
													`category`,
													`title`,
													`src_name`,
													`src_link`,
													`description`)
											 VALUES('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'".$kwd_type."',
													'".$database_new_title."',
													'".$database_new_name."',
													'".$database_new_src."',
													'".$database_new_description."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else if($kwd_type == '2'){						// 雜誌
					$database_new_title = $_POST['database_new_title2'];
					$database_new_name = $_POST['database_new_name2'];
					$database_new_authors = $_POST['database_new_authors2'];
					$database_new_fpage = $_POST['database_new_fpage2'];
					$database_new_epage = $_POST['database_new_epage2'];
					$database_new_pdate = $_POST['database_new_pdate2'];
					$database_new_description = $_POST['database_new_description2'];

					$sql = "INSERT INTO `database`( `p_id`,
													`s_id`,
													`category`,
													`title`,
													`src_name`,
													`pages`,
													`published_date`,
													`description`)
											 VALUES('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'".$kwd_type."',
													'".$database_new_title."',
													'".$database_new_name."',
													'".$database_new_fpage."-".$database_new_epage."',
													'".$database_new_pdate."',
													'".$database_new_description."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else if($kwd_type == '3'){						// 書籍
					$database_new_title = $_POST['database_new_title3'];
					$database_new_name = $_POST['database_new_name3'];
					$database_new_authors = $_POST['database_new_authors3'];
					$database_new_fpage = $_POST['database_new_fpage3'];
					$database_new_epage = $_POST['database_new_epage3'];
					$database_new_publisher = $_POST['database_new_publisher3'];
					$database_new_pdate = $_POST['database_new_pdate3'];
					$database_new_description = $_POST['database_new_description3'];

					$sql = "INSERT INTO `database`( `p_id`,
													`s_id`,
													`category`,
													`title`,
													`src_name`,
													`pages`,
													`publisher`,
													`published_date`,
													`description`)
											 VALUES('".$_SESSION['p_id']."',
													'".$_SESSION['UID']."',
													'".$kwd_type."',
													'".$database_new_title."',
													'".$database_new_name."',
													'".$database_new_fpage."-".$database_new_epage."',
													'".$database_new_publisher."',
													'".$database_new_pdate."',
													'".$database_new_description."')";
						mysql_query($sql, $link) or die(mysql_error());
				}else if($kwd_type == '4'){						// 圖片
					$database_new_title = $_POST['database_new_title4'];
					$database_new_name = $_POST['database_new_name4'];
					$database_new_description = $_POST['database_new_description4'];

					if($check_file_exist == 0){				// 判斷是否有附加檔案
						$sql = "INSERT INTO `database`( `p_id`,
														`s_id`,
														`category`,
														`title`,
														`src_name`,
														`description`,
														`fileurl`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$kwd_type."',
														'".$database_new_title."',
														'".$database_new_name."',
														'".$database_new_description."',
														'/co.in/science/model/images/project_null.jpg')";
							mysql_query($sql, $link) or die(mysql_error());
					}else{										// 其他
						$sql = "INSERT INTO `database`( `p_id`,
														`s_id`,
														`category`,
														`title`,
														`src_name`,
														`description`,
														`fileurl`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$kwd_type."',
														'".$database_new_title."',
														'".$database_new_name."',
														'".$database_new_description."',
														'/co.in/science/model/document/database/".$save_name."')";
							mysql_query($sql, $link) or die(mysql_error());
					}
				}else if($kwd_type == '5'){						// 其他
					$database_new_title = $_POST['database_new_title5'];
					$database_new_name = $_POST['database_new_name5'];
					$database_new_description = $_POST['database_new_description5'];

					if($check_file_exist == 0){				//判斷是否有附加檔案
						$sql = "INSERT INTO `database`( `p_id`,
														`s_id`,
														`category`,
														`title`,
														`src_name`,
														`description`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$kwd_type."',
														'".$database_new_title."',
														'".$database_new_name."',
														'".$database_new_description."')";
							mysql_query($sql, $link) or die(mysql_error());
					}else{
						$sql = "INSERT INTO `database`( `p_id`,
														`s_id`,
														`category`,
														`title`,
														`src_name`,
														`description`,
														`fileurl`)
												 VALUES('".$_SESSION['p_id']."',
														'".$_SESSION['UID']."',
														'".$kwd_type."',
														'".$database_new_title."',
														'".$database_new_name."',
														'".$database_new_description."',
														'/co.in/science/model/document/database/".$save_name."')";
							mysql_query($sql, $link) or die(mysql_error());
					}
				}
				// 抓取資料庫ID--------------------------------------------------------------
				$d_sql = "SELECT `d_id` FROM `database` WHERE `title` = '".$database_new_title."' AND `src_name` = '".$database_new_name."' AND `p_id` = '".$_SESSION['p_id']."' ORDER BY `d_id` DESC LIMIT 0, 1";
				$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
				$d_row = mysql_fetch_array($d_qry);
					$databaseID = $d_row["d_id"];
				// 新增資料庫關鍵字----------------------------------------------------------
				foreach($kwd_id as $value){
					$k_sql = "INSERT INTO `database_cnt`(`d_id`,
														 `k_id`)
												VALUES ('".$databaseID."',
														'".$value."')";
					mysql_query($k_sql, $link) or die(mysql_error());
				}
			}else if($type == "read_database"){ 				// 讀取資料庫
				$database_type = $_POST["database_type"];
				// 讀取資料庫----------------------------------------------------------------
				$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION['p_id']."' AND `category` = '".$database_type."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取建立人
						$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$name = $p_row['name'];
						// 抓取關聯式關鍵字
						$kwd_name = "";
						
						$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
						$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
						while($k_row = mysql_fetch_array($k_qry)){
							$kwd_name .= $k_row['name']."<br />";
						}

						$arr[] = array( "database_id"			=> $row["d_id"],
										"database_type"			=> $row["category"],
										"database_user"			=> $name,
										"database_title"		=> $row["title"],
										"database_name"			=> $row["src_name"],
										"database_link"			=> $row["src_link"],
										"database_authors"		=> $row["authors"],
										"database_kwd"			=> $kwd_name,
										"database_fileurl"		=> $row["fileurl"],
										"database_time"			=> date("Y-m-d",strtotime($row["database_time"])));
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_kwd"){ 						// 新增關鍵字
				$kwd_name = $_POST['kwd_name'];					// 關鍵字
				// 新增關鍵字------------------------------------------------------
				$sql = "INSERT INTO `database_kwd`( `p_id`,
													`name`)
											 VALUES('".$_SESSION['p_id']."',
													'".$kwd_name."')";
					mysql_query($sql, $link) or die(mysql_error());
				// 讀取關鍵字------------------------------------------------------
				$sql = "SELECT * FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."' AND `name` = '".$kwd_name."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "kwd_id"			=> $row["k_id"],
										"kwd_pid"			=> $row["p_id"],
										"kwd_name"			=> $row["name"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'log_update':		// ----------------------記錄使用者LOG--------------------
			$type = $_POST["type"];
			
			if($type == "record_log"){ 							// 紀錄LOG
				$log = $_POST["log"];
				// 紀錄使用者LOG-----------------------------------------------------------
				$sql = "INSERT INTO `log` ( `u_id`,
											`identity`,
											`log`)
									VALUES ('".$_SESSION['UID']."',
											'S',
											'".$log."')";
					mysql_query($sql, $link) or die(mysql_error());
			}
			break;
		default:
			exit();
			break;
	}
?>