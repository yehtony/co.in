<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > <a href="project_task.php?p_id='.$_GET['p_id'].'">小組任務編輯</a> > 小組時間規劃';
	include("api/php/header.php");
?>
<style>
/*------------------------------專題管理：規劃小組時間------------------------------*/
.time_set{
	width: 80%;
	margin: 0px auto;
}
.time_set p{
	float: left;
	margin-right: 60px;
}
.time_table{
	width: 80%;
	margin: 0px auto;
	text-align: center;
	border-collapse: collapse;
}
.time_table th{
	padding: 10px;
	border: 1px solid #000000;
}
.time_table td{
	padding: 10px;
	border: 1px solid #000000;
}
.time_table input[type=number]{
	width: 60%;
}
.time_btn{
	text-align: center;
}
.time_btn input{
	margin: 20px;
}
</style>
<script>
$(function(){
/*------------------------------專題管理：規劃小組時間------------------------------*/
	// 讀取資料
	var stage_arr = ["1-1", "1-2", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4"];
	var days_new = "";
	var days_old = "";

	$.ajax({
		url  : "/co.in/science/teacher/api/php/science.php",
		type : "POST",
		async: "true",
		dataType : "json",
		data : {
			type : "get_schedule",
			p_id : <?php echo $_GET['p_id']; ?>,
			action : "schedule_update"
		},
		error : function(e, status){
			alert("【警告】讀取失敗，請檢查網絡連線問題。");
		},
		success : function(data){
			for(var a in data){
				for(var i = 0, j = stage_arr.length; i < j; i++){
					if(i == 0){
						$("input[name=exp_days"+ stage_arr[i] +"]").val(deleteDate(data[a].starttime, data[a]["exp"+ stage_arr[i]]));
					}else{
						// 後面日期
						days_new = deleteDate(data[a].starttime, data[a]["exp"+ stage_arr[i]]);

						$("input[name=exp_days"+ stage_arr[i] +"]").val(days_new - days_old);
					}
					// 前面日期
					days_old = deleteDate(data[a].starttime, data[a]["exp"+ stage_arr[i]]);
				}
			}
		}
	});
	// 儲存資料
	$("input[name=save_time_info]").click(function(){
		var checktime = 1;  	// checktime是代表input的days值 (0是有空; 1是都有值)
		var hint = $("input[name=hint_time]:checked").val();
		var starttime = $("input[name=start_time]").val();
		var endtime = $("input[name=end_time]").val();
		var days = [];
		var exp = [];
		console.log(hint, starttime, endtime);
		$("#time_form input[type=number]").each(function(){
			if($(this).val() < 0){
				checktime = 0;
			};
		});

		if(checktime == "0"){
			alert("【系統】小組時間未填寫完成或工作天數不可小於零。");
		}else{
			$("#time_form input[type=number]").each(function(){
				days.push($(this).val());
			});

			var stage_arr = ["1-1", "1-2", "2-1", "2-2", "2-3", "2-4", "3-1", "3-2", "3-3", "4-1", "4-2", "5-1", "5-2", "5-3", "5-4"];

			// 將值推入預計完成日期中
			for(var i = 0; i < stage_arr.length; i++){
				if(i == 0){
					$("input[name=exp_time"+ stage_arr[i] +"]").val(addDate(starttime, days[i]));
					exp.push($("input[name=exp_time"+ stage_arr[i] +"]").val());
				}else{
					$("input[name=exp_time"+ stage_arr[i] +"]").val(addDate(exp[i-1], days[i]));
					exp.push($("input[name=exp_time"+ stage_arr[i] +"]").val());
				}
			}
			$("input[name=end_time]").val(exp[14]);

			console.log(exp);
	
			$("#time_form").ajaxSubmit({
				url  : "/co.in/science/teacher/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "new_schedule",
					p_id : <?php echo $_GET['p_id']; ?>,
					starttime : starttime,
					endtime : endtime,
					exp : exp,
					hint : hint,
					action : "schedule_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function(data){
					alert("【系統】規劃小組時間儲存成功！");
					window.location.reload();
				}
			});
		}
	});
	function deleteDate(startDate, endDate){
		start = Date.parse(startDate);
		end = Date.parse(endDate);
		numDays = (end-start)/(1000*60*60*24);
		return numDays;
	}
	// 加上日期
	function addDate(dtDate, NumDay){
		var dtTmp = new Date(dtDate);
		 // if(isNaN(dtTmp)) dtTmp= new Date();	// isNaN是否為數字
			dtTmp = new Date(Date.parse(dtTmp) + (86400000*parseInt(NumDay)));
		var mStr = new String(dtTmp.getMonth() + 1);
		var dStr = new String(dtTmp.getDate());
		if(mStr.length == 1){
			mStr = "0" + mStr;
		}
		if(dStr.length == 1){
			dStr = "0" + dStr;
		}
		var ndate = dtTmp.getFullYear() + "-" + mStr + "-" + dStr;
		//console.log(ndate);
		return ndate;
	}
});
</script>
<div id="centercolumn">
	<div id="time_pages">
		<h1 align="center">規劃小組時間</h1>
		<div class="time_set">
			<?php
				// 開始時間
				$sql = "SELECT * FROM `project` WHERE `p_id`= '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						echo "<p>專題開始時間：<input type='date' name='start_time' size='20' value='".$row['starttime']."' disabled></p>";
					}
				}
					mysql_query($sql, $link) or die(mysql_error());

				// 小組時間規劃
				$sql = "SELECT * FROM `project_schedule` WHERE `p_id`= '".$_GET['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				$row = mysql_fetch_array($qry);
			?>
			<p>預計結束日期：<?php echo "<input type='date' name='end_time' size='20' value='".$row['exp5-4']."' disabled>"; ?></p>
			<p>是否開啟提示<img src="../model/images/project_info.png" width="25px" title="開啟提示：當時間快到時，會提醒該小組時間快到了！" style="vertical-align: middle;">：
				ON <input type="radio" name="hint_time" value="1" />
				OFF<input type="radio" name="hint_time" value="0" checked /></p>
		</div>
		<form id="time_form">
			<table class="time_table">
				<tr>
					<th width="30%">專題階段</th>
					<th width="20%">預計工作天數</th>
					<th width="25%">預計完成日期</th>
					<th width="25%">實際完成日期</th>
				</tr>
				<tr>
					<td>1-1 決定研究主題</td>
					<td><input type="number" name="exp_days1-1" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time1-1' size='20' value='".$row['exp1-1']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time1-1' size='20' value='".$row['real1-1']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>1-2 決定研究題目</td>
					<td><input type="number" name="exp_days1-2" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time1-2' size='20' value='".$row['exp1-2']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time1-2' size='20' value='".$row['real1-2']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>2-1 提出研究問題</td>
					<td><input type="number" name="exp_days2-1" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time2-1' size='20' value='".$row['exp2-1']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time2-1' size='20' value='".$row['real2-1']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>2-2 撰寫研究構想</td>
					<td><input type="number" name="exp_days2-2" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time2-2' size='20' value='".$row['exp2-2']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time2-2' size='20' value='".$row['real2-2']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>2-3 設計記錄表格</td>
					<td><input type="number" name="exp_days2-3" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time2-3' size='20' value='".$row['exp2-3']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time2-3' size='20' value='".$row['real2-3']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>2-4 進行嘗試性實驗</td>
					<td><input type="number" name="exp_days2-4" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time2-4' size='20' value='".$row['exp2-4']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time2-4' size='20' value='".$row['real2-4']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>3-1 進行實驗並記錄</td>
					<td><input type="number" name="exp_days3-1" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time3-1' size='20' value='".$row['exp3-1']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time3-1' size='20' value='".$row['real3-1']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>3-2 分析資料與繪圖</td>
					<td><input type="number" name="exp_days3-2" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time3-2' size='20' value='".$row['exp3-2']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time3-2' size='20' value='".$row['real3-2']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>3-3 撰寫研究結果</td>
					<td><input type="number" name="exp_days3-3" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time3-3' size='20' value='".$row['exp3-3']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time3-3' size='20' value='".$row['real3-3']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>4-1 進行研究討論</td>
					<td><input type="number" name="exp_days4-1" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time4-1' size='20' value='".$row['exp4-1']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time4-1' size='20' value='".$row['real4-1']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>4-2 撰寫研究結論</td>
					<td><input type="number" name="exp_days4-2" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time4-2' size='20' value='".$row['exp4-2']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time4-2' size='20' value='".$row['real4-2']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>5-1 統整作品報告書</td>
					<td><input type="number" name="exp_days5-1" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time5-1' size='20' value='".$row['exp5-1']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time5-1' size='20' value='".$row['real5-1']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>5-2 製作作品海報</td>
					<td><input type="number" name="exp_days5-2" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time5-2' size='20' value='".$row['exp5-2']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time5-2' size='20' value='".$row['real5-2']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>5-3 錄製報告影片</td>
					<td><input type="number" name="exp_days5-3" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time5-3' size='20' value='".$row['exp5-3']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time5-3' size='20' value='".$row['real5-3']."' disabled>"; ?></td>
				</tr>
				<tr>
					<td>5-4 討論與反思</td>
					<td><input type="number" name="exp_days5-4" min="1" max="99" value="0"> 天</td>
					<td><?php echo "<input type='date' name='exp_time5-4' size='20' value='".$row['exp5-4']."' disabled>"; ?></td>
					<td><?php echo "<input type='date' name='real_time5-4' size='20' value='".$row['real5-4']."' disabled>"; ?></td>
				</tr>
			</table>
			<div class="time_btn">
				<input type="reset" name="reset_time_info" value="上一步：全部重設" />
				<input type="button" name="save_time_info" value="儲存時間" />
				<input type="button" onclick="location.href='project_open.php?p_id=<?php echo $_GET['p_id']; ?>'" value="下一步：開放程度" />
			</div>
		</form>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>