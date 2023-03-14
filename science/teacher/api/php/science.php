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
				setcookie(session_name(), '', time()-42000, '/'); //刪除sessin id.由於session默認是基於cookie的，所以使用setcookie刪除包含session id的cookie.
			}
			setcookie("PHPSESSID", '', time()-42000, '/'); //清除session在cookie裡面的殘值
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
		case 'guide_update':	// -----------------------教師指導手冊--------------------
			$type = $_POST["type"];
			
			if($type == "read_guide"){
				$g_id = $_POST['g_id'];		// 指引ID
				// 讀取指導手冊--------------------------------------------------------
				$sql = "SELECT `content` FROM `guide` WHERE `g_id`= '".$g_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				while($row = mysql_fetch_array($qry)){
					echo $row['content'];
				}
			}
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
		case 'project_update':	// -------------------------專題管理----------------------
			$type = $_POST["type"];

			if($type == "apply_project"){ 							// 接受小組申請
				$project_id = $_POST['project_id'];					// 專題ID
				// 接受小組申請--------------------------------------------------------
				$sql = "UPDATE `project` SET `state` = '0' WHERE `p_id`= '".$project_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取小組名稱----------------------------------------------------------------
				$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$project_id."'";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				$p_row = mysql_fetch_array($p_qry);
					$pname = $p_row['pname'];
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$project_id."'";
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
												'0',
												'".$pname."：老師已經同意成為專題指導老師！可以開始新的專題任務囉！GO！',
												'/co.in/science/student/entrance.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "reject_project"){ 					// 拒絕小組申請
				$project_id = $_POST['project_id'];					// 專題ID
				// 拒絕小組申請--------------------------------------------------------
				$sql = "UPDATE `project` SET `state` = '3' WHERE `p_id`= '".$project_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取小組名稱----------------------------------------------------------------
				$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$project_id."'";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				$p_row = mysql_fetch_array($p_qry);
					$pname = $p_row['pname'];
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$project_id."'";
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
												'0',
												'".$pname."：老師不同意成為專題指導老師！請再次與老師確認或請小組長重新選擇指導老師！',
												'/co.in/science/student/entrance.php')";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "new_project"){ 						// 新增學生名單
				$errorIndex = $_FILES["files"]["error"]; 			// 錯誤訊息
				$filename = $_FILES["files"]["tmp_name"];			// 暫存檔
				$values = "";
		
				if($errorIndex > 0){						// 判斷檔案是否有誤
					die('檔案錯誤，請再試一次。');
				}else{
					iconv("UTF-8", "big5", $filename);

					$handle = fopen($filename, 'r');		// 開啟檔案

					$result = array();
					$n = 0;									// 解析CSV，直行橫列
					while($data = fgetcsv($handle, 10000)){
						$num = count($data);
						for($i = 0; $i < $num; $i++){
							$result[$n][$i] = $data[$i];
						}
						$n++;
					}

					if(count($result) == 0){				// 計算總共幾筆，沒有任何資料
						die('沒有任何資料！');
					}

					for($i = 3; $i < count($result); $i++){ // 循環獲取各字段值(第三行開始取值)
						$name = iconv('big5', 'utf-8', $result[$i][1]); // 中文轉碼
						$nickname = iconv('big5', 'utf-8', $result[$i][2]);
						$birthday = iconv('big5', 'utf-8', $result[$i][3]);
						$gender = iconv('big5', 'utf-8', $result[$i][4]);
						$account = iconv('big5', 'utf-8', $result[$i][5]);
						$password = iconv('big5', 'utf-8', $result[$i][6]);
						$county = iconv('big5', 'utf-8', $result[$i][8]);
						$school = iconv('big5', 'utf-8', $result[$i][9]);
						$grade = iconv('big5', 'utf-8', $result[$i][10]);
						$teacher = iconv('big5', 'utf-8', $result[$i][11]);
						$email = iconv('big5', 'utf-8', $result[$i][12]);

						$values .= "('".$name."','".$nickname."','".$birthday."','".$gender."','".$account."','".$password."','S','".$county."','".$school."','".$grade."','".$teacher."','".$email."'),";
					}

					$values = substr($values, 0, -1); // 去掉最後一個逗號
					fclose($handle); // 關閉檔案

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
													`email`) VALUES $values"; // 批次存入資料中
						mysql_query($sql, $link) or die(mysql_error());
				}	
			}else if($type == "group_project"){						// 創立新小組
				$project_pname = $_POST["project_pname"];
				$project_chief = $_POST["project_chief"];
				$project_theme = $_POST["project_theme"];
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
												`state`,
												`starttime`)
										VALUES ('".$_SESSION['UID']."',
												'".$project_pname."',
												'".$project_theme."',
												'".$project_mascot."',
												'0',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取專題ID-----------------------------------------------------------
				$p_sql = "SELECT `p_id` FROM `project` WHERE `t_m_id` = '".$_SESSION['UID']."' AND `pname` = '".$project_pname."' AND `theme` = '".$project_theme."' AND `mascot` = '".$project_mascot."' ORDER BY `project_time` DESC LIMIT 0, 1";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				$p_row = mysql_fetch_array($p_qry);
					$projectID = $p_row["p_id"];
				// 新增專題小組組員--------------------------------------------------
					// 專題組長
					$c_sql = "INSERT INTO `project_group`(`p_id`,
														  `s_id`,
														  `chief`)
												  VALUES ('".$projectID."',
														  '".$project_chief."',
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
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$projectID."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息----------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$value."',
												'0',
												'".$project_pname."：老師新建立了專題小組，趕緊去查看吧！',
												'/co.in/science/student/entrance.php',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
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
														  '".$project_chief."')";
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
			}else if($type == "finish_project"){					// 結束專題小組
				$p_id = $_POST["p_id"];
				// 結束專題-----------------------------------------------------------
				$sql = "UPDATE `project` SET `state` = '1', `finish` = '0', `endtime` = NOW() WHERE `p_id`= '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取小組名稱----------------------------------------------------------------
				$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				$p_row = mysql_fetch_array($p_qry);
					$pname = $p_row['pname'];
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$p_id."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 新增學生的最新消息----------------------------------------------
				foreach($studentID as $value){
					$sql = "INSERT INTO `news`( `u_id`,
												`type`,
												`title`,
												`page_url`,
												`news_time`)
										VALUES ('".$value."',
												'0',
												'".$pname."：老師已結束此專案囉！恭喜你們已完成了所有任務！^_^',
												'/co.in/science/student/entrance.php',
												NOW())";
					mysql_query($sql, $link) or die(mysql_error());
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'notes_update':	// -------------------------My Notes----------------------
			$type = $_POST["type"];
			
			if($type == "read_notes"){ 								// 讀取備註
				$project_id = $_POST['project_id'];		// 專題ID
				// 讀取備註--------------------------------------------------------
				$sql = "SELECT * FROM `project_notes` WHERE `p_id`= '".$project_id."' ORDER BY `n_id` DESC";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "notes_id"				=> $row["n_id"],
										"notes_pid"				=> $row["p_id"],
										"notes_content"			=> $row["content"],
										"notes_time"			=> date("Y-m-d", strtotime($row["notes_time"])));
					}
				}else{
					$arr[] = array( "notes_pid"				=> $project_id,
									"notes_content"			=> "");
				}
			}else if($type == "write_notes"){ 							// 寫入備註
				$project_id = $_POST["project_id"];
				$note_content = $_POST["project_note_content"];

				// 檢查是否有相同的筆記--------------------------------------------------
				$n_sql = "SELECT * FROM `project_notes` WHERE `p_id`= '".$project_id."' ORDER BY `n_id` DESC limit 0, 1";
				$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
				if(mysql_num_rows($n_qry) > 0){ 	// 已有筆記
					// 更新筆記
					$sql = "UPDATE `project_notes` SET `content` = '".$note_content."' WHERE `p_id` = '".$project_id."'";
				}else{
					// 新增筆記
					$sql = "INSERT INTO `project_notes`(`p_id`,
														`content`)
												VALUES( '".$project_id."',
														'".$note_content."')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'diary_update':	// -------------------------教師日誌----------------------
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
			if($type == "view_guide_diary"){ 					// 讀取指導日誌
				$diary_id = $_POST['diary_id'];					// 日誌ID
				// 讀取指導日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$diary_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取小組名稱----------------------------------------------------------------
						$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$pname = $p_row['pname'];
						// 老師指導型態------------------------------------------------------------
						// if($row['category'] == "0"){
						// 	$category = "線上指導";
						// }else if($row['category'] == "1"){
						// 	$category = "面對面指導";
						// }
						$arr[] = array( "diary_id"				=> $row["d_id"],
										"diary_pid"				=> $row["p_id"],
										"diary_date"			=> date("Y-m-d", strtotime($row["date"])),
										"diary_pname"			=> $pname,
										"diary_category"		=> $row['category'],
										"diary_content1"		=> $row["content_1"],
										"diary_content2" 		=> $row["content_2"],
										"diary_content3"		=> $row["content_3"],
										"diary_filename"		=> $row["filename"],
										"diary_fileurl"			=> $row["fileurl"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "view_reflect_diary"){ 			// 讀取反思日誌
				$diary_id = $_POST['diary_id'];					// 日誌ID
				// 讀取反思日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `u_id` = '".$_SESSION['UID']."' AND `d_id` = '".$diary_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						// 抓取小組名稱----------------------------------------------------------------
						$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
						$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
						$p_row = mysql_fetch_array($p_qry);
							$pname = $p_row['pname'];
						
						// 老師反思類型------------------------------------------------------------
						// if($row['category'] == "0"){
						// 	$category = "開始專題前";
						// }else if($row['category'] == "1"){
						// 	$category = "形成問題";
						// }else if($row['category'] == "2"){
						// 	$category = "設計計畫";
						// }else if($row['category'] == "3"){
						// 	$category = "執行計畫";
						// }else if($row['category'] == "4"){
						// 	$category = "報告與展示";
						// }else if($row['category'] == "5"){
						// 	$category = "形成結論";
						// }
						$arr[] = array( "diary_id"				=> $row["d_id"],
										"diary_pid"				=> $row["p_id"],
										"diary_date"			=> date("Y-m-d", strtotime($row["date"])),
										"diary_pname"			=> $pname,
										"diary_category"		=> $row['category'],
										"diary_content1"		=> $row["content_1"],
										"diary_content2" 		=> $row["content_2"],
										"diary_content3"		=> $row["content_3"],
										"diary_content4"		=> $row["content_4"],
										"diary_content5" 		=> $row["content_5"],
										"diary_content6"		=> $row["content_6"],
										"diary_filename"		=> $row["filename"],
										"diary_fileurl"			=> $row["fileurl"]);
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_guide_diary"){				// 新增指導日誌
				$diary_guide_date = $_POST["diary_guide_date"];
				$diary_guide_pname = $_POST["diary_guide_pname"];
				$diary_guide_category = $_POST["diary_guide_category"];
				$diary_guide_event = $_POST["diary_guide_event"];
				$diary_guide_result = $_POST["diary_guide_result"];
				$diary_guide_remark = $_POST["diary_guide_remark"];

				// 新增指導日誌------------------------------------------------------------
				$sql = "INSERT INTO `diary`(`p_id`,
											`u_id`,
											`date`,
											`type`,
											`category`,
											`content_1`,
											`content_2`,
											`content_3`)
									VALUES( '".$diary_guide_pname."',
											'".$_SESSION['UID']."',
											'".$diary_guide_date."',
											'0',
											'".$diary_guide_category."',
											'".$diary_guide_event."',
											'".$diary_guide_result."',
											'".$diary_guide_remark."')";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "add_reflect_diary"){				// 新增反思日誌
				$diary_reflect_category = $_POST["diary_reflect_category"];		// 反思種類
				$diary_reflect_pname = $_POST["diary_reflect_pname"];
				// 先判斷寫的是哪個種類
				if($diary_reflect_category == '0'){	// 開始專題前
					for($i = 1; $i <= 6; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content0".$i];
					}
					// 新增反思日誌------------------------------------------------------------
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
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."',
												'".$diary_reflect_content[5]."',
												'".$diary_reflect_content[6]."')";
				}else if($diary_reflect_category == '1'){ // 形成問題階段
					for($i = 1; $i <= 4; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content1".$i];
					}
					// 新增反思日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`category`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."')";
				}else if($diary_reflect_category == '2'){ // 設計計畫階段
					for($i = 1; $i <= 4; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content2".$i];
					}
					// 新增反思日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`category`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."')";
				}else if($diary_reflect_category == '3'){ // 執行計畫階段
					for($i = 1; $i <= 4; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content3".$i];
					}
					// 新增反思日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`category`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."')";
				}else if($diary_reflect_category == '4'){ // 形成結論階段
					for($i = 1; $i <= 4; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content4".$i];
					}
					// 新增反思日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`category`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."')";
				}else if($diary_reflect_category == '5'){ // 報告與展示階段
					for($i = 1; $i <= 4; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content5".$i];
					}
					// 新增反思日誌------------------------------------------------------------
					$sql = "INSERT INTO `diary`(`p_id`,
												`u_id`,
												`date`,
												`type`,
												`category`,
												`content_1`,
												`content_2`,
												`content_3`,
												`content_4`)
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."')";
				}else if($diary_reflect_category == '6'){ // 完成專題後
					for($i = 1; $i <= 5; $i++){
						$diary_reflect_content[$i] = $_POST["diary_reflect_content6".$i];
					}
					// 新增反思日誌------------------------------------------------------------
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
										VALUES( '".$diary_reflect_pname."',
												'".$_SESSION['UID']."',
												'".date('Y-m-d', strtotime('NOW'))."',
												'1',
												'".$diary_reflect_category."',
												'".$diary_reflect_content[1]."',
												'".$diary_reflect_content[2]."',
												'".$diary_reflect_content[3]."',
												'".$diary_reflect_content[4]."',
												'".$diary_reflect_content[5]."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "view_group"){ 					// 讀取小組日誌(小組進度管理)
				$diary_id = $_POST['diary_id'];					// 日誌ID
				$p_id = $_POST['p_id'];							// 專題小組ID
				// 讀取小組日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$p_id."' AND `d_id` = '".$diary_id."'";
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
			}else if($type == "view_personal"){ 				// 讀取個人日誌(小組進度管理)
				$diary_id = $_POST['diary_id'];					// 日誌ID
				$p_id = $_POST['p_id'];							// 專題小組ID
				// 讀取個人日誌--------------------------------------------------------
				$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$p_id."' AND `d_id` = '".$diary_id."'";
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
			}else if($type == "delete_diary"){ 					// 刪除教師日誌
				$diary_id = $_POST['diary_id'];					// 日誌ID
				// 刪除教師日誌--------------------------------------------------------
				$sql = "DELETE FROM `diary` WHERE `d_id` = '".$diary_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'examine_update':	// -------------------------任務審核----------------------
			$type = $_POST["type"];
			$p_id = $_POST["p_id"];
			$stage = $_POST["stage"];
			
			if($type == "get_examine"){ 					// 獲得審核詳情
				// 各小組階段審核詳情
				if($stage == '1-1'){
					// 讀取研究主題--------------------------------------------------------
					$sql = "SELECT * FROM `research_theme` WHERE `p_id` = '".$p_id."' AND `research` = '1' ORDER BY `t_id` DESC limit 0, 1";
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
											"theme_time"		=> date('Y-m-d', strtotime($row["research_time"])));
						}
						mysql_query($sql, $link) or die(mysql_error());
					}
					// 讀取研究題目--------------------------------------------------------
					$sql = "SELECT * FROM `research_topic` WHERE `p_id` = '".$p_id."' AND `research` = '1' ORDER BY `t_id` DESC limit 0, 1";
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
							$d_sql = "SELECT `d_id` FROM `research_topic_data` WHERE `t_id` = '".$row["t_id"]."'";
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
											"topic_time"		=> date('Y-m-d', strtotime($row["research_time"])));
						}
						mysql_query($sql, $link) or die(mysql_error());
					}
				}else if($stage == '2-1'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 操縱變因
							$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								$independent_name = $i_row['name'];
							}
							// 應變變因
							$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
							while($d_row = mysql_fetch_array($d_qry)){
								$dependent_name = $d_row['name'];
							}

							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_independent"	=> $independent_name,
											"question_dependent" 	=> $dependent_name,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '2-2'){
					$order = $_POST["order"];
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."' AND `order` = '".$order."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 研究問題ID
							$q_id = $row['q_id'];
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 操縱變因
							$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								$independent_name = $i_row['name'];
							}
							// 應變變因
							$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
							while($d_row = mysql_fetch_array($d_qry)){
								$dependent_name = $d_row['name'];
							}
							// 最後問題幾號
							$o_sql = "SELECT `order` FROM `research_question` WHERE `p_id` = '".$p_id."' ORDER BY `q_id` DESC limit 0, 1";
							$o_qry = mysql_query($o_sql, $link) or die(mysql_error());
							$o_row = mysql_fetch_array($o_qry);
								$number = $o_row['order'];

							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_independent"	=> $independent_name,
											"question_dependent" 	=> $dependent_name,
											"question_number" 		=> $number,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
					// 抓取問題步驟-----------------------------------------------------------
					$sql = "SELECT * FROM `research_idea_steps` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$q_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							$arr[] = array( "question_id"			=> $row['q_id'],
											"steps_order"			=> $row['steps_order'],
											"steps_name"			=> $row['steps_name']);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
					// 抓取問題工具&紀錄表---------------------------------------------------
					$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$q_id."' AND `kind` = '1'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							if($row['type'] == '0'){			// 記錄方式
								$arr[] = array( "question_id"			=> $row['q_id'],
												"record_id"				=> $row['i_id'],
												"record_name"			=> $row['name']);
							}else{								// 工具&材料
								// 抓取材料&工具(圖片)
								$c_sql = "SELECT * FROM `research_idea_pic` WHERE `i_id` = '".$row['i_id']."'";
								$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
								while($c_row = mysql_fetch_array($c_qry)){
									$material_pic = $c_row['pic_url'];
								}

								$arr[] = array( "question_id"			=> $row['q_id'],
												"material_id"			=> $row['i_id'],
												"material_name"			=> $row['name'],
												"material_number"		=> $row['number'],
												"material_description"	=> $row['description'],
												"material_pic"			=> $material_pic);
							}
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '2-3'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 操縱變因
							$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								$independent_name = $i_row['name'];
							}
							// 應變變因
							$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
							while($d_row = mysql_fetch_array($d_qry)){
								$dependent_name = $d_row['name'];
							}
							// 紀錄表格
							$r_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '2-3'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$record_fileurl = $r_row["fileurl"];

							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_independent"	=> $independent_name,
											"question_dependent" 	=> $dependent_name,
											"question_fileurl" 		=> $record_fileurl,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '2-4'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 讀取嘗試性實驗
							$i_sql = "SELECT * FROM `research_pilot` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."'  AND `research` = '1' ORDER BY `r_id` DESC";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								// 實驗結果
								if($i_row['result'] == '0'){
									$result = '成功';
								}else{
									$result = '失敗';
								}
							}
							
							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_result"		=> $result,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '2-4-p'){
					$order = $_POST["order"];
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."' AND `order` = '".$order."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 讀取嘗試性實驗
							$i_sql = "SELECT * FROM `research_pilot` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row['q_id']."'";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								// 實驗結果
								if($i_row['result'] == '0'){
									$result = '成功';
								}else{
									$result = '失敗';
								}
								// 實驗說明
								$description = $i_row['description'];
								// 實驗應注意事項
								$attention = $i_row['attention'];
								// 是否需要修改
								if($i_row['fixed'] == '0'){
									$fixed = '否，我認為應該不用修改。';
								}else{
									$fixed = '是，我認為應該要修改。';
								}
								// 實驗檔案
								$fileurl = $i_row['fileurl'];
							}
							
							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_result"		=> $result,
											"question_description"	=> $description,
											"question_attention"	=> $attention,
											"question_fixed"		=> $fixed,
											"question_fileurl"		=> $fileurl,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '3-1'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 紀錄表格
							$r_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '2-3'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$record = $r_row["fileurl"];
							// 讀取實驗日誌
							$i_sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' ORDER BY `e_id` DESC";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								// 實驗結果
								if($i_row['result'] == '0'){
									$result = '成功';
								}else{
									$result = '失敗';
								}
							}
							
							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_result"		=> $result,
											"question_record"		=> $record,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '3-1-e'){
					$order = $_POST["order"];
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."' AND `order` = '".$order."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 操縱變因
							$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
							while($i_row = mysql_fetch_array($i_qry)){
								$independent_name = $i_row['name'];
							}
							// 應變變因
							$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$p_id."' limit 0, 1";
							$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
							while($d_row = mysql_fetch_array($d_qry)){
								$dependent_name = $d_row['name'];
							}
							// 讀取實驗日誌
							$e_sql = "SELECT * FROM `research_experiment` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' ORDER BY `e_id` DESC";
							$e_qry = mysql_query($e_sql, $link) or die(mysql_error());
							while($e_row = mysql_fetch_array($e_qry)){
								// 實驗結果
								if($e_row['result'] == '0'){
									$result = '成功';
								}else{
									$result = '失敗';
								}
								// 實驗日期
								$date = $e_row['date'];
								// 附加檔案
								$fileurl = $e_row["fileurl"];
								// 結果描述
								$description = $e_row["description"];
							}
							
							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_independent"	=> $independent_name,
											"question_dependent" 	=> $dependent_name,
											"question_date"			=> $date,
											"question_result"		=> $result,
											"question_description"	=> $description,
											"question_fileurl"		=> $fileurl,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '3-2'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 紀錄表格
							$r_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '3-2'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$record_fileurl = $r_row["fileurl"];

							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_fileurl" 		=> $record_fileurl,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '3-3'){
					// 讀取研究問題--------------------------------------------------------
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							// 紀錄表格
							$a_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '3-2'";
							$a_qry = mysql_query($a_sql, $link) or die(mysql_error());
							$a_row = mysql_fetch_array($a_qry);
								$fileurl1 = $a_row["fileurl"];
							// 資料結果
							$r_sql = "SELECT `fileurl` FROM `research_form` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row["q_id"]."' AND `stage` = '3-3'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$fileurl2 = $r_row["fileurl"];

							$arr[] = array( "question_id"			=> $row['q_id'],
											"question_pid"			=> $row["p_id"],
											"question_user"			=> $name,
											"question_order"		=> $row["order"],
											"question_name"			=> $row["question"],
											"question_assume" 		=> $row["assume"],
											"question_fileurl1" 	=> $fileurl1,
											"question_fileurl2" 	=> $fileurl2,
											"question_time"			=> $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '4-1'){
					// 讀取研究討論--------------------------------------------------------
					$sql = "SELECT * FROM `research_discussion` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							//  討論類型
							if($row['type'] == '0'){
								$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$row['r_q_id']."'";
								$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
								while($q_row = mysql_fetch_array($q_qry)){
									$arr[] = array( "discussion_id"			 => $row['d_id'],
													"discussion_pid"		 => $row["p_id"],
													"discussion_user"		 => $name,
													"discussion_type"		 => $row['type'],
													"discussion_related"	 => $q_row['question'],
													"discussion_description" => $row["description"],
													"discussion_time"		 => $row["research_time"]);
								}
							}else if($row['type'] == '1'){
								$question = explode(",", $row["r_q_id"]); 				// 切割相關問題
								$question = array_diff($question, array(null));			// 刪除空值

								for($j = 0; $j < count($question); $j++){
									if($question[$j] != "" || $question[$j] != null){
										$q_sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$p_id."' AND `q_id` = '".$question[$j]."'";
										$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
										$q_row = mysql_fetch_array($q_qry);
											$question_id[$j] = $q_row['q_id'];			// 取得研究問題ID
											$question_name[$j] = $q_row['question'];	// 取得研究問題
									}
								}
								$arr[] = array( "type"					 => "topic",
												"discussion_id"			 => $row['d_id'],
												"discussion_pid"		 => $row["p_id"],
												"discussion_user"		 => $name,
												"discussion_type"		 => $row['type'],
												"discussion_description" => $row["description"],
												"discussion_time"		 => $row["research_time"]);

								for($j = 0; $j < count($question); $j++){
									$arr[] = array( "type"					 => "question",
									 				"discussion_id"			 => $row['d_id'],
													"discussion_pid"		 => $row["p_id"],
													"discussion_user"		 => $name,
													"discussion_type"		 => $row['type'],
													"discussion_related"	 => $question_name[$j],
													"discussion_description" => $row["description"],
													"discussion_time"		 => $row["research_time"]);
								}
							}
						}
					}
					mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '4-2'){
					// 讀取研究結論--------------------------------------------------------
					$sql = "SELECT * FROM `research_conclusion` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發言人
							$p_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["s_id"]."' limit 0, 1";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$name = $p_row['name'];
							
							$arr[] = array( "conclusion_id"			 => $row['c_id'],
											"conclusion_pid"		 => $row["p_id"],
											"conclusion_user"		 => $name,
											"conclusion_content"	 => $row['content'],
											"discussion_time"		 => $row["research_time"]);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '5-1'){
					// 讀取專題小組--------------------------------------------------------
					$sql = "SELECT `p_id`, `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 作品報告書
							$r_sql = "SELECT `fileurl` FROM `research_report` WHERE `p_id` = '".$row["p_id"]."' AND `stage` = '5-1'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$complete_fileurl = $r_row["fileurl"];

							$arr[] = array( "complete_pid"			=> $row["p_id"],
											"complete_pname"		=> $row["pname"],
											"complete_fileurl" 		=> $complete_fileurl);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '5-2'){
					$report_fileurl0 = "";
					$report_fileurl1 = "";
					$report_fileurl2 = "";
					$report_fileurl3 = "";
					// 讀取專題小組--------------------------------------------------------
					$sql = "SELECT `p_id`, `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 作品海報
							$r_sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id` = '".$row["p_id"]."' AND `stage` = '5-2'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							while($r_row = mysql_fetch_array($r_qry)){
								if($r_row["order"] == '0'){
									$report_fileurl0 = $r_row["fileurl"];
								}else if($r_row["order"] == '1'){
									$report_fileurl1 = $r_row["fileurl"];
								}else if($r_row["order"] == '2'){
									$report_fileurl2 = $r_row["fileurl"];
								}else if($r_row["order"] == '3'){
									$report_fileurl3 = $r_row["fileurl"];
								}
							}

							$arr[] = array( "report_pid"			=> $row["p_id"],
											"report_pname"			=> $row["pname"],
											"report_fileurl0" 		=> $report_fileurl0,
											"report_fileurl1" 		=> $report_fileurl1,
											"report_fileurl2" 		=> $report_fileurl2,
											"report_fileurl3" 		=> $report_fileurl3);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '5-3'){
					// 讀取專題小組--------------------------------------------------------
					$sql = "SELECT `p_id`, `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 作品影片
							$r_sql = "SELECT `order`, `fileurl` FROM `research_report` WHERE `p_id` = '".$row["p_id"]."' AND `stage` = '5-3'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							$r_row = mysql_fetch_array($r_qry);
								$vedio_fileurl = $r_row["fileurl"];

							$arr[] = array( "vedio_pid"			=> $row["p_id"],
											"vedio_pname"			=> $row["pname"],
											"vedio_fileurl" 		=> $vedio_fileurl);
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}else if($stage == '5-4'){
					$qna_question = "";
					$qna_answer = "";

					// 讀取專題小組--------------------------------------------------------
					$sql = "SELECT `p_id`, `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 問與答
							$r_sql = "SELECT * FROM `research_qna` WHERE `p_id` = '".$row["p_id"]."'";
							$r_qry = mysql_query($r_sql, $link) or die(mysql_error());
							while($r_row = mysql_fetch_array($r_qry)){

							$arr[] = array( "qna_pid"		=> $row["p_id"],
											"qna_pname"		=> $row["pname"],
											"qna_order"  	=> $r_row["order"],
											"qna_question"  => $r_row["question"],
											"qna_answer" 	=> $r_row["answer"]);
							}
						}
					}
						mysql_query($sql, $link) or die(mysql_error());
				}
			}else if($type == "check_examine"){				// 審核各小組
				$pass = $_POST["pass"];
				$examine_pass_content = $_POST["examine_pass_content"];
				$examine_pass_back = $_POST["examine_pass_back"];
				// 抓取小組名稱----------------------------------------------------------------
				$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
				$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
				$p_row = mysql_fetch_array($p_qry);
					$pname = $p_row['pname'];
				// 抓取專題成員ID(array)-------------------------------------------------
				$studentID = [];
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$p_id."'";
				$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
				while($s_row = mysql_fetch_array($s_qry)){
					$studentID[] = $s_row['s_id'];
				}
				// 階段審核階段是否通過------------------------------------------------------
				if($pass == 'pass'){
					// 確定審核通過
					$sql = "UPDATE `project_examine` SET `result` = '0', `comment` = '".$examine_pass_content."' WHERE `p_id` = '".$p_id."' AND `stage` = '".$stage."' ORDER BY `p_e_id` DESC limit 1";
						mysql_query($sql, $link) or die(mysql_error());
					// 讀取下一階段
					$n_sql = "SELECT * FROM `project_examine` WHERE `p_id` = '".$p_id."' AND `stage` = '".$stage."' AND `comment` = '".$examine_pass_content."' ORDER BY `p_e_id` DESC limit 0, 1";
					$n_qry = mysql_query($n_sql, $link) or die(mysql_error());
					$n_row = mysql_fetch_array($n_qry);
						$next_stage = $n_row['next_stage'];
					// 更新小組階段
					$sql = "UPDATE `project` SET `stage` = '".$next_stage."', `examine` = '0' WHERE `p_id` = '".$p_id."'";
						mysql_query($sql, $link) or die(mysql_error());
					// 更新閱讀指引
					$sql = "UPDATE `project_group` SET `hint_state` = '1' WHERE `p_id` = '".$p_id."'";
						mysql_query($sql, $link) or die(mysql_error());
					// 更新個人反思階段
					if($stage == "1-1"){
						$where = "AND `category` = '1'";
						$reflection = "1";
					}else if($stage == "2-4"){
						$where = "AND `category` = '2'";
						$reflection = "2";
					}else if($stage == "3-3"){
						$where = "AND `category` = '3'";
						$reflection = "3";
					}else if($stage == "4-2"){
						$where = "AND `category` = '4'";
						$reflection = "4";
					}else if($stage == "5-4"){
						$where = "AND `category` = '5'";
						$reflection = "5";
					}else{		// 非最後一階段
						$where = "";
						$reflection = "";
					}
					// 檢查是否有反思日誌，已有反思日誌則不再更新送出
					$sql = "SELECT * FROM `diary` WHERE `p_id` = '".$p_id."' AND `type` = '1' $where";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) == 0){	// 確定無值
						// 更改反思日誌狀態
						$r_sql = "UPDATE `project_group` SET `reflection_stage` = '".$reflection."', `reflection_state` = '1' WHERE `p_id` = '".$p_id."'";
							mysql_query($r_sql, $link) or die(mysql_error());
					}
						mysql_query($sql, $link) or die(mysql_error());
					// 檢查是否為十四個階段裡的其中之一
					$sql = "SELECT * FROM `stage` WHERE `stage` = '".$stage."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) == 0){	// 確定無值，則為嘗試性實驗
						// 取得問題ID
						$pilot_order = substr($_POST["stage"], 4, -1);

						$q_sql = "SELECT `q_id` FROM `research_question` WHERE `p_id` = '".$p_id."' AND `order` = '".$pilot_order."' limit 0, 1";
						$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
						$q_row = mysql_fetch_array($q_qry);
							$pilot_qid = $q_row['q_id'];
						// 嘗試性實驗，修改為結果
						$p_sql = "UPDATE `research_pilot` SET `research` = '1' WHERE `p_id` = '".$p_id."' AND `q_id` ='".$pilot_qid."'";
							mysql_query($p_sql, $link) or die(mysql_error());
					}
						mysql_query($sql, $link) or die(mysql_error());
					// 新增學生的最新消息
					foreach($studentID as $value){
						$sql = "INSERT INTO `news`( `u_id`,
													`type`,
													`title`,
													`page_url`)
											VALUES ('".$value."',
													'2',
													'".$pname."：恭喜！老師已經審核通過".$stage."階段，趕快進入下一個階段繼續進行吧！',
													'/co.in/science/student/nav_project.php')";
						mysql_query($sql, $link) or die(mysql_error());
					}
				}else if($pass == 'unpass'){
					// 確定審核不通過
					$sql = "UPDATE `project_examine` SET `result` = '2', `comment` = '".$examine_pass_content."' WHERE `p_id` = '".$p_id."' AND `stage` = '".$stage."'";
						mysql_query($sql, $link) or die(mysql_error());
					// 退回小組階段
					$sql = "UPDATE `project` SET `stage` = '".$examine_pass_back."', `examine` = '0' WHERE `p_id` = '".$p_id."'";
						mysql_query($sql, $link) or die(mysql_error());
					// 檢查是否為十四個階段裡的其中之一
					$sql = "SELECT * FROM `stage` WHERE `stage` = '".$stage."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) == 0){	// 確定無值，則為嘗試性實驗
						// 取得問題ID
						$pilot_order = substr($_POST["stage"], 4, -1);

						$q_sql = "SELECT `q_id` FROM `research_question` WHERE `p_id` = '".$p_id."' AND `order` = '".$pilot_order."' limit 0, 1";
						$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
						$q_row = mysql_fetch_array($q_qry);
							$pilot_qid = $q_row['q_id'];
						// 嘗試性實驗，修改為結果
						$p_sql = "UPDATE `research_pilot` SET `research` = '2' WHERE `p_id` = '".$p_id."' AND `q_id` ='".$pilot_qid."'";
							mysql_query($p_sql, $link) or die(mysql_error());
					}else{
						// 刪除審核表
						$stage_arr = array("1-1", "1-2", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4");

						for($i = 0; $i < count($stage_arr); $i++){
							// 當前階段
							if($stage == $stage_arr[$i]){
								if($i == '0'){
									$k = $i+1;	// 當前1-1
								}else{
									$k = $i;	// 當前
								}
							}
							// 退回階段
							if($examine_pass_back == $stage_arr[$i]){
								$j = $i;	// 最後一個
							}
						}

						for($l = $k; $l >= $j; $l--){
							$sql = "DELETE FROM `checklist` WHERE `p_id` = '".$p_id."' AND `stage` = '".$stage_arr[$l]."'";
								mysql_query($sql, $link) or die(mysql_error());
						}
					}
					// 新增學生的最新消息
					foreach($studentID as $value){
						$sql = "INSERT INTO `news`( `u_id`,
													`type`,
													`title`,
													`page_url`)
											VALUES ('".$value."',
													'2',
													'".$pname."：喔歐...老師已經審核完".$stage."階段，可是好像有點不滿意，趕快去看看老師給了什麼建議吧？！',
													'/co.in/science/student/nav_project.php')";
						mysql_query($sql, $link) or die(mysql_error());
					}
				}
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'schedule_update':	// -----------------------小組時間規劃--------------------
			$type = $_POST["type"];
			
			if($type == "get_schedule"){ 							// 抓取小組規劃
				$p_id = $_POST['p_id'];

				$sql = "SELECT * FROM `project_schedule` WHERE `p_id` = '".$p_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				while($row = mysql_fetch_array($qry)){
				 	$arr[] = array(	"p_id"		=> 	$row['p_id'],
				 					"starttime" =>	$row['starttime'],
									"endtime"	=>	$row['endtime'],
									"hint"		=>	$row['hint'],
									"exp1-1"	=>	$row['exp1-1'],
									"exp1-2"	=>	$row['exp1-2'],
									"exp2-1"	=>	$row['exp2-1'],
									"exp2-2"	=>	$row['exp2-2'],
									"exp2-3"	=>	$row['exp2-3'],
									"exp2-4"	=>	$row['exp2-4'],
									"exp3-1"	=>	$row['exp3-1'],
									"exp3-2"	=>	$row['exp3-2'],
									"exp3-3"	=>	$row['exp3-3'],
									"exp4-1"	=>	$row['exp4-1'],
									"exp4-2"	=>	$row['exp4-2'],
									"exp5-1"	=>	$row['exp5-1'],
									"exp5-2"	=>	$row['exp5-2'],
									"exp5-3"	=>	$row['exp5-3'],
									"exp5-4"	=>	$row['exp5-4']);
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "new_schedule"){ 						// 新增小組規劃
				$p_id = $_POST['p_id'];
				$starttime = $_POST['starttime'];
				$endtime = $_POST['endtime'];
				$exp = $_POST['exp'];
				$hint = $_POST['hint'];

				$sql = "UPDATE `project_schedule` SET `p_id` = '".$p_id."',
													  `starttime` = '".$starttime."',
													  `endtime` = '".$exp[14]."',
													  `hint` = '".$hint."',
													  `exp1-1` = '".$exp[0]."',
													  `exp1-2` = '".$exp[1]."',
													  `exp2-1` = '".$exp[2]."',
													  `exp2-2` = '".$exp[3]."',
													  `exp2-3` = '".$exp[4]."',
													  `exp2-4` = '".$exp[5]."',
													  `exp3-1` = '".$exp[6]."',
													  `exp3-2` = '".$exp[7]."',
													  `exp3-3` = '".$exp[8]."',
													  `exp4-1` = '".$exp[9]."',
													  `exp4-2` = '".$exp[10]."',
													  `exp5-1` = '".$exp[11]."',
													  `exp5-2` = '".$exp[12]."',
													  `exp5-3` = '".$exp[13]."',
													  `exp5-4` = '".$exp[14]."' WHERE `p_id` = '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'score_update':	// -------------------------成果評量----------------------
			$type = $_POST["type"];
			
			if($type == "percent_group"){ 						// 小組百分比
				$p_id = $_POST["p_id"];
				$process_grade = $_POST["process_grade"];
				$complete_grade = $_POST["complete_grade"];
				// 更新小組百分比---------------------------------------------------------
				$sql = "UPDATE `score_group` SET `per_process` = '".$process_grade."', `per_report` = '".$complete_grade."' WHERE `p_id` = '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "score_group"){ 					// 評比小組分數
				$p_id = $_POST["p_id"];
				$process_grade = $_POST["process_grade"];
				$complete_grade = $_POST["complete_grade"];
				// 更新小組分數---------------------------------------------------------
				$sql = "UPDATE `score_group` SET `process_score` = '".$process_grade."', `report_score` = '".$complete_grade."' WHERE `p_id` = '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_personal"){ 				// 讀取個人分數
				$p_id = $_POST["p_id"];
				$u_id = $_POST["u_id"];
				// 讀取個人日誌
				$sql = "SELECT * FROM `diary` WHERE `p_id`= '".$p_id."' AND `u_id`= '".$u_id."' AND `type`= '0'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "diary_id"			=> $row['d_id'],
										"diary_date"		=> $row['date'],
										"diary_stage"		=> $row['stage']);
					}
				}
				// 讀取個人成績
				$sql = "SELECT * FROM `score_personal` WHERE `p_id`= '".$p_id."' AND `s_id`= '".$u_id."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$arr[] = array( "score_id"			=> $row['s_p_id'],
										"score_score1"		=> $row['score_1'],
										"score_score2"		=> $row['score_2'],
										"score_score3"		=> $row['score_3'],
										"score_score4"		=> $row['score_4'],
										"score_score5"		=> $row['score_5'],
										"score_final"		=> $row['final_score']);
					}
				}
			}else if($type == "score_personal"){ 				// 評比個人分數
				$p_id = $_POST["p_id"];
				$s_id = $_POST["s_id"];

				$stageI_grade = $_POST["stageI_grade"];
				$stageII_grade = $_POST["stageII_grade"];
				$stageIII_grade = $_POST["stageIII_grade"];
				$stageIV_grade = $_POST["stageIV_grade"];
				$stageV_grade = $_POST["stageV_grade"];
				$stageVI_grade = $_POST["stageVI_grade"];
				// 更新個人分數---------------------------------------------------------
				$sql = "UPDATE `score_personal` SET `score_1` = '".$stageI_grade."', `score_2` = '".$stageII_grade."', `score_3` = '".$stageIII_grade."', `score_4` = '".$stageIV_grade."', `score_5` = '".$stageV_grade."', `final_score` = '".$stageVI_grade."' WHERE `p_id` = '".$p_id."' AND `s_id` = '".$s_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "total_grade"){
				$p_id = $_POST["p_id"];
				$group_grade = $_POST["group_grade"];
				$personal_grade = $_POST["personal_grade"];
				// 更新總%設定-----------------------------------------------------------
				$sql = "UPDATE `score_group` SET `per_group` = '".$group_grade."', `per_personal` = '".$personal_grade."' WHERE `p_id` = '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'help_update':		// -------------------------建議回報----------------------
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
			if($type == "add_help"){ 							// 新增廣播
				$help_group = $_POST["help_group"];
				$help_type = $_POST["help_type"];
				$help_title = $_POST["help_title"];
				$help_description = $_POST["help_description"];
				// 新增老師廣播---------------------------------------------------------------
				if($check_file_exist == 0){				//判斷是否有附加檔案
					$sql = "INSERT INTO `help`( `p_id`,
												`u_id`,
												`t_u_id`,
												`objects`,
												`type`,
												`title`,
												`description`,
												`reply`)
										VALUES( '".$help_group."',
												'".$_SESSION['UID']."',
												'".$help_group."',
												'0',
												'".$help_type."',
												'".$help_title."',
												'".$help_description."',
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
										VALUES( '".$help_group."',
												'".$_SESSION['UID']."',
												'".$help_group."',
												'0',
												'".$help_type."',
												'".$help_title."',
												'".$help_description."',
												'".$save_name."',
												'/co.in/science/model/document/help/".$save_name."',
												'1')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_help"){ 					// 讀取Q&A
				$help_id = $_POST['help_id'];					// 求助ID
				// 讀取建議回報-----------------------------------------------------------
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
							$type = "發生錯誤";
						}else if($row['type'] == "2"){
							$type = "操作問題";
						}else if($row['type'] == "3"){
							$type = "系統使用";
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
			}else if($type == "reply_help"){ 					// 回覆Q&A
				$help_id = $_POST["help_id"];					// 問題ID
				$help_reply = $_POST["help_reply"];
				
				if($check_file_exist == 0){				//判斷是否有附加檔案
					$sql = "INSERT INTO `help_reply`(`h_id`,
													 `u_id`,
													 `content`)
											 VALUES('".$help_id."',
													'".$_SESSION['UID']."',
													'".$help_reply."')";
				}else{
					$sql = "INSERT INTO `help_reply`(`h_id`,
													 `u_id`,
													 `content`,
													 `filename`,
													 `fileurl`)
											 VALUES('".$help_id."',
													'".$_SESSION['UID']."',
													'".$help_reply."',
													'".$save_name."',
													'/co.in/science/model/document/help/".$save_name."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 已回覆Q&A-----------------------------------------------------------
				$sql = "UPDATE `help` SET `reply` = '0' WHERE `h_id` = '".$help_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "read_suggest"){ 					// 讀取回報
				$help_id = $_POST['help_id'];					// 求助ID
				// 讀取建議回報-----------------------------------------------------------
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
							$type = "發生錯誤";
						}else if($row['type'] == "2"){
							$type = "操作問題";
						}else if($row['type'] == "3"){
							$type = "系統使用";
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
			}else if($type == "add_suggest"){ 					// 新增回報
				$suggest_type = $_POST["suggest_type"];
				$suggest_title = $_POST["suggest_title"];
				$suggest_description = $_POST["suggest_description"];
				// 新增建議回報---------------------------------------------------------------
				if($check_file_exist == 0){						// 判斷是否有附加檔案
					$sql = "INSERT INTO `help`( `p_id`,
												`u_id`,
												`t_u_id`,
												`objects`,
												`type`,
												`title`,
												`description`,
												`reply`)
										VALUES( '1',
												'".$_SESSION['UID']."',
												'1',
												'2',
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
										VALUES( '1',
												'".$_SESSION['UID']."',
												'1',
												'2',
												'".$suggest_type."',
												'".$suggest_title."',
												'".$suggest_description."',
												'".$save_name."',
												'/co.in/science/model/document/help/".$save_name."',
												'1')";
				}
				mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "reply_suggest"){ 				// 回覆回報
				$help_id = $_POST["help_id"];					// 問題ID
				$suggest_reply = $_POST["suggest_reply"];
				
				if($check_file_exist == 0){				//判斷是否有附加檔案
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
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'discuss_update':	// -----------------------參與學生討論--------------------
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
			if($type == "add_discuss"){ 						// 新增討論串
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_title = $_POST['discuss_title'];					// 討論主題
				$discuss_stage = $_POST['discuss_stage'];					// 討論階段
				$discuss_type = $_POST['discuss_type'];						// 討論類型
				$discuss_description = $_POST['discuss_description'];		// 討論說明
				
				if($check_file_exist == 0){				//判斷是否有附加檔案
					$sql = "INSERT INTO `discussion`(`p_id`,
													 `u_id`,
													 `type`,
													 `stage`,
													 `title`,
													 `description`)
											 VALUES('".$p_id."',
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
											 VALUES('".$p_id."',
													'".$_SESSION['UID']."',
													'".$discuss_type."',
													'".$discuss_stage."',
													'".$discuss_title."',
													'".$discuss_description."',
													'".$save_name."',
													'/co.in/science/model/document/discussion/".$save_name."')";
				}
					mysql_query($sql, $link) or die(mysql_error());
				// 抓取討論ID-----------------------------------------------------------
				$d_sql = "SELECT `d_id` FROM `discussion` WHERE `p_id` = '".$p_id."' AND `stage` = '".$discuss_stage."' AND `title` = '".$discuss_title."' AND `description` = '".$discuss_description."'";
				$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
				$d_row = mysql_fetch_array($d_qry);
					$discussionID = $d_row["d_id"];
				// 抓取老師ID----------------------------------------------------------
				$t_sql = "SELECT `t_m_id` FROM `project` WHERE `p_id` = '".$p_id."'";
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
				$s_sql = "SELECT `s_id` FROM `project_group` WHERE `p_id` = '".$p_id."'";
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
				// 讀取討論串-----------------------------------------------------------
				$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$p_id."' AND `stage` = '".$discuss_stage."' AND `title` = '".$discuss_title."' AND `description` = '".$discuss_description."'";
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
						// 討論區域
						if($row["stage"] == '1-1'||$row["stage"] == '1-2'){
							$area = '1';
						}else if($row["stage"] == '2-1'||$row["stage"] == '2-2'||$row["stage"] == '2-3'||$row["stage"] == '2-4'){
							$area = '2';
						}else if($row["stage"] == '3-1'||$row["stage"] == '3-2'||$row["stage"] == '3-3'){
							$area = '3';
						}else if($row["stage"] == '4-1'||$row["stage"] == '4-2'){
							$area = '4';
						}else if($row["stage"] == '5-1'||$row["stage"] == '5-2'||$row["stage"] == '5-3'||$row["stage"] == '5-4'){
							$area = '5';
						}
						$arr[] = array( "discuss_id"			=> $row["d_id"],
										"discuss_pid"			=> $row["p_id"],
										"discuss_user"			=> $name,
										"discuss_type"			=> $type,
										"discuss_area" 			=> $area,
										"discuss_stage" 		=> $row["stage"],
										"discuss_title"			=> $row["title"],
										"discuss_description" 	=> $row["description"],
										"discuss_filename"	 	=> $row["filename"],
										"discuss_fileurl"	 	=> $row["fileurl"],
										"discuss_time"			=> date("Y-m-d",strtotime($row["discussion_time"])));
					}
				}
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "reply_discuss"){ 				// 讀取討論區
				$p_id = $_POST['p_id'];										// 專題小組
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
				$sql = "UPDATE `project_perform` SET `times_discuss` = '".date('Y-m-d', strtotime('NOW'))."' WHERE `p_id` = '".$p_id."'";
					mysql_query($sql, $link) or die(mysql_error());
			}else if($type == "get_discuss"){ 					// 讀取討論區
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_id = $_POST["discuss_id"];
				// 讀取討論區--------------------------------------------------------
				$sql = "SELECT * FROM `discussion` WHERE `p_id` = '".$p_id."' AND `d_id` = '".$discuss_id."'";
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
						$arr[] = array( "discuss_id"			=> $row["d_id"],
										"discuss_pid"			=> $row["p_id"],
										"discuss_user"			=> $name,
										"discuss_title"			=> $row["title"],
										"discuss_description"	=> $row["description"],
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
				$p_id = $_POST['p_id'];										// 專題小組
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
				$p_id = $_POST['p_id'];										// 專題小組
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
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_id = $_POST["discuss_id"];
				// 對討論星號註記------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `star` = '1' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
				mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
					$arr[] = array( "discuss_id"			=> $discuss_id);
			}else if($type == "unstar_discuss"){ 				// 取消星號註記討論
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_id = $_POST["discuss_id"];
				// 取消對討論星號註記----------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `star` = '0' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
				mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
					$arr[] = array( "discuss_id"			=> $discuss_id);
			}else if($type == "bookmark_discuss"){ 				// 做書籤討論
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_id = $_POST["discuss_id"];
				// 對討論星號註記------------------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `bookmark` = '1' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
				mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
					$arr[] = array( "discuss_id"			=> $discuss_id);
			}else if($type == "unbookmark_discuss"){ 			// 取消書籤討論
				$p_id = $_POST['p_id'];										// 專題小組
				$discuss_id = $_POST["discuss_id"];
				// 取消對討論星號註記----------------------------------------------------
				$sql = "UPDATE `discussion_active` SET `bookmark` = '0' WHERE `u_id`= '".$_SESSION['UID']."' AND `d_id` = '".$discuss_id."'";
				mysql_query($sql, $link) or die(mysql_error());
				// 讀取討論活動-----------------------------------------------------------
					$arr[] = array( "discuss_id"			=> $discuss_id);
			}
			if(isset($arr)){
				exit(str_replace('\/','/', json_encode($arr)));
			}else{
				exit('{"Error":"Error"}');
			}
			break;
		case 'qna_update':	// ----------------------------提問單活動---------------------
			$type = $_POST["type"];

			if($type == "add_qna"){ 							// 新增提問單
				$p_id = $_POST["p_id"];
				$qnalist = "";
				$order = 1;
				
				if(isset($_POST["qnalist"])){					// 如無值則不傳遞！
					$qnalist = $_POST["qnalist"];
				}
				// 新增提問單-------------------------------------------------------------
				if($qnalist != ""){
					foreach($qnalist as $value){
						$sql = "INSERT INTO `research_qna`( `p_id`,
															`order`,
															`question`)
													 VALUES ('".$p_id."',
															 '".$order."',
															 '".$value."')";
							mysql_query($sql, $link) or die(mysql_error());
						$order++;
					}
				}
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
											'T',
											'".$log."')";
					mysql_query($sql, $link) or die(mysql_error());
			}
			break;
		default:
			exit ('error');
			break;
	}
?>