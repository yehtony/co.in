<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > 創立新專題小組';
	include("api/php/header.php");
?>
<style>
/*----------------------------------創立新專題小組----------------------------------*/
#group_field{
	width: 60%;
	margin: 15px auto;
	padding: 0px 80px 20px 80px;
}
.group_block{
	width: 80%;
	height: 105px;
	margin-top: -5px;
	margin-left: 53px;
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
	margin-top: -35px;
	margin-right: 30px;
}
.project_img img{
	width: 100px;
}
</style>
<script>
$(function(){
/*----------------------------------創立新專題小組----------------------------------*/
	$("select[name=project_chief]").change(function(){
		var add_id = $(this).val();
		$(".project_group option").show();
		$(".project_group option[value='"+ add_id +"']").hide();
	});
	// 點擊[加入組員]，加入專題組員
	$(".group_add").click(function(){
		var add_id = $(".project_group").val();
		var add_name = $(".project_group").find("option[value='"+ add_id +"']").text();
		var group_id = [];				// 專題組員(如多於三個人則可能無法參加科展)

		$("#project_form .group_block .group_choosed").each(function(){
			group_id.push($(this).attr("value"));
		});

		if(group_id.length > 5){		// 最多六個人(暫)
			$(".fancybox_mark").text("*超過六個組員，可能會違反科展相關規定，如未參加請跳過此提醒。");
		}

		if(add_id != "" && add_id != "default" && add_id != "null"){
			$("<div class='group_choosed' value='"+ add_id +"'>"+ add_name +
				"<sapn class='group_deleted'>[刪除]</sapn>"+
				"</div>").appendTo(".group_block");
			$(".project_group option[value='"+ add_id +"']").hide();
			$(".project_group").val("default");
		}
		// 點擊組員裡的[刪除]，刪除專題組員
		$(".group_deleted").click(function() {
			var del_id = $(this).parent().attr("value");
			var del_name = $(this).parent().text();
			$(".project_group option[value='"+ add_id +"']").show();
			$(this).parent().remove();

			if(group_id.length < 7){
				$(".fancybox_mark").text("");
			}
		});
	});
	// 點擊吉祥物
	$("select[name=project_mascot]").change(function(){
		if(this.value != "default"){
			$(".project_img").html("<img src='/co.in/science/model/images/"+ this.value +"'>");
		}else{
			$(".project_img").html("");	// 清空圖片
		}
	});
	// 按下創立小組
	$("#project_create").click(function(){
		var pname = $("input[name=project_pname]").val();
		var theme = $("select[name=project_theme]").val();
		var mascot = $("select[name=project_mascot]").val();
		var group_id = [];				// 專題組員(如多於三個人則可能無法參加科展)
		var chief_id = $("select[name=project_chief]").val();

		$("#project_form .group_block .group_choosed").each(function(){	// 最多三個人(暫)
			group_id.push($(this).attr("value"));
		});

		if(pname == ""){
			alert("【系統】請填寫小組名稱。");
		}else if(theme == "default"){
			alert("【系統】請選擇學科。");
		}else if(chief_id == "default"){
			alert("【系統】請選擇小組組長。");
		}else if(mascot == "default"){
			alert("【系統】請選擇吉祥物。");
		}else{
			// console.log(group_id);
			$("#project_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "group_project",
					group_id : group_id,
					action : "project_update"
				},
				error : function(){
					alert("【系統】創立失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】新專題小組創立成功！");
					window.location.href = "./";
					// window.location.reload();
				}
			});
		}
	});
});
</script>
<div id="centercolumn">
	<fieldset id="group_field">
		<h1 align="center">創立新小組<img src="../model/images/project_info.png" width="25px" title="創立新小組：要注意如果要參與科學展覽，請記得先參考參展須知！" style="vertical-align: middle;"></h1>
		<form id="project_form" method="post">
			<p>
				<div class="project_img"></div>
			</p>
			<p>
				<label>小組名稱：</label>
				<input type="text" name="project_pname">
			</p>
			<p>
				<label>組長：</label>
				<select name="project_chief">
					<option value="default">請選擇小組組長</option>
					<?php
						$sql = "SELECT `u_id`, `name`, `school` FROM  `userinfo` WHERE `identity` = 'S' ORDER BY `register_time` DESC";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<option value='".$row['u_id']."'>".$row['name']."(".$row['school'].")</option>";
							}
						}else{
							echo "<option value='null'>尚未有任何【同校】組長！</option>";
						}
					?>
				</select>
			</p>
			<p>
				<label>組員：</label>
				<select class="project_group">
					<option value="default">請選擇組員</option>
					<?php
						$sql = "SELECT `u_id`, `name`, `school` FROM  `userinfo` WHERE `school` = '".$_SESSION['school']."' AND `identity` = 'S' AND `u_id` != '".$_SESSION['UID']."' ORDER BY `register_time` DESC";  // `school` = '".$_SESSION['school']."' AND
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
				<label>指導老師：<?php echo $_SESSION['name'].'('.$_SESSION['school'].')'; ?></label>
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
	</fieldset>
</div>
<?php
	include("api/php/footer.php");
?>