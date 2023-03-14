<style>
/*---------------------------------小組工作室：2-2----------------------------------*/
.research_steps, .research_material, .research_record{
	float: left;
	margin-left: 23px;
	font-size: 12px;
	color: #808080;
	cursor: pointer;
}
.research_idea_area{
	display: none;
}
.steps_box{
	width: 240px;
	min-height: 260px;
}
.steps_box p{
	text-align: left;
}
.steps_submit{
	margin: 0px;
}
.research_return{
	float: right;
	margin: 0px;
}
#research_check_area{
	width: 40%;
}
/*----------------------------------研究材料：2-2-----------------------------------*/
.material_box{
	float: left;
	width: 38%;
	height: 240px;
	padding: 0px 10px;
	margin-right: 10px;
	border: 1px #AAAAAA solid;
}
.material_block{
	width: 57%;
	height: 240px;
	border: 1px #AAAAAA solid;
	overflow-y: scroll;
}
.material_choosed{
	padding: 3px 5px 3px 5px;
	margin: 5px 5px 5px 5px;
	height: 140px;
	font-size: 14px;
	text-align: center;
	border: rgb(165,165,165) 1px solid;
	border-radius: 3px;
	display: inline-block;
}
.material_deleted{
	float: right;
	margin-left: 5px;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
#material_save{
	float: right;
}
#material_files{
	width: 170px;
	height: 30px;
	cursor: pointer;
}
/*----------------------------------研究步驟：2-2-----------------------------------*/
#steps_btn{
	float: right;
	margin-right: 20px;
}
#steps_btn button{
	padding: 15px 32px;
	margin: 5px;
	font-size: 14px;
	background-color: #59B791;
	border: 1px solid #000000;
	display: block;
}
#steps_sortable table{
	width: 50%;
	margin: 5px 0px;
	margin-left: 180px;
	border: 1px solid #000000;
	border-collapse: collapse;
}
#steps_sortable table th{
	width: 25%;
	line-height: 70px;
	text-align: center;
	border-right: 1px dotted #000000;
	background-color: #6FC7C1;
}
#steps_sortable table td textarea{
	width: 95%;
	height: 55px;
	margin: 4px 0px 0px 4px;
	resize: none;
}
/*----------------------------------記錄方式：2-2-----------------------------------*/
#record_form h4{
	margin: 0px;
	line-height: 35px;
}
#record_save{
	margin-left: 40px;
}
/*---------------------------------研究構想表：2-2----------------------------------*/
.research_idea_table{
	width: 98%;
	padding: 5px;
	margin: 20px auto;
	border-collapse: collapse;						/*-----------表格線-----------*/
}
.research_idea_table th{
	padding: 5px;
	text-align: center;
	border: 1px solid #000000;
	background-color: #6699CC;
}
.research_idea_table td{
	padding: 20px 0px 20px 5px;
	border: 1px solid #000000;
}
.question_steps table{
	width: 70%;
	margin: 5px auto;
	border: 1px solid #000000;
	border-collapse: collapse;
}
.question_steps th{
	width: 25%;
	line-height: 70px;
	text-align: center;
	border-right: 1px dotted #000000;
	background-color: #6699CC;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：2-2----------------------------------*/
	$(".research_material, .research_steps, .research_record").click(function(){
		var question_id = $(this).parent().attr('id');			// 抓取問題ID
		var idea_type = $(this).attr('class');
		// console.log(idea_type);
		$(".question_name").html(""); 		// 一開始則清空
		$(".question_assume").html("");
		$(".question_independent").html("");
		$(".question_dependent").html("");
		$(".question_record").html("");

		$(".material_block").html("");
		$("#steps_sortable table").hide();
		$(".question_material").html("");
		$(".question_steps").html("");
		$("input[name=idea_record]").prop("checked", false);

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data: {
				type: 'get_question',
				question_id: question_id,
				action: 'stage2-2_update'
			},
			error: function(){
				alert("【警告】讀取失敗，請檢查網絡連線問題。");
				return;
			},
			success: function(data){
				for(var a in data){
					$(".question_id").val(data[a].question_id);
					$(".question_name").html(data[a].question_name);
					$(".question_assume").html(data[a].question_assume);
					$(".question_independent").html(data[a].question_independent);
					$(".question_dependent").html(data[a].question_dependent);
					$(".question_record").html(data[a].record_name);
					// 材料與工具
					if(data[a].material_name != undefined){
						$("<div class='material_choosed' id='"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<sapn class='material_deleted'>[刪除]</sapn><br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
						  "</div>").appendTo(".material_block");

						$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
						  "</div>").appendTo(".question_material");
					}
					// 研究步驟
					if(data[a].steps_name != undefined){
						// 先將提醒文字清空
						$("#steps_sortable center").hide();

						var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

						$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td><textarea class='steps_content' id='"+ a +"' value='"+ data[a].steps_name +"'>"+ data[a].steps_name +"</textarea></td></tr></table>").appendTo("#steps_sortable");

						$("<table><tr><th>步驟"+ step_arr[a-1] +"</th><td>"+ data[a].steps_name +"</td></tr></table>").appendTo(".question_steps");

						$(".research_return").attr("disabled", false);
					}
					// 記錄方式
					if(data[a].record_name != undefined){
						if(data[a].record_name == "照片"){
							$("#record1").prop("checked", true);
						}else if(data[a].record_name == "紀錄表"){
							$("#record2").prop("checked", true);
						}else if(data[a].record_name == "手繪圖"){
							$("#record3").prop("checked", true);
						}else if(data[a].record_name == "其他"){
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "照片, 紀錄表"){
							$("#record1").prop("checked", true);
							$("#record2").prop("checked", true);
						}else if(data[a].record_name == "照片, 手繪圖"){
							$("#record1").prop("checked", true);
							$("#record3").prop("checked", true);
						}else if(data[a].record_name == "照片, 其他"){
							$("#record1").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "紀錄表, 手繪圖"){
							$("#record2").prop("checked", true);
							$("#record3").prop("checked", true);
						}else if(data[a].record_name == "紀錄表, 其他"){
							$("#record2").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "手繪圖, 其他"){
							$("#record3").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "照片, 紀錄表, 手繪圖"){
							$("#record1").prop("checked", true);
							$("#record2").prop("checked", true);
							$("#record3").prop("checked", true);
						}else if(data[a].record_name == "紀錄表, 手繪圖, 其他"){
							$("#record2").prop("checked", true);
							$("#record3").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "照片, 手繪圖, 其他"){
							$("#record1").prop("checked", true);
							$("#record3").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "照片, 紀錄表, 其他"){
							$("#record1").prop("checked", true);
							$("#record2").prop("checked", true);
							$("#record4").prop("checked", true);
						}else if(data[a].record_name == "照片, 紀錄表, 手繪圖, 其他"){
							$("#record1").prop("checked", true);
							$("#record2").prop("checked", true);
							$("#record3").prop("checked", true);
							$("#record4").prop("checked", true);
						}
					}
				}
				// 點擊變因裡的[刪除]，刪除研究變因(外)
				$(".material_deleted").click(function(){
					var material_id =  $(this).parent().attr('id');			// 抓取材料ID
					// console.log(material_id);
					$.ajax({
						url  : "/co.in/science/student/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data : {
							type : "delete_material",
							material_id : material_id,
							action : "stage2-2_update"
						},
						error : function(e, status){
							alert("【系統】網路發生異常！請檢查網路連線狀況！");
							return;
						},
						success : function(data){
							$(".question_material #material"+ material_id).remove();
							return;
						}
					});
					$(this).parent().remove();
				});

				if(idea_type == "research_material"){
					$("#step2").removeClass('open').children('ul').hide();
					$("#step3").removeClass('open').children('ul').hide();
					$("#step4").removeClass('open').children('ul').hide();
					$("#step1").addClass('open').children('ul').show();
				}else if(idea_type == "research_steps"){
					$("#step1").removeClass('open').children('ul').hide();
					$("#step3").removeClass('open').children('ul').hide();
					$("#step4").removeClass('open').children('ul').hide();
					$("#step2").addClass('open').children('ul').show();
				}else if(idea_type == "research_record"){
					$("#step1").removeClass('open').children('ul').hide();
					$("#step3").removeClass('open').children('ul').hide();
					$("#step4").removeClass('open').children('ul').hide();
					$("#step3").addClass('open').children('ul').show();
				}

				$(".research_main_area").hide();
				$(".research_idea_area").show();
			}
		});
	});
	// 返回研究列表
	$(".research_return").click(function(){
		window.location.reload();
	});
/*-----------------------------------研究材料：2-2-----------------------------------*/
	// 點擊[儲存]，儲存材料與工具
	$("#material_save").click(function(){
		var question_id = $(".question_id").val();
		var add_type = $("select[name=material_type]").val();
		var add_name = $("input[name=material_name]").val();
		var add_description = $("input[name=material_description]").val();
		var add_number = $("input[name=material_number]").val();

		if(add_type == "default" || add_name == "" || add_number == ""){
			alert("【系統】請將材料填寫齊全。");
		}else{
			$(".material_block").html("");		// 一開始則清空
			$(".question_material").html("");

			$("#material_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "save_material",
					question_id : question_id,
					action : "stage2-2_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						// 材料與工具
						if(data[a].material_name != undefined){
							$("<div class='material_choosed' id='"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<sapn class='material_deleted'>[刪除]</sapn><br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
							  "</div>").appendTo(".material_block");


							$("<div class='material_choosed' id='material"+ data[a].material_id +"'>"+ data[a].material_name + " x " + data[a].material_number + " 個<br />"+"<img src='"+ data[a].material_pic +"' width='120px;' height='120px;'>"+
							  "</div>").appendTo(".question_material");
						}
					}
					// 點擊變因裡的[刪除]，刪除研究變因(內)
					$(".material_deleted").click(function(){
						var material_id =  $(this).parent().attr('id');			// 抓取材料ID
						// console.log(material_id);
						$.ajax({
							url  : "/co.in/science/student/api/php/science.php",
							type : "POST",
							async: "true",
							dataType : "json",
							data : {
								type : "delete_material",
								material_id : material_id,
								action : "stage2-2_update"
							},
							error : function(e, status){
								alert("【系統】網路發生異常！請檢查網路連線狀況！");
								return;
							},
							success : function(data){
								$(".question_material #material"+ material_id).remove();
								return;
							}
						});
						$(this).parent().remove();
					});

					// 清空
					$("select[name=material_type]").val("default");
					$("input[name=material_name]").val("");
					$("input[name=material_description]").val("");
					$("input[name=material_number]").val("1");
					$("#material_files").val("");
				}
			});
		}
	});
/*-----------------------------------研究步驟：2-2-----------------------------------*/
	var counter = 1;
	var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

	$("#steps_add").click(function(){
		// 先將提醒文字清空
		$("#steps_sortable center").hide();

		if(counter > 8){
			alert("【系統】目前最多八個步驟，有問題請洽管理員。");
			return false;
		}
		
		var newStepsDiv = $(document.createElement('div')).attr("id", 'research_steps_box' + counter);
			newStepsDiv.after().html("<table><tr><th>步驟"+ step_arr[counter-1] +"</th><td><textarea class='steps_content' id='"+ counter +"'></textarea></td></tr></table>");

			newStepsDiv.appendTo("#steps_sortable");

			counter++;					
	});

	$("#steps_remove").click(function(){
		if(counter == 1){
			$("#steps_sortable center").show();

			alert("【系統】已經沒有步驟了喔。");
			return false;
		}
		counter--;

		$("#research_steps_box" + counter).remove();
	});

	$("#steps_save").click(function(){
		var question_id = $(".question_id").val();		// 問題ID
		var steps_content = [];							// 步驟內容
		var CheckCode = 0;

		$("#steps_sortabl textarea").each(function() {	// textarea不可為空
			if($(this).val() == ""){					// 關注未填寫的textarea
			 	this.focus();
				CheckCode = 1;
			}
		});

		$(".question_steps").html("");

		if(CheckCode == "0"){
			$(".steps_content").each(function(){		// 步驟內容
				steps_content.push($(this).val());
			});

			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "save_steps",
					question_id  : question_id,
					steps_content: steps_content,
					action 	     : "stage2-2_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert("【系統】研究步驟已儲存！！");
					for(var a in data){
						// 研究步驟
						if(data[a].steps_name != undefined){
							// 先將提醒文字清空
							$("#steps_sortable center").hide();

							var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];

							$(".question_steps").append("<table><tr><th>步驟"+ step_arr[a] +"</th><td>"+ data[a].steps_name +"</td></tr></table>");

							$(".research_return").attr("disabled", false);
						}
					}
					return;
				}
			});
		}
	});

	$("#steps_sortable").sortable({
		cursor: "move",						// 拖曳游標
	});
	$("#steps_sortable").disableSelection();
/*-----------------------------------記錄方式：2-2-----------------------------------*/
	$("#record_save").click(function(){
		var question_id = $(".question_id").val();
		var record_way = '';

		$('input:checkbox[name=idea_record]:checked').each(function(){
			record_way += $(this).val() + ", ";
		});

		// 去最後標點符號
		if(record_way.length > 0) {
			record_way = record_way.substring(0, record_way.length - 2);
		}

		$("#record_form").ajaxSubmit({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "save_record",
				question_id : question_id,
				record_way : record_way,
				action : "stage2-2_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				alert("【系統】紀錄方式已儲存！！");
				for(var a in data){
					$(".question_record").html(data[a].record_name);
				}
				return;
			}
		});
	});
/*----------------------------------繳交審核表：2-2----------------------------------*/
	// 繳交作業
	$("#research_idea_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究審核表
	$("#research_check_submit").click(function(){
		var checknum = 10;				// 檢核表的題數
		var checklist = [];				// 檢核表的答案
		var check = "true";				// 是否全檢核過
		$("#research_check_form input:radio:checked").each(function(){	// 將檢核表的答案存入checkList
			checklist.push($(this).val());
			// 判斷是否有否
			if($(this).val() != '1'){
				check = "false";
			}
		});
		if(checklist.length < checknum){
			alert("【系統】檢核表尚未填寫完成。");
		}else if(check == "false"){
			alert("【系統】請重新確認上傳研究是否合格。");
		}else{
			var x = confirm("【系統】確定送出作業後，就無法修改囉？");
			if(x){
				$("#research_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_research",
						check_num : checknum,
						action : "stage2-2_update"
					},
					error : function(){
						alert("【系統】送出失敗！請再重試一次！");
						return;
					},
					success : function (data){
						alert("【系統】研究構想審核表送出成功！");
						window.location.reload();
					}
				});
			}
		}
	});
});
</script>
<section>
	<?php
		// 是否已繳交審核表
		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '2-2'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(審核表)
			$display2 = '';					// 消失(審核表)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<div class="research_main_area">
			<?php
				if($_SESSION['chief'] == '1'){ // 組長
					// 計算總問題筆數
					$q_sql = "SELECT COUNT(q_id) AS question_number FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
					$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
					$q_row = mysql_fetch_array($q_qry);
						$question = $q_row['question_number'];
					// 繳交作業 = 計算總問題筆數
					$sql = "SELECT * FROM `research_idea` WHERE `p_id` = '".$_SESSION['p_id']."' AND `type` = '0'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) == $question){
						echo "<button class='steps_submit' id='research_idea_submit'>繳交作業</button>";
					}else{
						echo "<button class='steps_submit' id='research_idea_submit' disabled>繳交作業</button>";
					}
				}
			?>
			<h1 id="title">訂定研究構想表</h1>
			<p style="text-indent: 2em;">請在討論活動內發表意見，並和同學討論該如何設計研究構想。</p>
			<?php
				$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				while($t_row = mysql_fetch_array($t_qry)){
					echo "<h3>研究題目：".$t_row['topic']."</h3>";
				}
				$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
				$qry = mysql_query($sql, $link) or die(mysql_error());
				if(mysql_num_rows($qry) > 0){
					while($row = mysql_fetch_array($qry)){
						$i_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['independent_var']."' AND `p_id`= '".$_SESSION['p_id']."'";
						$i_qry = mysql_query($i_sql, $link) or die(mysql_error());
						while($i_row = mysql_fetch_array($i_qry)){
							$independent_name = $i_row['name'];
						}
						$d_sql = "SELECT * FROM `research_question_var` WHERE `q_v_id` = '".$row['dependent_var']."' AND `p_id`= '".$_SESSION['p_id']."'";
						$d_qry = mysql_query($d_sql, $link) or die(mysql_error());
						while($d_row = mysql_fetch_array($d_qry)){
							$dependent_name = $d_row['name'];
						}
						echo "<div class='steps_box' id='".$row['q_id']."'>
								<h3>".$row['question']."</h3>
								<p>
									研究假設：".$row['assume']."<br />
									操縱變因：".$independent_name."<br />
									應變變因：".$dependent_name."<br />
								</p>
								<div class='research_material'><img src='../../model/images/project_material.png' width='50px'><br />研究材料</div>
								<div class='research_steps' onclick=''><img src='../../model/images/project_steps.png' width='50px'><br />實驗步驟</div>
								<div class='research_record'><img src='../../model/images/project_devices.png' width='50px'><br />記錄方式？</div>
							</div>";
						
					}
				}
			?>
		</div>
		<div class="research_idea_area">
			<button class="research_return" disabled>返回研究列表</button>
			<h3>研究問題：<span class="question_name"></span></h3>
			<p>研究假設：<span class="question_assume"></span></p>
			<p>操縱變因：<span class="question_independent"></span></p>
			<p>應變變因：<span class="question_dependent"></span></p>
			<div class="steps_menu">
				<ul>
					<li class='has-sub active' id="step1"><a href='#'>決定研究材料與工具</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<form id="material_form">
									<div class="material_box">
										<p style="display: none;">問題：<input type="text" class="question_id"></p>
										<p>種類：<select name="material_type">
													<option value="default">請選擇種類...</option>
													<option value="1">材料</option>
													<option value="2">工具</option>
												 </select>
										</p>
										<p>名稱：<input type="text" name="material_name"></p>
										<p>敘述：<input type="text" name="material_description"></p>
										<p>數量：<input type="number" min="0" name="material_number" value="1"> 個</p>
										<p>上傳圖片：<input type="file" name="files" id="material_files"><br/>
													 <input type="button" id="material_save" value="儲存"></p>
									</div>
									<div class="material_block">
									</div>
								</form>
							</a></li>
						</ul>
					</li>
					<li class='has-sub' id="step2"><a href='#'>決定實驗步驟</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<div id="steps_btn">
									<p style="display: none;">問題：<input type="text" class="question_id"></p>
									<button id='steps_add'>新增<br/>步驟</button>
									<button id='steps_remove'>移除<br/>步驟</button>
									<button id='steps_save'>儲存</button>
								</div>
								<div id='steps_sortable'>
									<center>(請按新增，增加步驟。)</center>
								</div>
							</a></li>
						</ul>
					</li>
					<li class='has-sub' id="step3"><a href='#'>決定紀錄方式</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<form id="record_form">
									<h4>請選擇紀錄方式：(可複選)</h4>
									<p style="display: none;">問題：<input type="text" class="question_id"></p>
									<input type="checkbox" name="idea_record" id="record1" value="照片">照片
									<input type="checkbox" name="idea_record" id="record2" value="紀錄表">紀錄表
									<input type="checkbox" name="idea_record" id="record3" value="手繪圖">手繪圖
									<input type="checkbox" name="idea_record" id="record4" value="其他">其他
									<input type="button" id="record_save" value="儲存">
								</form>
							</a></li>
						</ul>
					</li>
					<li class='has-sub' id="step4"><a href='#'>研究構想表總覽</a>
						<ul>
							<li class='sub-sub'><a href='#'>
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
							</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 2-2《訂定研究構想表》檢核表 -</h2>
				<form id="research_check_form" method="post">
					<table class="fancybox_table">
						<tr>
							<th width="80%">檢核表題目</th>
							<th width="10%"><center>是</center></th>
							<th width="10%"><center>否</center></th>
						</tr>
						<tr>
							<td>1. 我們是否列出了所有必要的實驗器材？</td>
							<td class="check_choose"><input type="radio" name="check_1" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_1" value="0" /></td>
						</tr>
						<tr>
							<td>2. 我們是否詳實地描述實驗器材的用途、材質、大小、重量以及外觀？</td>
							<td class="check_choose"><input type="radio" name="check_2" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_2" value="0" /></td>
						</tr>
						<tr>
							<td>3. 所需器材的「數量」是否明確列出？</td>
							<td class="check_choose"><input type="radio" name="check_3" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_3" value="0" /></td>
						</tr>
						<tr>
							<td>4. 如果可以，有拍下實驗器材的相片嗎？</td>
							<td class="check_choose"><input type="radio" name="check_4" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_4" value="0" /></td>
						</tr>
						<tr>
							<td>5. 是否有在實驗步驟中描述「實驗組」以及「對照組」的特性和外觀？</td>
							<td class="check_choose"><input type="radio" name="check_5" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_5" value="0" /></td>
						</tr>
						<tr>
							<td>6. 實驗步驟有沒有詳細說明每一步該怎麼做？</td>
							<td class="check_choose"><input type="radio" name="check_6" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_6" value="0" /></td>
						</tr>
						<tr>
							<td>7. 實驗步驟是否詳細，讓任何人看完步驟後都能重複相同的實驗？</td>
							<td class="check_choose"><input type="radio" name="check_7" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_7" value="0" /></td>
						</tr>
						<tr>
							<td>8. 實驗步驟中有描述如何改變「操縱變因」及如何測量它的改變量嗎？</td>
							<td class="check_choose"><input type="radio" name="check_8" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_8" value="0" /></td>
						</tr>
						<tr>
							<td>9. 實驗步驟有描述如何測量「應變變因」的變化嗎？</td>
							<td class="check_choose"><input type="radio" name="check_9" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_9" value="0" /></td>
						</tr>
						<tr>
							<td>10. 有沒有列出要重複幾次實驗(至少三次)？</td>
							<td class="check_choose"><input type="radio" name="check_10" value="1" /></td>
							<td class="check_choose"><input type="radio" name="check_10" value="0" /></td>
						</tr>
					</table>
					<div class="fancybox_mark">小提醒：送出後便不能再修改和討論了。</div>
					<input type="button" class="fancybox_btn" id="research_check_submit" value="送出">
				</form>
			</div>
		</div>
	</div>
	<div class="<?php echo $display2; ?>">
		<h1>完成的學習任務，已經提交給老師！等待老師審核。</h1>
		<p>在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button> 撰寫【個人日誌】回想自己在此階段學習到了什麼！ </p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>