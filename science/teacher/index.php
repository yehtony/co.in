<?php
	$page_url = '專題指導';
	include("api/php/header.php");
?>
<div id="centercolumn">
	<div id="leftcolumn">
		<fieldset id="info_field" data-step="7" data-intro="教師廣播室：傳送廣播給專題小組們。" data-position='right'>
			<legend>教師廣播室</legend>
			<button class="help_btn log_btn" value="發送廣播"><img src="/co.in/science/model/images/project_help.png" width="20px;" style="vertical-align: bottom;">發送廣播</button>
			<table border="1" width="100%">
				<tr>
					<th width="11%">廣播小組</th>
					<th width="11%">類型</th>
					<th width="45%">廣播內容</th>
					<th width="15%">日期</th>
				</tr>
				<?php
					$sql = "SELECT * FROM `help` WHERE `u_id` = '".$_SESSION['UID']."' && `objects` = '0' ORDER BY `help_time` ASC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取小組名稱----------------------------------------------------------------
							$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['t_u_id']."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$pname = $p_row['pname'];
							// 廣播類型--------------------------------------------------------------------
							if($row['type'] == "0"){
								$help_type = "其他";
							}else if($row['type'] == "1"){
								$help_type = "開會通知";
							}else if($row['type'] == "2"){
								$help_type = "作業催繳";
							}else if($row['type'] == "3"){
								$help_type = "緊急事項";
							}
							echo "<tr id='info_table'>".
									"<td>".$pname."</td>".
								  	"<td>".$help_type."</td>".
								  	"<td>".$row['description']."</td>".
								  	"<td>".date('Y-m-d', strtotime($row['help_time']))."</td>".
								  "</tr>";
						}
					}else{
						echo "<tr><td colspan='4'>尚未有任何廣播。</td></tr>";
					}
				?>
			</table>
		</fieldset>
		<fieldset id="info_field" data-step="8" data-intro="學生Q&A：學生提出問題，老師回覆求助答案。" data-position='right'>
			<legend>回答學生問題</legend>
			<table border="1" width="100%">
				<tr>
					<th width="15%">日期</th>
					<th width="11%">小組</th>
					<th width="11%">類型</th>
					<th width="38%">發問內容</th>
					<th width="7%"></th>
				</tr>
				<?php
					$sql = "SELECT * FROM `help` WHERE `t_u_id` = '".$_SESSION['UID']."' && `objects` = '1' && `reply` = '1' ORDER BY `help_time` ASC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取發問學生
							$u_sql = "SELECT `name` FROM `userinfo` WHERE `u_id` = '".$row["u_id"]."' limit 0, 1";
							$u_qry = mysql_query($u_sql, $link) or die(mysql_error());
							$u_row = mysql_fetch_array($u_qry);
								$name = $u_row['name'];
							// 抓取發問學生所在小組
							$g_sql = "SELECT `p_id` FROM `project_group` WHERE `s_id` = '".$row["u_id"]."' limit 0, 1";
							$g_qry = mysql_query($g_sql, $link) or die(mysql_error());
							$g_row = mysql_fetch_array($g_qry);
								$p_id = $g_row['p_id'];
							// 抓取小組名稱----------------------------------------------------------------
							$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$p_id."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$pname = $p_row['pname'];
							// 求助類型--------------------------------------------------------------------
							if($row['type'] == "0"){
								$help_type = "其他";
							}else if($row['type'] == "1"){
								$help_type = "器材問題";
							}else if($row['type'] == "2"){
								$help_type = "如何進行";
							}else if($row['type'] == "3"){
								$help_type = "小組溝通";
							}

							echo "<tr id='".$row["h_id"]."'>".
									"<td>".date('Y-m-d', strtotime($row['help_time']))."</td>".
									"<td>".$pname."</td>".
								  	"<td>".$help_type."</td>".
								  	"<td>".$row['description']."</td>".
								  	"<td><button class='help_reply log_btn' value='回覆學生問題'>回覆</button></td>".
								  "</tr>";
						}
					}else{
						echo "<tr><td colspan='5'>尚未有任何求助內容。</td></tr>";
					}
				?>
			</table>
		</fieldset>
	</div>
	<div id="rightcolumn">
		<fieldset id="info_field" data-step="9" data-intro="任務審核：專題任務通過後，將會發出審核通知。" data-position='left'>
			<legend>小組任務審核</legend>
			<table border="1" width="100%">
				<tr>
					<th width="18%">日期</th>
					<th width="14%">專題小組</th>
					<th width="35%">任務階段</th>
					<th width="13%">任務詳情</th>
					<th width="20%">狀態</th>
				</tr>
				<?php
					$sql = "SELECT * FROM `project_examine` WHERE `t_id` = '".$_SESSION['UID']."' && `result` = '1' ORDER BY `examine_start_time` ASC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							// 抓取小組名稱
							$p_sql = "SELECT `pname` FROM `project` WHERE `p_id` = '".$row['p_id']."'";
							$p_qry = mysql_query($p_sql, $link) or die(mysql_error());
							$p_row = mysql_fetch_array($p_qry);
								$pname = $p_row['pname'];
							// 抓取階段內容
							$s_sql = "SELECT `stage`, `name` FROM `stage` WHERE `stage`= '".$row['stage']."'";
							$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
							if(mysql_num_rows($s_qry) > 0){
								$s_row = mysql_fetch_array($s_qry);
									$stage = $s_row['stage'];
									$sname = $s_row['name'];
							}else{
								$stage = $row['stage'];
								$sname = '進行嘗試性研究';
							}
							// 抓取研究問題(第一個)
							$q_sql = "SELECT `q_id` FROM `research_question` WHERE `p_id` = '".$row['p_id']."' ORDER BY `q_id` ASC limit 0, 1";
							$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
							$q_row = mysql_fetch_array($q_qry);
								$question = $q_row['q_id'];
							// 檢查是否為十四個階段裡的其中之一
							$c_sql = "SELECT * FROM `stage` WHERE `stage` = '".$row['stage']."'";
							$c_qry = mysql_query($c_sql, $link) or die(mysql_error());
							if(mysql_num_rows($c_qry) > 0){		// 確定有值
								if($row['stage'] == '5-3'){
									$q_sql = "SELECT `q_id` FROM `research_qna` WHERE `p_id` = '".$row['p_id']."'";
									$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
									if(mysql_num_rows($q_qry) == 0){		// 確定無值
										echo "<tr class='".$row['stage']."' id='".$row['p_id']."'>".
												"<td>".date('Y-m-d', strtotime($row['examine_start_time']))."</td>".
												"<td>".$pname."</td>".
												"<td>".$stage." ".$sname."</td>".
												"<td align='center' class='log_btn' value='詳情【".$stage." ".$sname."】'><span class='examine_detail' value='".$question."'>詳情</span></td>".
												"<td align='center' class='log_btn' value='撰寫提問單'>
													<button class='examine_qna'>撰寫提問單</button>
												</td>".
											  "</tr>";
									}else{
										echo "<tr class='".$row['stage']."' id='".$row['p_id']."'>".
											"<td>".date('Y-m-d', strtotime($row['examine_start_time']))."</td>".
											"<td>".$pname."</td>".
											"<td>".$stage." ".$sname."</td>".
											"<td align='center' class='log_btn' value='詳情【".$stage." ".$sname."】'><span class='examine_detail' value='".$question."'>詳情</span></td>".
											"<td class='log_btn' value='審核【".$stage." ".$sname."】'>
												<button class='examine_pass' value='pass'>通過</button>
												<button class='examine_unpass' value='unpass'>不通過</button>
											</td>".
										  "</tr>";
									}
								}else{
									echo "<tr class='".$row['stage']."' id='".$row['p_id']."'>".
											"<td>".date('Y-m-d', strtotime($row['examine_start_time']))."</td>".
											"<td>".$pname."</td>".
											"<td>".$stage." ".$sname."</td>".
											"<td align='center' class='log_btn' value='詳情【".$stage." ".$sname."】'><span class='examine_detail' value='".$question."'>詳情</span></td>".
											"<td class='log_btn' value='審核【".$stage." ".$sname."】'>
												<button class='examine_pass' value='pass'>通過</button>
												<button class='examine_unpass' value='unpass'>不通過</button>
											</td>".
										  "</tr>";
								}
							}else{
								echo "<tr class='".$row['stage']."' id='".$row['p_id']."'>".
										"<td>".date('Y-m-d', strtotime($row['examine_start_time']))."</td>".
										"<td>".$pname."</td>".
										"<td>".$stage." ".$sname."</td>".
										"<td align='center' class='log_btn' value='詳情【".$stage." ".$sname."】'><span class='examine_pilot' value='".$question."'>詳情</span></td>".
										"<td class='log_btn' value='審核【".$stage." ".$sname."】'>
											<button class='examine_pass' value='pass'>通過</button>
											<button class='examine_unpass' value='unpass'>不通過</button>
										</td>".
									  "</tr>";
							}
						}
					}else{
						echo "<tr><td colspan='5'>尚未有任何審核請求。</td></tr>";
					}
				?>
			</table>
			<span class='log_btn' id="examine_more" title="觀看歷史審核紀錄" value='歷史審核紀錄[more...]'>[more...]</span>
		</fieldset>
		<fieldset id="info_field" style="margin-top: 15px;" data-step="10" data-intro="其他：可開啟介面導覽、觀看學生階段任務介紹和系統特色介紹。" data-position='left'>
			<center>
				<p><b>更了解專題探究學習系統</b></p>
				<p class='log_btn' value="專題探究學習系統介面導覽"><a href="javascript:void(0);" onclick="javascript:introJs().start();">專題探究學習系統介面導覽</a></p>
				<p class='log_btn' value="學生階段任務介紹"><a href="tool_intro.php">學生階段任務介紹</a></p>
				<p class='log_btn' value="系統特色介紹"><a href="tool_feature.php">系統特色介紹</a></p>
			</center>
		</fieldset>
	</div>
	<div class="fancybox_box" id="help_new_fancybox">
		<div class="fancybox_area" id="help_new_area">
		<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<form id="help_new_form">
				<h2>- 老師廣播 -</h2>
				<p>小組：<select name="help_group">
							<option value="default">請選擇發送小組...</option>
							<?php
								$sql = "SELECT `p_id`, `pname` FROM `project` WHERE `t_m_id` = '".$_SESSION['UID']."' OR `t_s_id` = '".$_SESSION['UID']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<option value='".$row['p_id']."'>".$row['pname']."</option>";
									}
								}else{
									echo "<option value='NULL'>尚未有任何小組</option>";
								}
							?>
						 </select></p>
				<p>類型：<select name="help_type">
							<option value="default">請選擇求助類型...</option>
							<option value="1">開會通知</option>
							<option value="2">作業催繳</option>
							<option value="3">緊急事項</option>
							<option value="0">其他</option>
						 </select></p>
				<p>主題：<input type="text" name="help_title" /></p>
				<p>內容：<textarea name="help_description"></textarea></p>
				<!-- <p>附加檔案：<input type="file" name="files" /></p> -->
				<input type="button" class="fancybox_btn" id="help_add" value="送出">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="help_read_fancybox">
		<div class="fancybox_area" id="help_read_area">
		<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 求助問題 -</h2>
			<p><label>時間：</label><span id="help_date"></span></p>
			<p><label>求助人：</label><span id="help_people"></span></p>
			<p><label>類型：</label><span id="help_type"></span></p>
			<p><label>主題：</label><span id="help_title"></span></p>
			<p><label>內容：</label><span id="help_description"></span></p>
			<p style="padding-left: 60px;"><span id="help_filename"></span></p>
		<hr />
			<p id="help_reply">目前無任何回應</p>
			<p><span id="help_reply_content"></span></p>
		<hr />
			<form id="help_read_form">
				<p style="display: none;">問題：<input type="text" name="help_id"></p>
				<p><label>回覆：</label><textarea name="help_reply"></textarea></p>
				<p><label>附加檔案：</label><input type="file" name="files" /></p>
				<input type="button" class="fancybox_btn" id="help_submit" value="送出">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="examine_pass_fancybox">
		<div class="fancybox_area" id="examine_pass_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 審核評論 -</h2>
			<form id="examine_pass_form" method="post">
				<p style="display: none;">
					<label>小組ID：</label>
					<input type="text" name="examine_pass_project" />
				<p style="display: none;">
					<label>階段：</label>
					<input type="text" name="examine_pass_stage" value="<?php $stage; ?>"/>
				</p>
				<p style="display: none;">
					<label>通過：</label>
					<input type="text" name="examine_pass_pass" />
				</p>
				<p class="examine_pass_back" style="display: none;">
					<label>退回階段：</label>
					<select name="examine_pass_back">
						<option value="default">請選擇退回階段...</option>
						<?php
							$s_sql = "SELECT `s_id` FROM `stage` WHERE `stage` = '".$stage."'";
							$s_qry = mysql_query($s_sql, $link) or die(mysql_error());
							$s_row = mysql_fetch_array($s_qry);
								$s_id = $s_row['s_id'];

							$sql = "SELECT * FROM `stage` WHERE `s_id` <= '".$s_id."'";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['stage']."'>".$row['stage']." ".$row['name']."</option>";
								}
							}else{ // 嘗試性實驗
								echo "<option value='2-4'>2-4 進行嘗試性研究</option>";
							}
								mysql_query($sql, $link) or die(mysql_error());
						?>
					</select>
				</p>
				<p>
					<label>評語：</label>
					<textarea name="examine_pass_content"></textarea>
				</p>
				<input type="button" class="fancybox_btn" id="examine_pass_submit" value="送出">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="examine_idea_fancybox">
		<div class="fancybox_area" id="examine_idea_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看研究構想表 -</h2>
			<table class="research_idea_table">
				<tr>
					<th width="38%">研究問題</th>
					<th width="38%">研究假設</th>
					<th width="12%">操縱變因</th>
					<th width="12%">應變變因</th>
				</tr>
				<tr>
					<td><span class="question_name"></span></td>
					<td><span class="question_assume"></span></td>
					<td align="center"><span class="question_independent"></span></td>
					<td align="center"><span class="question_dependent"></span></td>
				</tr>
				<tr>
					<td colspan="4">實驗紀錄方式：<span class="question_record"></td>
				</tr>
				<tr>
					<th colspan="4">研究材料與工具</th>
				</tr>
				<tr>
					<td colspan="4"><span class="question_material"></span></td>
				</tr>
				<tr>
					<th colspan="4">研究實驗步驟</th>
				</tr>
				<tr>
					<td colspan="4"><span class="question_steps"></span></td>
				</tr>
			</table>
			<input type="button" class="fancybox_btn" id="examine_idea_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_experiment_fancybox">
		<div class="fancybox_area" id="examine_experiment_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗日誌 -</h2>
			<table>
				<tr>
					<th width="38%">研究問題</th>
					<th width="38%">研究假設</th>
					<th width="12%">操縱<br />變因</th>
					<th width="12%">應變<br />變因</th>
				</tr>
				<tr>
					<td><span class="experiment_read_question"></td>
					<td><span class="experiment_read_assume"></td>
					<td align="center"><span class="experiment_read_independent"></td>
					<td align="center"><span class="experiment_read_dependent"></td>
				</tr>
				<tr>
					<td colspan="4">
						<p>實驗日期：<span class="experiment_read_date"></p>
						<p>實驗結果：<span class="experiment_read_result"></p>
						<p>實驗紀錄表：<button><a target="_blank" class="experiment_read_fileurl" download='實驗紀錄表'>下載</a></button></p>
						<p>實驗描述：<span class="experiment_read_description"></p>
					</td>
				</tr>
			</table>
			<input type="button" class="fancybox_btn" id="examine_experiment_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_qna_fancybox">
		<div class="fancybox_area" id="examine_qna_area">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 撰寫教師提問單 -</h2>
			<button id="qna_add">新增</button>
			<button id="qna_remove">移除</button>
			<from id="qna_form">
				<input type="button" class="fancybox_btn" id="examine_qna_submit" value="提交">
				<div>專題：<input type="text" name="project_id"></div>
				<div id="qna_area1">
					<textarea name="qna_write1" placeholder="問題#1"></textarea>
				</div>
			</from>
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox1-1">
		<div class="fancybox_area" id="examine_detail_area1-1">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看研究主題與題目 -</h2>
			<p>
				<label>提議主題：</label><span class="theme_read_name"></span>
			</p>
			<p>
				<label>提議人：</label><span class="theme_read_user"></span>
			</p>
			<p>
				<label>主題來源：</label><span class="theme_read_src"></span>
			</p>
			<p>
				<label>提議的原因：</label><span class="theme_read_description"></span>
			</p>
			<p>
				<label>附加檔案：</label><span class="theme_read_filename"></span>
			</p>
			<p>
				<label>提議時間：</label><span class="theme_read_time"></span>
			</p>
		<hr />
			<p>
				<label>提議題目：</label><span class="topic_read_name"></span>
			</p>
			<p>
				<label>提議人：</label><span class="topic_read_user"></span>
			</p>
			<p>
				<label>相關資源：</label><span class="topic_read_data"></span>
			</p>
			<p>
				<label>提議的原因：</label><span class="topic_read_description"></span>
			</p>
			<p>
				<label>附加檔案：</label><span class="topic_read_filename"></span>
			</p>
			<p>
				<label>提議時間：</label><span class="topic_read_time"></span>
			</p>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 這個主題跟自然科學或數學有關？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 我們至少可以找到三個跟這個主題有關的資料？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 我們能輕易的獲得研究材料及設備嗎？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 進行相關的研究不會有安全的問題？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 這個主題的相關研究不會傷害小動物？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
				<tr>
					<td>6. 可以找到請教的人？</td>
					<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
				</tr>
				<tr>
					<td>7. 研究主題足夠有趣嗎？能不能在幾個月內都一直覺得有趣？</td>
					<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
				</tr>
				<tr>
					<td>8. 我們可以在限定的時間內完成這個主題的相關研究？</td>
					<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
				</tr>
				<tr>
					<td>9. 在全國科展近五年的得獎作品中沒有完全一樣的題目？</td>
					<td class="check_choose"><input type="radio" name="check_9" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_9" value="0" /></td>
				</tr>
				<tr>
					<td>10. 我們有沒有避免在階段指引中所列出的不好的科學題目呢？</td>
					<td class="check_choose"><input type="radio" name="check_10" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_10" value="0" /></td>
				</tr>
				<tr>
					<td>11. 這個題目對我們來說，不會太難？</td>
					<td class="check_choose"><input type="radio" name="check_11" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_11" value="0" /></td>
				</tr>
				<tr>
					<td>12. 我們可以取得或自製相關的研究器材？</td>
					<td class="check_choose"><input type="radio" name="check_12" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_12" value="0" /></td>
				</tr>
				<tr>
					<td>13. 研究材料容易取得？</td>
					<td class="check_choose"><input type="radio" name="check_13" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_13" value="0" /></td>
				</tr>
				<tr>
					<td>14. 要做的實驗是安全的？</td>
					<td class="check_choose"><input type="radio" name="check_14" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_14" value="0" /></td>
				</tr>
				<tr>
					<td>15. 我們可以找到測量或紀錄的方法？</td>
					<td class="check_choose"><input type="radio" name="check_15" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_15" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox2-1">
		<div class="fancybox_area" id="examine_detail_area2-1">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看研究問題 -</h2>
			<table class="question_read_table" width="100%;">
				<tr class="question_read_tr">
					<th width="38%">研究問題</th>
					<th width="38%">研究假設</th>
					<th width="12%">應變變因</th>
					<th width="12%">操縱變因</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1.「操縱變因」以及「應變變因」是否可以被量測？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 我們在實驗過程中，「操縱變因」的數值是可以被改變的嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 我們是否能夠鑑別所有相關的「應變變因」？它們的變化是因為「操縱變因」所引起的嗎？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 我們是否有找出所有相關的「控制變因」呢？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 所有的「控制變因」都可以在實驗過程中保持穩定不變嗎？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
				<tr>
					<td>6.「假設」是否基於你所找到的資訊？</td>
					<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
				</tr>
				<tr>
					<td>7.「假設」是否包含「操縱變因」和「應變變因」？</td>
					<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
				</tr>
				<tr>
					<td>8.「假設」是否撰寫成因果關係明確的形式？ 例如：「對植物施肥」（因）可以讓「植物長更高」（果）。</td>
					<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox2-2">
		<div class="fancybox_larrow"><img src="../model/images/project_larrow.png" width="20px"></div>
		<div class="fancybox_rarrow"><img src="../model/images/project_rarrow.png" width="20px"></div>
		<div class="fancybox_area" id="examine_detail_area2-2">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看研究構想表 -</h2>
			<table class="research_idea_table">
				<tr>
					<th width="38%">研究問題</th>
					<th width="38%">研究假設</th>
					<th width="12%">操縱變因</th>
					<th width="12%">應變變因</th>
				</tr>
				<tr>
					<td><span class="question_name"></span></td>
					<td><span class="question_assume"></span></td>
					<td align="center"><span class="question_independent"></span></td>
					<td align="center"><span class="question_dependent"></span></td>
				</tr>
				<tr>
					<td colspan="4">實驗紀錄方式：<span class="question_record"></td>
				</tr>
				<tr>
					<th colspan="4">研究材料與工具</th>
				</tr>
				<tr>
					<td colspan="4"><span class="question_material"></span></td>
				</tr>
				<tr>
					<th colspan="4">研究實驗步驟</th>
				</tr>
				<tr>
					<td colspan="4"><span class="question_steps"></span></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox2-3">
		<div class="fancybox_area" id="examine_detail_area2-3">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看研究紀錄表格 -</h2>
			<table class="research_record_table">
				<tr class="research_record_tr">
					<th width="30%">研究問題</th>
					<th width="45%">研究假設</th>
					<th width="8%">研究<br/>構想</th>
					<th width="9%">紀錄<br/>表格</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 是否有針對研究問題和研究步驟來設計紀錄表格？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 紀錄表格中是否有包含「操縱變因」和「應變變因」？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 紀錄表格中是否有標示單位？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox2-4-p">
		<div class="fancybox_area" id="examine_detail_area2-4-p">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看嘗試性實驗 -</h2>
			<table border="1">
				<tr>
					<th width="100%" colspan="3">研究問題</th>
				</tr>
				<tr>
					<td colspan="3"><span class="pilot_read_question"></td>
				</tr>
				<tr>
					<th width="100%" colspan="3">研究假設</th>
				</tr>
				<tr>
					<td colspan="3"><span class="pilot_read_assume"></td>
				</tr>
				<tr>
					<th width="10%">實驗結果</th>
					<th width="70%">是否需修改？</th>
					<th width="10%">附件</th>
				</tr>
				<tr>
					<td align="center"><span class="pilot_read_result"></td>
					<td><span class="pilot_read_fixed"></td>
					<td align="center"><span class="pilot_read_fileurl"></td>
				</tr>
				<tr>
					<th width="100%" colspan="3">結果說明</th>
				</tr>
				<tr>
					<td colspan="3"><span class="pilot_read_description"></td>
				</tr>
				<tr>
					<th width="100%" colspan="3">應注意和改進事項</th>
				</tr>
				<tr>
					<td colspan="3"><span class="pilot_read_attention"></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox2-4">
		<div class="fancybox_area" id="examine_detail_area2-4">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看總嘗試性實驗 -</h2>
			<table class="research_pilot_table">
				<tr class="research_pilot_tr">
					<th width="30%">研究問題</th>
					<th width="30%">研究假設</th>
					<th width="8%">研究<br/>構想</th>
					<th width="9%">研究<br/>結果</th>
					<th width="9%">研究<br/>報告</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 我們是否小心、謹慎進行測量？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 有謹慎地保持「控制變因」不變，以免影響到實驗結果嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 有在實驗日誌中詳細的描述觀察到的現象及記錄數據嗎？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 有使用實驗記錄表來記錄數據嗎？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 有沒有提出實驗時遇到的困難，並調整實驗方法呢？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox3-1">
		<div class="fancybox_area" id="examine_detail_area3-1">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗日誌 -</h2>
			<table class="research_experiment_table">
				<tr class="research_experiment_tr">
					<th width="30%">研究問題</th>
					<th width="30%">研究假設</th>
					<th width="8%">研究<br/>構想</th>
					<th width="8%">紀錄<br/>表格</th>
					<th width="9%">研究<br/>結果</th>
					<th width="9%">研究<br/>報告</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 我們是否小心、謹慎進行測量？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 有謹慎地保持「控制變因」不變，以免影響到實驗結果嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 有在實驗日誌中詳細的描述觀察到的現象及記錄數據嗎？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 有使用實驗記錄表來記錄數據嗎？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox3-2">
		<div class="fancybox_area" id="examine_detail_area3-2">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗分析 -</h2>
			<table class="research_analysis_table">
				<tr class="research_analysis_tr">
					<th width="30%">研究問題</th>
					<th width="30%">研究假設</th>
					<th width="8%">研究<br/>構想</th>
					<th width="8%">實驗<br/>日誌</th>
					<th width="9%">資料<br/>分析</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 是否有足夠的數據知道我們的假設是正確的？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 我們的數據準確嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 我們是否有將數據經過「平均」或是其他計算方式呈現呢？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 我們的數據是否有標示正確的單位？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 我們是否已經驗證所有的計算都是正確的？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
				<tr>
					<td>6. 我們選擇的圖表類型是否適當？</td>
					<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
				</tr>
				<tr>
					<td>7. 我們的圖表有標題嗎？</td>
					<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
				</tr>
				<tr>
					<td>8. 在圖表上，X軸是否為「操縱變因」，Y軸為「應變變因」？</td>
					<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
				</tr>
				<tr>
					<td>9. X軸及Y軸是否都有標示單位？</td>
					<td class="check_choose"><input type="radio" name="check_9" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_9" value="0" /></td>
				</tr>
				<tr>
					<td>10. 圖表是否有適當的比例？</td>
					<td class="check_choose"><input type="radio" name="check_10" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_10" value="0" /></td>
				</tr>
				<tr>
					<td>11. 圖表的繪製是否正確且清晰？</td>
					<td class="check_choose"><input type="radio" name="check_11" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_11" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox3-3">
		<div class="fancybox_area" id="examine_detail_area3-3">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗結果 -</h2>
			<table class="research_result_table">
				<tr class="research_result_tr">
					<th width="30%">研究問題</th>
					<th width="30%">研究假設</th>
					<th width="8%">研究<br/>構想</th>
					<th width="8%">實驗<br/>日誌</th>
					<th width="9%">資料<br/>分析</th>
					<th width="9%">研究<br/>結果</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 是否有簡單敘述實驗的設計與過程？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 是否有說明數據以及圖表的趨勢及差異？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox4-1">
		<div class="fancybox_area" id="examine_detail_area4-1">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗討論 -</h2>
			<table class="research_discussion_table">
				<tr class="research_discussion_tr">
					<th width="10%">類別</th>
					<th width="30%">相關研究問題</th>
					<th width="30%">相關研究討論</th>
				</tr>
			</table>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 我們有沒有說明每一組數據、圖表的涵意？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 我們有沒有提出誤差的來源有哪些？這些誤差如何影響實驗的結果？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 我們有沒有簡略說明實驗結果的意義？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox4-2">
		<div class="fancybox_area" id="examine_detail_area4-2">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看實驗結論 -</h2>
			<table>
				<tr>
					<th width="20%">編號</th>
					<th width="80%">實驗結論</th>
				</tr>
			</table>
			<hr />
			<div class="research_conclusion_area">
			</div>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 我們的結論和實驗結果有沒有矛盾？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 我們的結論是否證明或反駁我們的研究假設？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 我們是否說出「操縱變因」和「應變變因」之間的關係？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 我們是否有提出改進實驗的方法，或是未來做進一步研究的可能性？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox5-1">
		<div class="fancybox_area" id="examine_detail_area5-1">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 下載作品報告書 -</h2>
			<div class="research_complete_area"></div>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 摘要是否包含：簡介、研究問題、研究方法、結果、結論？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 摘要有沒有引起讀者對科展的興趣？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 有沒有符合作品說明書的文字及標題格式？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 作品說明書中有沒有錯別字？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 作品說明書有沒有包含以下幾個章節？
						<div>- 標題頁</div>
						<div>- 摘要</div>
						<div>- 目錄</div>
						<div>- 研究問題、變因和假設</div>
						<div>- 材料清單</div>
						<div>- 實驗計畫</div>
						<div>- 實驗結果</div>
						<div>- 實驗結果</div>
						<div>- 結論(包含未來研究的建議)</div>
						<div>- 參考書目</div>
					</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox5-2">
		<div class="fancybox_area" id="examine_detail_area5-2">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看作品海報 -</h2>
			<div class="research_report_area"></div>
			<p class="fancybox_tips">[p.s 載入海報時間稍久，請稍微等待一下，謝謝您。]</p>
		<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 看板包含：
						<div> (1) 標題</div>
						<div> (2) 摘要</div>
						<div> (3) 研究問題</div>
						<div> (4) 變因和假設</div>
						<div> (5) 材料清單</div>
						<div> (6) 實驗計畫</div>
						<div> (7) 資料分析和討論(包含統計圖表)</div>
						<div> (8) 結論(包含未來研究的建議)</div>
						<div> (9) 參考書目</div>
					</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 海報有沒有進行適當的排版，讓其他人容易看得懂嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 字體夠大嗎？(至少16字體)</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 標題能不能吸引人，標題的字體能不能從教室的另一端看得見呢？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 我們有沒有使用照片及圖表來傳達我們的研究結果呢？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
				<tr>
					<td>6. 有沒有盡可能簡潔地架構科展看板？</td>
					<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
				</tr>
				<tr>
					<td>7. 有沒有依照科展的規定來設計科展看板？</td>
					<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox5-3">
		<div class="fancybox_area" id="examine_detail_area5-3">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看上傳影片 -</h2>
			<div class="research_vedio_area"></div>
			<hr />
			<table class="fancybox_table">
				<tr>
					<th width="80%">檢核表題目</th>
					<th width="10%"><center>是</center></th>
					<th width="10%"><center>否</center></th>
				</tr>
				<tr>
					<td>1. 你有在影片中提到這次的研究動機嗎？</td>
					<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
				</tr>
				<tr>
					<td>2. 你有提到怎麼進行實驗的嗎？</td>
					<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
				</tr>
				<tr>
					<td>3. 你有提到實驗的結果和你們的結論嗎？</td>
					<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
				</tr>
				<tr>
					<td>4. 你有提到你們的研究對社會的幫助嗎？</td>
					<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
				</tr>
				<tr>
					<td>5. 你有展示你們用於研究的基礎理論嗎？</td>
					<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
				</tr>
				<tr>
					<td>6. 你有準備好評審可能會問的「問題清單」嗎？</td>
					<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
				</tr>
				<tr>
					<td>7. 你有充分練習回答問題的技巧嗎？</td>
					<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
					<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
				</tr>
			</table>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
	<div class="fancybox_box" id="examine_detail_fancybox5-4">
		<div class="fancybox_area" id="examine_detail_area5-4">
			<div class="fancybox_cancel"><img src="../model/images/project_close.png" width="20px"></div>
			<h2>- 觀看問與答 -</h2>
			<div class="research_qna_area"></div>
			<input type="button" class="examine_detail_submit" value="確定">
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>