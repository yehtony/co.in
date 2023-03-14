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
			<li class="log_btn" id="work" value="小組工作室" data-step='8' data-intro='小組工作室：進行專題探究任務，小組共同協作空間。');>小組工作室</li>
			<li class="log_btn" id="database" style="background-color: #59B791;" value="小組資料庫" data-step='9' data-intro='小組資料庫：將蒐集來的資料，皆存放於此。'>小組資料庫</li>
		</ul>
		<div id="page_database" class="function_pages">
			<ul class="database_types">
				<li id="database1">網站</li>
				<li id="database2">雜誌</li>
				<li id="database3">書籍</li>
				<li id="database4">圖片</li>
				<li id="database5">其他</li>
			</ul>
			<div class="database_new log_btn" value="新增資料庫" data-step='10' data-intro='新增資料庫：按此新增資料庫！' data-position='left'><img src="../../model/images/project_add.png" width="30px" /></div>
			<div id="page_database1" class="database_pages">
				<table class="database_table1" width="100%">
					<tr>
						<th width="33%">[文章標題]</th>
						<th width="16%">[關鍵字]</th>
						<th width="33%">[網站名稱]</th>
						<th width="17%">[收藏日期]</th>
					</tr>
					<?php
						$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION["p_id"]."' && `category` = '1' ORDER BY `database_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						while($row = mysql_fetch_array($qry)){
							// 抓取關聯式關鍵字
							$kwd_name = "";
							
							$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
							$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
							while($k_row = mysql_fetch_array($k_qry)){
								$kwd_name .= $k_row['name']."<br />";
							}

							echo "<tr id='".$row['d_id']."'>
									<td>".$row['title']."</td>
									<td>".$kwd_name."</td>
									<td>".$row['src_name']."</td>
									<td align='center'>".date("Y-m-d",strtotime($row["database_time"]))."</td>
								</tr>";
						}
						mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
			</div>
			<div id="page_database2" class="database_pages">
				<table class="database_table2" width="100%">
					<tr>
						<th width="33%">[文章標題]</th>
						<th width="16%">[關鍵字]</th>
						<th width="33%">[雜誌名稱]</th>
						<th width="17%">[收藏日期]</th>
					</tr>
					<?php
						$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION["p_id"]."' && `category` = '2' ORDER BY `database_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						while($row = mysql_fetch_array($qry)){
							// 抓取關聯式關鍵字
							$kwd_name = "";

							$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
							$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
							while($k_row = mysql_fetch_array($k_qry)){
								$kwd_name .= $k_row['name']."<br />";
							}

							echo "<tr id='".$row['d_id']."'>
									<td>".$row['title']."</td>
									<td>".$kwd_name."</td>
									<td>".$row['src_name']."</td>
									<td align='center'>".date("Y-m-d",strtotime($row["database_time"]))."</td>
								</tr>";
						}
						mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
			</div>
			<div id="page_database3" class="database_pages">
				<table class="database_table3" width="100%">
					<tr>
						<th width="33%">[書籍名稱]</th>
						<th width="16%">[關鍵字]</th>
						<th width="33%">[作者]</th>
						<th width="17%">[收藏日期]</th>
					</tr>
					<?php
						$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION["p_id"]."' && `category` = '3' ORDER BY `database_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						while($row = mysql_fetch_array($qry)){
							// 抓取關聯式關鍵字
							$kwd_name = "";

							$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
							$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
							while($k_row = mysql_fetch_array($k_qry)){
								$kwd_name .= $k_row['name']."<br />";
							}

							echo "<tr id='".$row['d_id']."'>
									<td>".$row['title']."</td>
									<td>".$kwd_name."</td>
									<td>".$row['src_name']."</td>
									<td align='center'>".date("Y-m-d",strtotime($row["database_time"]))."</td>
								</tr>";
						}
						mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
			</div>
			<div id="page_database4" class="database_pages">
				<?php
					$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION["p_id"]."' && `category` = '4' ORDER BY `database_time` DESC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					while($row = mysql_fetch_array($qry)){
						// 抓取關聯式關鍵字
						$kwd_name = "";

						$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
						$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
						while($k_row = mysql_fetch_array($k_qry)){
							$kwd_name .= $k_row['name']." ";
						}

						echo "<figure class='database_figure' id='".$row['d_id']."'>
								<img src=".$row['fileurl']." alt='".$row['title']."(圖)' width='90%' height='70px'>
								<figcaption>".$row['title']."</figcaption>
								<span>".$kwd_name."</span><br />
								<time>".date("Y-m-d",strtotime($row["database_time"]))."</time>
							  </figure>";
					}
					mysql_query($sql, $link) or die(mysql_error());
				?>
			</div>
			<div id="page_database5" class="database_pages">
				<table class="database_table3" width="100%">
					<tr>
						<th width="33%">[檔案名稱]</th>
						<th width="16%">[關鍵字]</th>
						<th width="33%">[檔案出處]</th>
						<th width="17%">[收藏日期]</th>
					</tr>
					<?php
						$sql = "SELECT * FROM `database` WHERE `p_id` = '".$_SESSION["p_id"]."' && `category` = '5' ORDER BY `database_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						while($row = mysql_fetch_array($qry)){
							// 抓取關聯式關鍵字
							$kwd_name = "";
							
							$k_sql = "SELECT * FROM  `database_cnt` LEFT OUTER JOIN `database_kwd`
									ON `database_cnt`.`k_id` = `database_kwd`.`k_id`
									WHERE `database_cnt`.`d_id` = '".$row['d_id']."' AND `database_kwd`.`p_id` = '".$_SESSION["p_id"]."'";
							$k_qry = mysql_query($k_sql, $link) or die(mysql_error());
							while($k_row = mysql_fetch_array($k_qry)){
								$kwd_name .= $k_row['name']."<br />";
							}

							echo "<tr id='".$row['d_id']."'>
									<td>".$row['title']."</td>
									<td>".$kwd_name."</td>
									<td>".$row['src_name']."</td>
									<td align='center'>".date("Y-m-d",strtotime($row["database_time"]))."</td>
								</tr>";
						}
						mysql_query($sql, $link) or die(mysql_error());
					?>
				</table>
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
			<input type='button' class='fancybox_btn' id='research_guide_submit' value='我知道了'>
		</div>
	</div>
	<div class="fancybox_box" id="database_new_fancybox">
		<div class="fancybox_area" id="database_new_area">
			<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
			<h2>- 新增資料庫 -</h2>
			<form id="database_new_form" method="post">
				<p>
					<label>資源類型：</label>
					<select name="database_new_types">
						<option value="default">請選擇資料類型...</option>
						<option value="1">網站</option>
						<option value="2">雜誌</option>
						<option value="3">書籍</option>
						<option value="4">圖片</option>
						<option value="5">其他</option>
					</select>
				</p>
				<div class="database_area" id="database_area1">
					<p>
						<label>文章標題：</label>
						<input type="text" name="database_new_title1">
					</p>
					<p>
						<label>關鍵字：</label>
						<select class="database_new_kwd" id="database_new_kwd1">
							<option value="default">請選擇關鍵字</option>
							<option value='new' style="background-color: #eee;">+ 新增關鍵字</option>
							<?php
								$sql = "SELECT `k_id`, `name` FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['k_id']."'>".$row['name']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</select>
						<input type="button" class="kwd_add" id="kwd_add1" value="加入"><br />
						<span class="kwd_new_area" style="display: none;">
							<input type="text" class="kwd_name" id="kwd_name1" size="20" style="margin-left: 76px;">
							<input type="button" class="kwd_new" value="新增">
						</span>
						<div class="kwd_block" id="kwd_block1"></div>
					</p>
					<p>
						<label>網誌名稱：</label>
						<input type="text" name="database_new_name1" size="25">
					</p>
					<p>
						<label>網誌位址：</label>
						<input type="text" name="database_new_src1" size="25" placeholder="https://www.google.com.tw/">
					</p>
					<p>
						<label>描述：</label>
						<textarea name="database_new_description1"></textarea>
					</p>
				</div>
				<div class="database_area" id="database_area2">
					<p>
						<label>文章標題：</label>
						<input type="text" name="database_new_title2">
					</p>
					<p>
						<label>關鍵字：</label>
						<select class="database_new_kwd" id="database_new_kwd2">
							<option value="default">請選擇關鍵字</option>
							<option value='new' style="background-color: #eee;">+ 新增關鍵字</option>
							<?php
								$sql = "SELECT `k_id`, `name` FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['k_id']."'>".$row['name']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</select>
						<input type="button" class="kwd_add" id="kwd_add2" value="加入"><br />
						<span class="kwd_new_area" style="display: none;">
							<input type="text" class="kwd_name" id="kwd_name2" size="20" style="margin-left: 76px;">
							<input type="button" class="kwd_new" value="新增">
						</span>
						<div class="kwd_block" id="kwd_block2"></div>
					</p>
					<p>
						<label>雜誌名稱：</label>
						<input type="text" name="database_new_name2" size="25">
					</p>
					<p>
						<label>作者：</label>
						<input type="text" name="database_new_authors2" size="25">
					</p>
					<p>
						<label>頁數：</label>
						<input type="number" name="database_new_fpage2" min="1" max="9999"> ~ 
						<input type="number" name="database_new_epage2" min="1" max="9999">
					</p>
					<p>
						<label>出版日期：</label>
						<input type="date" name="database_new_pdate2" size="25">
					</p>
					<p>
						<label>描述：</label>
						<textarea name="database_new_description2"></textarea>
					</p>
				</div>
				<div class="database_area" id="database_area3">
					<p>
						<label>書籍標題：</label>
						<input type="text" name="database_new_title3">
					</p>
					<p>
						<label>章節名稱：</label>
						<input type="text" name="database_new_name3">
						<span style="font-size: 12px;">（如果無章節，請填書籍名稱。）</span>
					</p>
					<p>
						<label>關鍵字：</label>
						<select class="database_new_kwd" id="database_new_kwd3">
							<option value="default">請選擇關鍵字</option>
							<option value='new' style="background-color: #eee;">+ 新增關鍵字</option>
							<?php
								$sql = "SELECT `k_id`, `name` FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['k_id']."'>".$row['name']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</select>
						<input type="button" class="kwd_add" id="kwd_add3" value="加入"><br />
						<span class="kwd_new_area" style="display: none;">
							<input type="text" class="kwd_name" id="kwd_name3" size="20" style="margin-left: 76px;">
							<input type="button" class="kwd_new" value="新增">
						</span>
						<div class="kwd_block" id="kwd_block3"></div>
					</p>
					<p>
						<label>作者：</label>
						<input type="text" name="database_new_authors3" size="25">
					</p>
					<p>
						<label>頁數：</label>
						<input type="number" name="database_new_fpage3" min="1" max="9999"> ~ 
						<input type="number" name="database_new_epage3" min="1" max="9999">
					</p>
					<p>
						<label>出版社：</label>
						<input type="text" name="database_new_publisher3" size="25">
					</p>
					<p>
						<label>出版日期：</label>
						<input type="date" name="database_new_pdate3" size="25" >
					</p>
					<p>
						<label>描述：</label>
						<textarea name="database_new_description3"></textarea>
					</p>
				</div>
				<div class="database_area" id="database_area4">
					<p>
						<label>圖片名稱：</label>
						<input type="text" name="database_new_title4">
					</p>
					<p>
						<label>圖片出處：</label>
						<input type="text" name="database_new_name4">
					</p>
					<p>
						<label>關鍵字：</label>
						<select class="database_new_kwd" id="database_new_kwd4">
							<option value="default">請選擇關鍵字</option>
							<option value='new' style="background-color: #eee;">+ 新增關鍵字</option>
							<?php
								$sql = "SELECT `k_id`, `name` FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['k_id']."'>".$row['name']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>
						</select>
						<input type="button" class="kwd_add" id="kwd_add4" value="加入"><br />
						<span class="kwd_new_area" style="display: none;">
							<input type="text" class="kwd_name" id="kwd_name4" size="20" style="margin-left: 76px;">
							<input type="button" class="kwd_new" value="新增">
						</span>
						<div class="kwd_block" id="kwd_block4"></div>
					</p>
					<p>
						<label>檔案上傳：</label>
						<input type="file" name="files">
					</p>
					<p>
						<label>描述：</label>
						<textarea name="database_new_description4"></textarea>
					</p>
				</div>
				<div class="database_area" id="database_area5">
					<p>
						<label>檔案名稱：</label>
						<input type="text" name="database_new_title5">
					</p>
					<p>
						<label>檔案出處：</label>
						<input type="text" name="database_new_name5">
					</p>
					<p>
						<label>關鍵字：</label>
						<select class="database_new_kwd" id="database_new_kwd5">
							<option value="default">請選擇關鍵字</option>
							<option value='new' style="background-color: #eee;">+ 新增關鍵字</option>
							<?php
								$sql = "SELECT `k_id`, `name` FROM `database_kwd` WHERE `p_id` = '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['k_id']."'>".$row['name']."</option>";
									}
								}
								mysql_query($sql, $link) or die(mysql_error());
							?>	
						</select>
						<input type="button" class="kwd_add" id="kwd_add5" value="加入"><br />
						<span class="kwd_new_area" style="display: none;">
							<input type="text" class="kwd_name" id="kwd_name5" size="20" style="margin-left: 76px;">
							<input type="button" class="kwd_new" value="新增">
						</span>
						<div class="kwd_block" id="kwd_block5"></div>
					</p>
					<p>
						<label>檔案上傳：</label>
						<input type="file" name="files">
					</p>
					<p>
						<label>描述：</label>
						<textarea name="database_new_description5"></textarea>
					</p>
				</div>
				<input type="button" class="fancybox_btn" id="database_add" value="新增資料">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="database_read_fancybox">
		<div class="fancybox_area" id="database_read_area">
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