<?php
	$page_url = '<a href="index.php">專題指導</a> > <a href="nav_project.php">專題管理</a> > <a href="project_task.php?p_id='.$_GET['p_id'].'">小組任務編輯</a> > 任務開放程度設定';
	include("api/php/header.php");
?>
<style>
/*------------------------------專題管理：決定開放程度------------------------------*/
#choice_pages{
	width: 80%;
	margin: 10px auto;
	border: 1px solid #000000;
}
.open_list{
	margin-left: 100px;
	font-size: 22px;
	line-height: 50px;
	list-style-type: none;
}
.open_list li ul{
	font-size: 18px;
	list-style-type: none;
	line-height: 40px;
	display: none;
}
.open_btn{
	text-align: center;
}
.open_btn button{
	margin: 0px 20px 20px 20px;
}
/*------------------------------專題管理：填寫階段資料------------------------------*/
#open_pages{
	width: 80%;
	margin: 15px auto;
	border: 1px solid #000000;
	display: none;
}
.open_remind{
	margin: 100px 0px 150px 0px;
	font-size: 200%;
	font-style: italic;
	text-align: center;
	color: #808080;
}
.open_table{
	width: 100%;
	margin: 0 auto;
}
.open_table{	
/*	display: none;*/
}
.open_table th{
	width: 175px;
	padding: 5px 25px;
	font-size: 120%;
	text-align: left;
}
.open_table td{
	padding: 15px;
}
.open_table textarea{
	width: 95%;
	height: 100px;
	resize: none;
}
.open_btn input{
	margin: 0px 10px 20px 10px;
}
.open_info5 span{
	padding-left: 5px;
	font-size: 15px;
	color: blue;
	cursor: pointer;
}
.open_info6 span{
	margin-left: 50px;
	padding: 1px 0px 6px 0px;
	border: 5px solid #1C90FD;
	border-radius: 30px;
	cursor: pointer;
}
.open_info6 span img{
	vertical-align: middle;
}
.open_info7 input[type=file], .open_info10 input[type=file], .open_info11 input[type=file]{
	margin-left: 35px;
	width: 70px;
}
</style>
<script>
$(function(){
/*------------------------------專題管理：決定開放程度------------------------------*/
	$(".reset_open_info").click(function(){
		// 取消所有勾勾(for)
		for(var j = 14; j >= 0; j--){
			$("input[name=open_stage"+ j +"]").prop("checked", false);
			$(".open_info"+ j).hide();
		}
		// 收合所有(for)
		for(var j = 0; j < 5; j++){
			$(".open_stage_ul"+ j).hide();
		}
	});
	$("input[type=checkbox]").change(function(){
		var open_list = $(this).attr('id');

		if($(this).prop("checked") == true){
			var open_ul = 0;
			// 大標 > 小標
			if(open_list == 1){
				open_list = 3;
			}else if(open_list == 4){
				open_list = 7;
			}else if(open_list == 8){
				open_list = 11;
			}else if(open_list == 12){
				open_list = 14;
			}
			// 打勾勾(for)
			for(var i = 1; i <= open_list; i++){
				$("input[name=open_stage"+ i +"]").prop("checked", true);
				$(".open_remind").hide();
				$(".open_info"+ i).show();
			}
			// 展開
			if(open_list >= 1 && open_list < 4){
				open_ul = 1;
			}else if(open_list >= 4 && open_list < 8){
				open_ul = 2;
			}else if(open_list >= 8 && open_list < 12){
				open_ul = 3;
			}else if(open_list >= 12 && open_list <= 14){
				open_ul = 4;
			}
			// 展開(for)
			for(var i = 1; i <= open_ul; i++){
				$(".open_stage_ul"+ i).show();
			}
		}else if($(this).prop("checked") == false){
			var open_ul = 5;
			// 小標 > 大標
			if(open_list == 2){
				open_list = 1;
			}else if(open_list == 5){
				open_list = 4;
			}else if(open_list == 9){
				open_list = 8;
			}else if(open_list == 13){
				open_list = 12;
			}
			// 取消勾勾(for)
			for(var j = 14; j >= open_list; j--){
				$("input[name=open_stage"+ j +"]").prop("checked", false);
				$(".open_info"+ j).hide();
				if(open_list == "1"){
					$(".open_remind").show();
				}
			}
			// 收合
			if(open_list == 1 || open_list == 2){
				open_ul = 1;
			}else if(open_list == 4 || open_list == 5){
				open_ul = 2;
			}else if(open_list == 8 || open_list == 9){
				open_ul = 3;
			}else if(open_list == 12 || open_list == 13){
				open_ul = 4;
			}else if(open_list == 14){
				open_ul = 5;
			}
			// 收合(for)
			for(var j = open_ul; j < 5; j++){
				$(".open_stage_ul"+ j).hide();
			}
		}		
	});
	$(".next_open_info").click(function(){
		$("#choice_pages").hide();
		$("#open_pages").show();
	});
	$(".last_open_info").click(function(){
		$("#open_pages").hide();
		$("#choice_pages").show();
	});
/*------------------------------專題管理：填寫階段資料------------------------------*/
	var counter = 2;
	// 新增動態
	$("#question_add").click(function(){
		if(counter > 4){
			alert("【系統】目前最多四個研究問題，如有問題可以聯絡管理員。");
			return false;
		}
		var newQuestion = $(document.createElement('td')).attr("id", "question_area"+ counter);
			newQuestion.after().html('<input type="text" name="write_open_info5-'+ counter +'" size="15" placeholder="研究問題 #'+ counter +'">');

			newQuestion.appendTo(".open_info5");

		var newIdea = $(document.createElement('td')).attr("id", "idea_box"+ counter);
			newIdea.after().html('<span title="研究構想表 #'+ counter +'"><img src="../model/images/project_add.png" width="30px"/></span>');

			newIdea.appendTo(".open_info6");

		var newRecord = $(document.createElement('td')).attr("id", "record_box"+ counter);
			newRecord.after().html('<input type="file" name="write_open_info7-'+ counter +'" size="20">');

			newRecord.appendTo(".open_info7");

		var newAnalysis = $(document.createElement('td')).attr("id", "analysis_box"+ counter);
			newAnalysis.after().html('<input type="file" name="write_open_info10-'+ counter +'" size="20">');

			newAnalysis.appendTo(".open_info10");

		var newResult = $(document.createElement('td')).attr("id", "result_box"+ counter);
			newResult.after().html('<input type="file" name="write_open_info11-'+ counter +'" size="20">');

			newResult.appendTo(".open_info11");

			counter++;
	});
	// 移除動態
	$("#question_remove").click(function(){
		if(counter == 2){
			alert("【系統】最少需要一個研究問題。");
			return false;
		}
		counter--;

		$("#question_area" + counter).remove();
		$("#idea_new" + counter).remove();
		$("#record_area" + counter).remove();
		$("#analysis_area" + counter).remove();
		$("#result_area" + counter).remove();
	});
	// 動態取值
	$("#getButtonValue").click(function(){
		var msg = '';

		for(i = 1; i < counter; i++){
			msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
		}
		alert(msg);
	});
	$(".save_open_info").click(function(){
		alert("【系統】已設定完成開放程度，並回到小組管理頁面。")
		window.location.href = "/co.in/science/teacher/nav_project.php";
	});
	$(".next_open_time").click(function(){
		window.location.href = "/co.in/science/teacher/project_time.php?p_id=<?php echo $_GET['p_id']; ?>";
	});
});
</script>
<div id="centercolumn">
	<div id="choice_pages">
		<h1 align="center">決定開放程度</h1>
		<ul class="open_list">
			<li><input type="checkbox" name="open_stage1" id="1"><strong> 第一階段：形成問題</strong></li>
			<li>
				<ul class="open_stage_ul1">
					<li><input type="checkbox" name="open_stage2" id="2" value="1-1"> 1-1 決定研究主題</li>
					<li><input type="checkbox" name="open_stage3" id="3" value="1-2"> 1-2 決定研究題目</li>
				</ul>
			</li>
			<li><input type="checkbox" name="open_stage4" id="4"><strong> 第二階段：規劃</strong></li>
			<li>
				<ul class="open_stage_ul2">
					<li><input type="checkbox" name="open_stage5" id="5" value="2-1"> 2-1 提出研究問題</li>
					<li><input type="checkbox" name="open_stage6" id="6" value="2-2"> 2-2 撰寫研究構想</li>
					<li><input type="checkbox" name="open_stage7" id="7" value="2-3"> 2-3 設計記錄表格</li>
				</ul>
			</li>
			<li><input type="checkbox" name="open_stage8" id="8"><strong> 第三階段：執行</strong></li>
			<li>
				<ul class="open_stage_ul3">
					<li><input type="checkbox" name="open_stage9" id="9" value="3-1"> 3-1 進行實驗並記錄</li>
					<li><input type="checkbox" name="open_stage10" id="10" value="3-2"> 3-2 分析資料與繪圖</li>
					<li><input type="checkbox" name="open_stage11" id="11" value="3-3"> 3-3 撰寫研究結果</li>
				</ul>
			</li>
			<li><input type="checkbox" name="open_stage12" id="12"><strong> 第四階段：形成結論</strong></li>
			<li>
				<ul class="open_stage_ul4">
					<li><input type="checkbox" name="open_stage13" id="13" value="4-1"> 4-1 進行研究討論</li>
					<li><input type="checkbox" name="open_stage14" id="14" value="4-2"> 4-2 撰寫研究結論</li>
				</ul>
			</li>
		</ul>
		<div class="open_btn">
			<button class="reset_open_info">上一步：重設選擇</button>
			<button class="next_open_info">下一步：填寫階段資料</button>
		</div>
	</div>
	<div id="open_pages">
		<h1 align="center">填寫階段資料</h1>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info2">
						<th>1-1 決定研究主題</th>
						<td colspan="6"><input type="text" name="write_open_info2" placeholder="研究主題"></td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info3">
						<th>1-2 決定研究題目</th>
						<td colspan="6"><input type="text" name="write_open_info3" placeholder="研究題目"></td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info5">
						<th>2-1 提出研究問題<br />
							<span id='question_add'>[新增問題]</span>
							<span id='question_remove'>[移除問題]</span>
						</th>
						<td id="question_box">
							<div id="question_area1">
								<input type="text" name="write_open_info5-1" size="15" placeholder="研究問題 #1">
							</div>
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info6">
						<th>2-2 撰寫研究構想</th>
						<td id="idea_box1">
							<span id="idea_new1" title="研究構想表 #1"><img src="../model/images/project_add.png" width="30px"/></span>
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info7">
						<th>2-3 設計記錄表格</th>
						<td id="record_box1">
							<input type="file" name="write_open_info7-1">
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info9">
						<th>3-1 進行實驗並記錄</th>
						<td>
							<div id="experiment_area1">
								<select>
									<option value="default">選擇實驗結果...</option>
									<option value="0">成功</option>
									<option value="1">失敗</option>
								</select>
								<input type="text" name="write_open_info9-1" placeholder="描述實驗說明">
							</div>
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info10">
						<th>3-2 分析資料與繪圖</th>
						<td id="analysis_box1">
							<input type="file" name="write_open_info10-1">
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info11">
						<th>3-3 撰寫研究結果</th>
						<td id="result_box1">
							<input type="file" name="write_open_info11-1">
						</td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info13">
						<th>4-1 進行研究討論</th><td><textarea name="write_open_info13"></textarea></td>
					</tr>
				</table>
			</form>
			<form id="open_form">
				<table class="open_table">
					<tr class="open_info14">
						<th>4-2 撰寫研究結論</th><td><textarea name="write_open_info14"></textarea></td>
					</tr>
				</table>
			</form>
			<div class="open_remind">(尚未勾選開放性階段。)</div>
			<div class="open_btn">
				<input type="button" class="last_open_info" value="上一步：決定開放程度">
				<input type="button" class="save_open_info"  value="儲存階段資料">
				<input type="button" class="next_open_time" value="下一步：規劃小組時間">
			</div>
		</form>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>