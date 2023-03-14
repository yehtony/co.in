<?php
	$page_url = '';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------入口網站-------------------------------------*/
#centercolumn{
	min-height: 90%;
}
/*-------------------------------------創立小組-------------------------------------*/
.group_block{
	width: 80%;
	height: 105px;
	margin-top: -5px;
	margin-left: 58px;
	border: 1px #AAAAAA solid;
	overflow-y: auto;
}
.group_choosed{
	padding: 3px 5px 3px 5px;
	margin: 5px 5px 5px 5px;
	font-size: 14px;
	text-align: center;
	border: rgb(165,165,165) 1px solid;
	border-radius: 3px;
}
.group_deleted{
	float: right;
	margin-left: 5px;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
.project_img{
	float: right;
	margin-right: 30px;
}
.project_img img{
	width: 100px;
}
.project_said, .project_cross{
	font-size: 14px;
	color: blue;
	cursor: pointer;
	display: inline-block;
}
/*-----------------------------------重新選擇老師-----------------------------------*/
#re_teacher_area{
	width: 25%;
	height: 150px;
}
</style>
<div id="centercolumn">
	<div id="leftcolumn">
		<img class='log_btn' value="專題探究學習系統welcome" src="/co.in/science/model/images/welcome.png" width="90%" style="margin-left: 15px;" onclick="javascript:introJs().start();">
		<fieldset id="info_field" style="margin-top: 15px;" data-step="4" data-intro="其他：可開啟介面導覽、觀看系統介紹和快速使用說明。" data-position='right'>
			<center>
				<p><b>更了解專題探究學習系統</b></p>
				<p class='log_btn' value="專題探究學習系統介面導覽"><a href="javascript:void(0);" onclick="javascript:introJs().start();">專題探究學習系統介面導覽</a></p>
				<p class='log_btn' value="認識科展活動"><a href="tool_intro.php">認識科學專題活動</a></p>
				<p class='log_btn' value="系統特色介紹"><a href="tool_feature.php">系統特色介紹</a></p>
			</center>
		</fieldset>
	</div>
	<div id="rightcolumn">
		<fieldset id="info_field" style="min-height: 390px;" data-step="5" data-intro="進行中的專案小組：目前進行的專案小組任務，也可以創立新小組。" data-position='left'>
			<legend>進行中的專題小組</legend>
			<table width="100%">
				<?php
					$sql = "SELECT `project`.*, `project_group`.`chief` FROM  `project_group`
									LEFT OUTER JOIN `project`
									ON `project`.`p_id` = `project_group`.`p_id`
							WHERE `project_group`.`s_id` = '".$_SESSION['UID']."' ORDER BY `project`.`p_id` ASC";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							if($row['state'] == '0'){
								echo "<tr class='log_btn' value='進入【".$row['pname']."】'><td class='project_enter' value='".$row['p_id']."|".$row['state']."|".$row['chief']."'>".$row['pname']."</td></tr>";
							}else if($row['state'] == '1'){
								echo "<tr class='log_btn' value='進入【".$row['pname']."】(完成)'><td class='project_enter' value='".$row['p_id']."|".$row['state']."|".$row['chief']."'>".$row['pname']."(完成)</td></tr>";
							}else if($row['state'] == '2'){
								echo "<tr class='log_btn' value='進入【".$row['pname']."】(申請中)'><td class='project_enter' value='".$row['p_id']."|".$row['state']."|".$row['chief']."'>".$row['pname']."(申請中)</td></tr>";
							}else if($row['state'] == '3'){
								echo "<tr class='log_btn' value='進入【".$row['pname']."】(重新選擇老師)'><td class='project_enter' value='".$row['p_id']."|".$row['state']."|".$row['chief']."'>".$row['pname']."(重新選擇老師)</td></tr>";
							}
						}
					}
				?>
				<tr class='log_btn' value='創立新小組'>
					<td class='project_new'>我是組長，我要創立新小組</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="fancybox_box" id="project_fancybox">
		<div class="fancybox_area" id="project_area">
			<div class="fancybox_cancel"><img src="/co.in/science/model/images/project_close.png" width="20px"></div>
			<h2>- 創立新小組 -<img src="../model/images/project_info.png" width="25px" title="創立新小組：要注意如果要參與科學展覽，請記得先參考參展須知！" style="vertical-align: middle;"></h2>
			<form id="project_form" method="post">
				<p>
					<div class="project_img"></div>
				</p>
				<p>
					<label>小組名稱：</label>
					<input type="text" name="project_pname">
				</p>
				<p>
					<label>組長：</label><?php echo $_SESSION['name'].'('.$_SESSION['school'].')'; ?>
				</p>
				<p>
					<label>組員：</label>
					<select class="project_group">
						<option value="default">請選擇組員</option>
						<?php
							$sql = "SELECT `u_id`, `name`, `school` FROM  `userinfo` WHERE `identity` = 'S' AND `u_id` != '".$_SESSION['UID']."' ORDER BY `register_time` DESC";  // `school` = '".$_SESSION['school']."' AND
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['u_id']."'>".$row['name']."(".$row['school'].")</option>";
								}
							}else{
								echo "<option value='null'>尚未有任何【同校】組員！</option>";
							}
						?>
					</select>
					<input type="button" class="group_add" value="加入組員">
					<div class="group_block"></div>
					<span class="fancybox_mark"></span>
				</p>
				<p>
					<label>學科類別：</label>
					<select name="project_theme">
						<option value="default">請選擇學科</option>
						<option value="1">物理</option>
						<option value="2">化學</option>
						<option value="3">生物</option>
						<option value="4">地球科學</option>
						<option value="5">數學</option>
						<option value="6">生活與應用科學</option>
					</select>
				</p>
				<p>
					<label>指導老師：</label>
					<select name="project_teacher">
						<option value="default">請選擇指導老師</option>
						<?php
							$sql = "SELECT `u_id`, `name`, `school` FROM  `userinfo` WHERE `school` = '".$_SESSION['school']."' AND `identity` = 'T' ORDER BY `register_time` DESC";
							$qry = mysql_query($sql, $link) or die(mysql_error());
							if(mysql_num_rows($qry) > 0){
								while($row = mysql_fetch_array($qry)){
									echo "<option value='".$row['u_id']."'>".$row['name']."(".$row['school'].")</option>";
								}
							}else{
								echo "<option value='null'>尚未有任何【同校】老師！</option>";
							}
						?>
					</select>
					<!-- <span class="project_said">[給老師一些話]</span>
					<span class="project_cross"> / [選擇跨校指導…]</span> -->
				</p>
				<p>
					<label>吉祥物：</label>
					<select name="project_mascot">
						<option value="default">請選擇小組吉祥物</option>
						<option value="mascot_01.png">小狗</option>
						<option value="mascot_02.png">小貓</option>
						<option value="mascot_03.png">兔子</option>
						<option value="mascot_04.png">乳牛</option>
						<option value="mascot_05.png">小豬</option>
						<option value="mascot_06.png">母雞</option>
						<option value="mascot_07.png">公雞</option>
						<option value="mascot_08.png">金絲雀</option>
						<option value="mascot_09.png">貓頭鷹</option>
						<option value="mascot_10.png">老鷹</option>
						<option value="mascot_11.png">獅子</option>
						<option value="mascot_12.png">老虎</option>
						<option value="mascot_13.png">獵豹</option>
						<option value="mascot_14.png">大象</option>
						<option value="mascot_15.png">小羊</option>
						<option value="mascot_16.png">猴子</option>
						<option value="mascot_17.png">熊貓</option>
						<option value="mascot_18.png">狐狸</option>
						<option value="mascot_19.png">公鹿</option>
						<option value="mascot_20.png">驢子</option>
						<option value="mascot_21.png">斑馬</option>
						<option value="mascot_22.png">企鵝</option>
					</select>
				</p>
				<input type="button" class="fancybox_btn" id="project_create" value="創立小組">
			</form>
		</div>
	</div>
	<div class="fancybox_box" id="re_teacher_fancybox">
		<div class="fancybox_area" id="re_teacher_area">
			<div class="fancybox_cancel"><img src="/co.in/science/model/images/project_close.png" width="20px"></div>
			<h2>- 重新選擇老師 -<img src="../model/images/project_info.png" width="25px" title="小組創立須知！" style="vertical-align: middle;"></h2>
			<form id="re_teacher_form" method="post">
				<input type="text" name="project_id" style="display: none;"><!-- 藏值 -->
				<label>指導老師：</label>
				<select name="project_re_teacher">
					<option value="default">請選擇指導老師</option>
					<?php
						$sql = "SELECT `u_id`, `name`, `school` FROM  `userinfo` WHERE `school` = '".$_SESSION['school']."' AND `identity` = 'T' ORDER BY `register_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<option value='".$row['u_id']."'>".$row['name']."(".$row['school'].")</option>";
							}
						}else{
							echo "<option value='null'>尚未有任何【同校】老師！</option>";
						}
					?>
				</select><br />
				<!-- <span class="project_said">[給老師一些話]</span>
				<span class="project_cross"> / [選擇跨校指導…]</span> -->
				<input type="button" class="fancybox_btn" id="re_teacher" value="送出">
			</form>
		</div>
	</div>
</div>
<?php
	include ("api/php/footer.php");
?>