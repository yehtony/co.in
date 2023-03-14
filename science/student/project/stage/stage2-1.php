<style>
/*---------------------------------小組工作室：2-1----------------------------------*/
.research_box{
	float: left;
	width: 280px;
	height: 200px;
	margin: 10px 0px 0px 70px;
	padding: 0px 15px 0px 15px;
	text-align: center;
	border: 1px solid #000000;
	cursor: pointer;
}
.research_box:hover{
	background-color: #eee;
}
.research_think_area, .research_try_area, .research_edit_area{
	display: none;
}
.research_return{
	float: right;
	margin-top: -45px;
	margin-right: 120px;
	color: blue;
	cursor: pointer;
}
.research_lock_box{
	float: left;
	width: 280px;
	height: 200px;
	margin: 10px 0px 0px 70px;
	padding: 0px 15px;
	text-align: center;
	background: rgba(0,0,0,0.33);
	border: 1px solid #000000;
	cursor: not-allowed;
}
.research_lock{
	position: relative;
	top: -65px;
	left: 0px;
}
/*--------------------------------新增研究問題：2-1---------------------------------*/
#research_new_area{
	width: 620px;
}
.new_research_box{
	float: left;
	width: 250px;
	height: 220px;
	padding: 0px 15px 0px 15px;
	margin: 0px 10px 20px 10px;
	text-align: center;
	border: 1px solid #000000;
	cursor: pointer;
}
.new_research_box:hover{
	background-color: #eee;
}
/*--------------------------------先發散性思考：2-1---------------------------------*/
.research_think_table{
	width: 75%;
	margin: 10px auto;
	clear: both;
}
.research_think_table th{
	line-height: 25px;
	color: #FFFFFF;
	background-color: #6699CC;
}
.research_think_table tr:nth-child(odd){
	background-color: #99CCFF;
}
#think_form{
	width: 75%;
	margin: 10px auto;
}
#think_form input[type=button]{
	float: right;
	margin-bottom: 12px;
}
.research_think_types{
	float: left;
	width: 27%;
	padding: 5px;								/*-------解決去list:none問題--------*/
	font-weight: bolder;
	list-style-type: none;
	border: 1px solid #000000;
}
.research_think_types li{
	padding: 8px 10px;
	margin-top: 5px;
	background-color: #eee;
	border: 1px solid #000000;
	cursor: pointer;
}
.research_think_types li:hover{
	background-color: #B8BBC0;
}
.research_think_pages{
	float: left;
	width: 67%;
	height: 265px;
	padding: 5px;
	margin: 15px 5px;
	border: 1px solid #000000;
	display: none;								/*-------先消失以免爆框框--------*/
}
.research_think_pages textarea{
	width: 98%;
	height: 98%;
	resize: none; 
}
#research_think_save{
	margin-top: -45px;
}
.think_delete{
	float: right;
	margin-right: 5px;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
/*--------------------------------先嘗試性實驗：2-1---------------------------------*/
.research_devices, .research_material, .research_steps, .research_write, .research_discuss{
	float: left;
	margin-left: 30px;
	text-align: center;
	color: #808080;
	cursor: pointer;
}
.research_look, .research_upload{
	float: left;
	margin-left: 90px;
	color: #808080;
	cursor: pointer;
}
.research_look img, .research_upload img{
	vertical-align: middle;						/*-----------圖文對齊-----------*/
}
.research_choice{
	clear: both;
}
#research_try_area{
	width: 65%;
}
#research_try_area hr{
	clear: both;
}
.material_box{
	float: left;
	width: 38%;
	height: 260px;
	padding: 0px 10px;
	margin-right: 10px;
	border: 1px #AAAAAA solid;
}
.material_block{
	width: 57%;
	height: 260px;
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
#steps_btn{
	float: right;
	margin-right: 20px;
	margin-bottom: 10px;
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
#record_form h4{
	margin: 0px;
	line-height: 35px;
}
#record_save{
	margin-left: 40px;
}
#research_idea_area{
	width: 70%;
}
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
#research_vedio_form textarea{
	width: 80%;
	height: 100px;
	resize: none;
}
/*--------------------------------編輯研究問題：2-1---------------------------------*/
.var_block{
	width: 70%;
	height: 140px;
	margin-top: 5px;
	border: 1px #AAAAAA solid;
	overflow-y: scroll;
}
.var_choosed{
	padding: 3px 5px 3px 5px;
	margin: 5px 5px 5px 5px;
	font-size: 14px;
	border: rgb(165,165,165) 1px solid;
	border-radius: 3px;
}
.var_deleted{
	float: right;
	font-size: 12px;
	color: blue;
	cursor: pointer;
}
.steps_box{
	width: 230px;
	height: 230px;
}
.steps_box p{
	text-align: left;
}
.steps_box select{
	width: 75%;
}
.research_edit_save{
	float: right;
}
.steps_submit{
	margin-top: 5px;
}
#research_check_area{
	width: 500px;
}
.question_edit{
	font-size: 10px;
	color: blue;
	cursor: pointer;
}
input[name=question_name]{
	margin-top: 25px;
	display: none;
}
</style>
<script>
$(function(){
/*----------------------------------小組工作室：2-1----------------------------------*/
	$("#research_new").click(function(){
		$("#research_new_fancybox").show();
	});
	$(".research_return").click(function(){
		$(".research_think_area").hide();
		$(".research_try_area").hide();
		$(".research_edit_area").hide();
		$(".research_main_area").show();
	});
	$("#research_think").click(function(){
		$("#research_new_fancybox").hide();
		$(".research_main_area").hide();
		$(".research_think_area").show();
	});
	$("#research_try").click(function(){
		$("#research_new_fancybox").hide();
		$(".research_main_area").hide();
		$(".research_try_area").show();
	});
	$("#research_edit").click(function(){
		$(".research_main_area").hide();
		$(".research_edit_area").show();
	});
/*---------------------------------先發散性思考：2-1---------------------------------*/
	$("#page_who").show();
	$("#who").addClass('show_type').removeClass('type');
	$("#who").css("background-color", "#BDBDBD");
	// 按鈕陣列
	var type_arr = ["who", "when", "where", "what", "why", "how"];
	// 設定清單的出現和隱藏
	for(var i = type_arr.length - 1; i >= 0; i--){
		// 設定按鈕的變化
		$("#" + type_arr[i]).click(function(){
			var type_id = this.id;
			if(!$("." + type_id).hasClass("show_type")){
				$(".show_type").removeClass('show_type').addClass('type');
				$("#" + type_id).addClass('show_type').removeClass('type');
				// 按鈕變色
				$(".research_think_types li").css("background-color", "#F1FAFA");	
				$("#" + type_id).css("background-color", "#BDBDBD");
			}
		});
		// 設定畫面的出現和隱藏
		$("#" + type_arr[i]).click(function(){
			var type_id = this.id;
			if(!$("." + type_id).hasClass("show_type")){
				$(".research_think_pages").hide();
				$("#page_" + type_id).show();
				// 藏值
				$(".research_think_types").attr('value', type_id)
			}
		});
	};
	// 新增發散性思考
	$("#think_add").click(function(){
		var think_type = $(".research_think_types").attr('value');
		var think_idea = $("textarea[name=think_"+ think_type +"]").val();
		// console.log(think_type, think_idea);
		$("#think_form").ajaxSubmit({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "new_think",
				think_type : think_type,
				think_idea : think_idea,
				action : "stage2-1_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					$(".think_"+ data[a].think_type).append("<div class='think_idea"+ data[a].think_id +"' id='"+ data[a].think_id +"'>"+ data[a].think_name +"<span class='think_delete'>[刪除]</span></div>");
				}
				// 清空
				$("textarea[name=think_"+ think_type +"]").val("");
				// 繳交任務
				$("#research_think_save").attr("disabled", false);
				// 刪除發散性思考
				$(".think_delete").click(function(){
					var think_id = $(this).parent().attr('id');
					// console.log(think_id);
					// $(".think_idea"+think_id).remove();
					$.ajax({
						url  : "/co.in/science/student/api/php/science.php",
						type : "POST",
						async: "true",
						dataType : "json",
						data : {
							type : "delete_think",
							think_id : think_id,
							action : "stage2-1_update"
						},
						error : function(e, status){
							alert("【系統】網路發生異常！請檢查網路連線狀況！");
							return;
						},
						success : function(data){
							$(".think_idea"+ think_id).remove();
						}
					});
				});
			}
		});
	});
	// 刪除發散性思考(外)
	$(".think_delete").click(function(){
		var think_id = $(this).parent().attr('id');
		// console.log(think_id);
		// $(".think_idea"+think_id).remove();
		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "delete_think",
				think_id : think_id,
				action : "stage2-1_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				$(".think_idea"+ think_id).remove();
			}
		});
	});
	$("#research_think_save").click(function(){
		window.location.reload();
	});
/*--------------------------------先做嘗試性實驗：2-1--------------------------------*/
	// 第一層
	$(".research_devices, .research_material, .research_steps, .research_write").click(function(){
		$("#research_try_fancybox").show();
	});
	var number = 1;
	// 點擊[儲存]，儲存材料與工具
	$("#material_save").click(function(){
		var add_type = $("select[name=material_type]").val();
		var add_name = $("input[name=material_name]").val();
		var add_description = $("input[name=material_description]").val();
		var add_number = $("input[name=material_number]").val();
		var add_files = $("#material_files").val(); // .replace("C:\\fakepath\\", "/co.in/science/model/images/")

		if(add_files == ""){
			add_files = "/co.in/science/model/images/project_null.jpg"
		}else{
			var fReader = new FileReader();
				fReader.readAsDataURL($("#material_files")[0].files[0]);
				fReader.onloadend = function(event){
					$("#material"+ (number-1) +" img").attr("src", event.target.result);
				}
		}

		if(add_type == "default" || add_name == "" || add_number == ""){
			alert("【系統】請填寫完整的材料與工具。");
		}else{
			$("<div class='material_choosed' id='material"+ number +"'>"+ add_name + " x " + add_number + " 個<sapn class='material_deleted'>[刪除]</sapn><br />"+"<img src='"+ add_files +"' width='120px;' height='120px;'>"+
			"</div>").appendTo(".material_block");

			$("<div class='material_choosed' id='material"+ number +"'>"+ add_name + " x " + add_number + " 個<br />"+"<img src='"+ add_files +"' width='120px;' height='120px;'>"+
			"</div>").appendTo(".question_material");

			// 填完後，清空值
			$("select[name=material_type]").val("default");
			$("input[name=material_name]").val("");
			$("input[name=material_description]").val("");
			$("input[name=material_number]").val("1");

			number++;
		}

		// 點擊變因裡的[刪除]，刪除研究變因(內)
		$(".material_deleted").click(function(){
			$(this).parent().remove();

			$(".question_material #material"+ (number-1)).remove();

			number--;
		});
	});

	var counter = 1;
	var step_arr = ["一", "二", "三", "四", "五", "六", "七", "八"];
	// 步驟儲存
	$("#steps_add").click(function(){
		// 先將提醒文字清空
		$("#steps_sortable center").hide();

		if(counter > 8){
			alert("【系統】目前最多八個步驟，有問題請洽管理員。");
			return false;
		}
		
		var newStepsDiv1 = $(document.createElement('div')).attr("id", 'research_steps_box' + counter);
			newStepsDiv1.after().html("<table><tr><th>步驟"+ step_arr[counter-1] +"</th><td><textarea class='steps_content' id='steps"+ counter +"'></textarea></td></tr></table>");

			newStepsDiv1.appendTo("#steps_sortable");

		var newStepsDiv2 = $(document.createElement('div')).attr("id", 'research_steps_box' + counter);
			newStepsDiv2.after().html("<table><tr><th>步驟"+ step_arr[counter-1] +"</th><td><span id='read_steps"+ counter +"'></span></td></tr></table>");

			newStepsDiv2.appendTo(".question_steps");

			counter++;

		$(".steps_content").blur(function(){
			var steps_id = $(this).attr("id");

			$("#read_"+ steps_id).html($(this).val());
		});
	});
	$("#steps_remove").click(function(){
		if(counter == 1){
			$("#steps_sortable center").show();

			alert("【系統】已經沒有步驟了喔。");
			return false;
		}
		counter--;

		$("#steps_sortable #research_steps_box" + counter).remove();
		$(".question_steps #research_steps_box" + counter).remove();
	});
	
	$("#steps_sortable").sortable({
		cursor: "move",						// 拖曳游標				
	});
	$("#steps_sortable").disableSelection();

	$('input:checkbox[name=idea_record]').change(function(){
		var recordlist = '';

		$('input:checkbox[name=idea_record]:checked').each(function(){
			recordlist += $(this).val() + ", ";
		});

		if(recordlist.length > 0) {
			recordlist = recordlist.substring(0, recordlist.length - 2);
		} // 去最後標點符號

		$(".question_record").html(recordlist);
	});

	$("#research_try_submit").click(function(){
		$("#research_try_fancybox").hide();
	});
	// 下一步，試做實驗
	$("#research_idea_next").click(function(){
		$("#try1").removeClass('open');
		$("#try1 ul").slideUp(200);
		$("#try2").addClass('open');
		$("#try2 ul").slideDown(200);
	});
	// 第二層
	$(".research_look").click(function(){
		$("#research_idea_fancybox").show();
	});
	$("#research_idea_submit").click(function(){
		$("#research_idea_fancybox").hide();
	});
	$(".research_upload").click(function(){
		$("#research_vedio_fancybox").show();
	});
	$("#research_vedio_submit").click(function(){
		$("#research_vedio_fancybox").hide();
	});
	// 下一步，討論活動
	$("#research_choice_next").click(function(){
		$("#try2").removeClass('open');
		$("#try2 ul").slideUp(200);
		$("#try3").addClass('open');
		$("#try3 ul").slideDown(200);
	});
	
	$(".research_discuss").click(function(){
		window.location.href = "../project/discuss.php?stage=2-1";
	});
/*---------------------------------編輯研究問題：2-1---------------------------------*/
	// 點擊[加入]，加入研究變因
	$(".var_add").click(function(){
		var add_name = $(".research_var").val();				// 新增變因

		if(add_name != ""){
			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_var",
					add_name : add_name,
					action : "stage2-1_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						// 及時加入操縱變因
						$("<option class='var"+ data[a].var_id +"' value='"+ data[a].var_id +"'>"+ data[a].var_name +"</option>").appendTo(".question_independent");
						// 及時加入應變變因
						$("<option class='var"+ data[a].var_id +"' value='"+ data[a].var_id +"'>"+ data[a].var_name +"</option>").appendTo(".question_dependent");
					}
				}
			});

			$("<div class='var_choosed' value='"+ add_name +"'>"+ add_name +
						"<sapn class='var_deleted'>[刪除]</sapn>"+
						"</div>").appendTo(".var_block");
			$(".research_var").val("");	
		}
		// 點擊[刪除]，刪除研究變因
		$(".var_deleted").click(function(){
			var delete_name = $(this).parent().attr('value');				// 刪除變因

			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "delete_var",
					delete_name : delete_name,
					action : "stage2-1_update"
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					for(var a in data){
						// 刪除變因
						$(".var"+ data[a].var_id).remove();
					}
					return;
				}
			});
			$(this).parent().remove();
		});
	});
	// 點擊[刪除]，刪除研究變因
	$(".var_deleted").click(function(){
		var delete_name = $(this).parent().attr('value');				// 刪除變因

		$.ajax({
			url  : "/co.in/science/student/api/php/science.php",
			type : "POST",
			async: "true",
			dataType : "json",
			data : {
				type : "delete_var",
				delete_name : delete_name,
				action : "stage2-1_update"
			},
			error : function(e, status){
				alert("【系統】網路發生異常！請檢查網路連線狀況！");
				return;
			},
			success : function(data){
				for(var a in data){
					// 刪除變因
					$(".var"+ data[a].var_id).remove();
				}
			}
		});
		$(this).parent().remove();
	});
	// 編輯研究問題edit
	$(".question_edit").click(function(){
		$(this).hide();
		$(this).parent().hide();
		$(this).parent().next().show();
		$(this).parent().next().select();
	});
	$("input[name='question_name']").blur(function(){
		var question_id = $(this).parent().attr('id');

		if($.trim(this.value) == ''){
			this.value = (this.defaultValue ? this.defaultValue : '');
		}else{
			$(this).prev().html(this.value + "<span class='question_edit'>[編輯]</span>");

			$(".question_edit").click(function(){
				$(this).hide();
				$(this).parent().hide();
				$(this).parent().next().show();
				$(this).parent().next().select();
			});

			$.ajax({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data: {
					type: 'save_name',
					question_id : question_id,
					question_name : this.value,
					action: 'stage2-1_update'
				},
				error: function(){
					alert("【警告】讀取失敗，請檢查網絡連線問題。");
					return;
				},
				success: function(data){
					return;
				}
			});
		}
		$(this).hide();
		$(this).prev().show();
		$(this).prev().children().show();		
	});
	$("input[name='question_name']").keypress(function(event){
		if(event.keyCode == '13'){
			if($.trim(this.value) == ''){
				this.value = (this.defaultValue ? this.defaultValue : '');
			}else{
				$(this).prev().html(this.value + "<span class='question_edit'>[編輯]</span>");

				$(".question_edit").click(function(){
					$(this).hide();
					$(this).parent().hide();
					$(this).parent().next().show();
					$(this).parent().next().select();
				});

				$.ajax({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data: {
						type: 'save_name',
						question_id : question_id,
						question_name : this.value,
						action: 'stage2-1_update'
					},
					error: function(){
						alert("【警告】讀取失敗，請檢查網絡連線問題。");
						return;
					},
					success: function(data){
						return;
					}
				});
			}
			$(this).hide();
			$(this).prev().show();
			$(this).prev().children().show();
		}
	});
	// 儲存研究問題
	$(".research_edit_save").click(function(){
		var question_id = $(this).parent().parent().attr('id');
		// console.log(question_id);
		if($("#question_form"+ question_id +" input[type=text]").val() == ""){
			alert("【系統】請填寫研究假設。");
		}else if($("#question_form"+ question_id +" select[name=question_independent"+ question_id +"]").val() == "default" || $("#question_form"+ question_id +" select[name=question_dependent"+ question_id +"]").val() == "default"){
			alert("【系統】請選擇研究變因。");
		}else{
			var x = confirm("【系統】確認儲存變因就無法更改囉？");
			if(x){
				$("#question_form"+ question_id).ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "save_question",
						question_id : question_id,
						action : "stage2-1_update"
					},
					error : function(e, status){
						alert("【系統】網路發生異常！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						alert("【系統】研究問題與變數已儲存！！");
						$("input[name=research_edit_save"+ question_id +"]").attr("disabled", true);
						$("#research_edit_submit").attr("disabled", false);
						return;
					}
				});
			}
		}
	});
/*----------------------------------繳交審核表：2-1----------------------------------*/
	// 繳交研究問題
	$("#research_edit_submit").click(function(){
		$("#research_check_fancybox").show();
	});
	// 繳交研究問題審核表
	$("#research_check_submit").click(function(){
		var checknum = 8;				// 檢核表的題數
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
			alert("【系統】請重新確認上傳研究問題是否合格。");
		}else{
			var x = confirm("【系統】確定送出研究問題後，就無法修改囉？");
			if(x){
				$("#research_check_form").ajaxSubmit({
					url  : "/co.in/science/student/api/php/science.php",
					type : "POST",
					async: "true",
					dataType : "json",
					data : {
						type : "check_question",
						check_num : checknum,
						action : "stage2-1_update"
					},
					error : function(){
						alert("【系統】送出失敗！！請再重試一次！！");
						return;
					},
					success : function (data){
						alert("【系統】研究問題審核表已送出成功！");
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
		$lock = 'research_lock_box';		// 鎖住
		$edit = '';							// 不可編輯
		$display = '';						// 出現(初始)

		$q_sql = "SELECT `q_id` FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
		$q_qry = mysql_query($q_sql, $link) or die(mysql_error());
		if(mysql_num_rows($q_qry) > 0){
			$lock = 'research_box';			// 解開
			$edit = 'research_edit';		// 可編輯
			$display = 'steps_display';		// 消失
		}
		mysql_query($q_sql, $link) or die(mysql_error());

		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '2-1'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(初始)
			$display2 = '';					// 消失(初始)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<div class="research_main_area">
			<h1 id="title">提出研究問題</h1>
			<p style="text-indent: 2em;">接下來你們要<b><u>提出研究問題</u></b>作為你們研究的目標，你們可以「先做實驗、再提問題」，也可以「先做發散性思考、再提出研究問題」。</p>
			<?php
				$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				while($t_row = mysql_fetch_array($t_qry)){
					echo "<h3>研究題目：".$t_row['topic']."</h3>";
				}
			?>
			<div class="research_box" id="research_new">
				<h2>新增研究問題</h2>
				<p>可選擇先做<span title="配合5W1H來訓練自己如何發散思考！">「發散性思考」</span>，還是先做<span title="可以先做嘗試性實驗，來判斷此問題是否可以？">「嘗試性實驗」</span>，來尋找出適合的研究問題。</p>
			</div>
			<div class="<?php echo $lock; ?>" id="<?php echo $edit; ?>">
				<h2>編輯研究問題</h2>
				<p>你所新增的研究問題都在這裡！</p>
				<div class='research_lock <?php echo $display; ?>' <?php echo $edit; ?>><img src='../../model/images/project_lock.png' width='120px'></div>
			</div>
			<div class="research_lock_box">
				<h2>撰寫研究構想表</h2>
				<p>你所想到的研究問題都放在這裡，為它們撰寫研究步驟、研究器材以及記錄表格吧！</p>
				<div class='research_lock' style="top: -110px;"><img src='../../model/images/project_lock.png' width='120px'></div>
			</div>
			<div class="research_lock_box">
				<h2>實驗預試</h2>
				<p>決定好研究構想之後，實際測試看看這個構想能不能順利做實驗。</p>
				<div class='research_lock' style="top: -90px;"><img src='../../model/images/project_lock.png' width='120px'></div>
			</div>
		</div>
		<div class="fancybox_box" id="research_new_fancybox">
			<div class="fancybox_area" id="research_new_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 新增研究問題 -</h2>
				<div class="new_research_box" id="research_think">
					<h3>先做發散性思考</h3>
					先進行發散性思考，再依據你們想到的點子提出研究問題。如果你的研究主題屬於自然科學類（例如：青苔、蜜蜂、土壤），建議先做發散性思考。
				</div>
				<div class="new_research_box" id="research_try">
					<h3>先做嘗試性實驗</h3>
					先做嘗試性實驗，再依據嘗試實驗的結果找出值得研究的問題。如果你的研究主題是屬於工程類（例如：船、電池、車），建議先做嘗試實驗。
				</div>
			</div>
		</div>
		<div class="research_think_area">
			<?php
				$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				while($t_row = mysql_fetch_array($t_qry)){
					echo "<h3>研究題目：".$t_row['topic']."</h3>";
				}
			?>
			<span class="research_return">[返回]</span>
			<?php
				if($_SESSION['chief'] == '1'){ // 組長
					$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						echo "<button class='steps_next' id='research_think_save'>繳交此階段任務</button>";
					}else{
						echo "<button class='steps_next' id='research_think_save' disabled>繳交此階段任務</button>";
					}
				}
			?>
			<p style="text-indent: 2em;">利用發散性思考(5W1H)的方式，建立思考的模式，以想出適合的<b><u>研究問題</u></b>。</p>
			<table class="research_think_table">
				<tr>
					<th width="25%">分類</th><th width="75%">範例(以蝴蝶為例)</th>
				</tr>
				<tr>
					<td>與誰有關（Who）</td><td>誰會把蝴蝶當作食物？</td>
				</tr>
				<tr>
					<td>什麼時候（When）</td><td>蝴蝶會在晚上活動嗎？</td>
				</tr>
				<tr>
					<td>什麼地點（Where）</td><td>蝴蝶會選擇在哪裡產卵？</td>
				</tr>
				<tr>
					<td>什麼事物（What）</td><td>蝴蝶吃什麼？</td>
				</tr>
				<tr>
					<td>為什麼（Why）</td><td>為什麼蝴蝶會忽高忽低的飛？</td>
				</tr>
				<tr>
					<td>如何（How）</td><td>蝴蝶如何保護自己？</td>
				</tr>
			</table>
			<form id="think_form">
				<ul class="research_think_types" value='who'>
					<li id="who">與誰有關（Who）</li>
					<li id="when">什麼時候（When）</li>
					<li id="where">什麼地點（Where）</li>
					<li id="what">什麼事物（What）</li>
					<li id="why">為什麼（Why）</li>
					<li id="how">如何（How）</li>
				</ul>
				<div id="page_who" class="research_think_pages">
					<textarea name="think_who" placeholder="與誰有關（Who）"></textarea>
				</div>
				<div id="page_when" class="research_think_pages">
					<textarea name="think_when" placeholder="什麼時候（When）"></textarea>
				</div>
				<div id="page_where" class="research_think_pages">
					<textarea name="think_where" placeholder="什麼地點（Where）"></textarea>
				</div>
				<div id="page_what" class="research_think_pages">
					<textarea name="think_what" placeholder="什麼事物（What）"></textarea>
				</div>
				<div id="page_why" class="research_think_pages">
					<textarea name="think_why" placeholder="為什麼（Why）"></textarea>
				</div>
				<div id="page_how" class="research_think_pages">
					<textarea name="think_how" placeholder="如何（How）"></textarea>
				</div>
				<input type="button" name="think_add" id="think_add" value="新增">
			</form>
			<table class="research_think_table">
				<tr>
					<th width="25%">分類</th><th width="75%">研究問題</th>
				</tr>
				<tr>
					<td>與誰有關（Who）</td>
					<td class="think_who">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'who'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td>什麼時候（When）</td>
					<td class="think_when">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'when'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td>什麼地點（Where）</td>
					<td class="think_where">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'where'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>	
					</td>
				</tr>
				<tr>
					<td>什麼事物（What）</td>
					<td class="think_what">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'what'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td>為什麼（Why）</td>
					<td class="think_why">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'why'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td>如何（How）</td>
					<td class="think_how">
					<?php
						$sql = "SELECT * FROM `research_question` WHERE `p_id` = '".$_SESSION['p_id']."' AND `5W1H` = 'how'";
						$qry = mysql_query($sql, $link) or die(mysql_error());
						if(mysql_num_rows($qry) > 0){
							while($row = mysql_fetch_array($qry)){
								echo "<div class='think_idea".$row['q_id']."' id='".$row['q_id']."'>".$row['question']."<span class='think_delete'>[刪除]</span></div>";
							}
						}
					?>
					</td>
				</tr>
			</table>
		</div>
		<div class="research_try_area">
			<?php
				$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				while($t_row = mysql_fetch_array($t_qry)){
					echo "<h3>研究題目：".$t_row['topic']."</h3>";
				}
			?>
			<span class="research_return">[返回前一頁]</span> 
			<div class="steps_menu">
				<ul>
					<li class='has-sub active' id='try1'><a href='#'>第一步：撰寫嘗試實驗構想表</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<p style="text-indent: 2em;">在進行嘗試性實驗之前，先簡略地說明你們的實驗規劃吧！在這個階段你們要說明會用到哪些<b>研究器材</b>、<b>實驗步驟</b>是如何，並說明你要<b>紀錄哪些資料</b>？</p>
								<div class='research_devices'><img src='../../model/images/project_devices.png' width='150px'><br />研究器材</div>
								<div class='research_material'><img src='../../model/images/project_material.png' width='150px'><br />研究材料</div>
								<div class='research_steps'><img src='../../model/images/project_steps.png' width='150px'><br />實驗步驟</div>
								<div class='research_write'><img src='../../model/images/project_rewrite.png' width='150px'><br />如何記錄？</div>
								<button class="steps_next" id="research_idea_next">下一步，試做實驗</button>
							</a></li>
						</ul>
					</li>
					<li class='has-sub' id='try2'><a href='#'>第二步：紀錄試做結果</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<p style="text-indent: 2em;">規劃完嘗試實驗構想之後，請你們照著構想做幾次嘗試實驗，並在這個步驟中記錄你們的嘗試實驗結果。</p>
								<div class='research_look'><img src='../../model/images/project_review.png' width='80px'>觀看嘗試實驗構想</div>
								<div class='research_upload'><img src='../../model/images/project_cupload.png' width='80px'>上傳實驗影片或紀錄</div>
								<p class="research_choice">
									<label>你們實驗成功嗎？</label>
									<select name="research_result">
										<option value="default">請選擇....</option>
										<option value="success">成功</option>
										<option value="failure">沒有成功</option>
									</select>
								</p>
								<button class="steps_next" id="research_choice_next">下一步，討論活動</button>
							</a></li>
						</ul>
					</li>
					<li class='has-sub' id='try3'><a href='#'>第三步：討論活動</a>
						<ul>
							<li class='sub-sub'><a href='#'>
								<p style="text-indent: 2em;">在先前的活動中，你們已經試著做過一次嘗試實驗了，也紀錄了嘗試實驗的數據與結果。</p>
								<p style="text-indent: 2em;">如果嘗試性實驗<b><u>成功</u></b>了，請你們思考「這個實驗是否可以改良？」「改良、改進實驗的時候，我們會運用到哪些科學原理或知識？」</p>
								<p style="text-indent: 2em;">如果嘗試性實驗<b><u>失敗</u></b>了，請你們思考「這個實驗失敗的原因是什麼？」「我們是否可以改良這個實驗呢？」「改良、改進實驗的時候，我們會運用到哪些科學原理或知識？」</p>
								<div class='research_discuss'><img src='../../model/images/project_discuss.png' width='120px'><br />進入討論區討論</div>
								<button class="steps_next" id="research_try_save">繳交此階段任務</button>
							</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="fancybox_box" id="research_try_fancybox">
			<div class="fancybox_area" id="research_try_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 新增嘗試性實驗 -</h2>
				<form>
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
									 <input type="button" id="material_save" value="加入"></p>
					</div>
					<div class="material_block">
					</div>
				</form>
				<hr />
					<div id="steps_btn">
						<p style="display: none;">問題：<input type="text" class="question_id"></p>
						<button id='steps_add'>新增<br/>步驟</button>
						<button id='steps_remove'>移除<br/>步驟</button>
						<!-- <button id='steps_save'>儲存</button> -->
					</div>
					<div id='steps_sortable'>
						<center>(請按新增，增加步驟。)</center>
					</div>
				<hr />
					<form id="record_form">
						<h4>請選擇紀錄方式：(可複選)</h4>
						<p style="display: none;">問題：<input type="text" class="question_id"></p>
						<input type="checkbox" name="idea_record" id="record1" value="照片">照片
						<input type="checkbox" name="idea_record" id="record2" value="紀錄表">紀錄表
						<input type="checkbox" name="idea_record" id="record3" value="手繪圖">手繪圖
						<input type="checkbox" name="idea_record" id="record4" value="其他">其他
						<!-- <input type="button" id="record_save" value="儲存"> -->
					</form>
				<input type="button" class="fancybox_btn" id="research_try_submit" value="確定">
			</div>
		</div>
		<div class="fancybox_box" id="research_idea_fancybox">
			<div class="fancybox_area" id="research_idea_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 觀看研究構想 -</h2>
				<table class="research_idea_table">
					<tr>
						<th width="38%">研究問題</th>
						<th width="38%">研究假設</th>
						<th width="12%">操縱變因</th>
						<th width="12%">應變變因</th>
					</tr>
					<tr>
						<td align='center'><input type="text" class="question_name" size="38" /></td>
						<td align='center'><input type="text" class="question_assume" size="38" /></td>
						<td align='center'><input type="text" class="question_independent" size="8" /></td>
						<td align='center'><input type="text" class="question_dependent" size="8" /></td>
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
				<input type="button" class="fancybox_btn" id="research_idea_submit" value="確定">
			</div>
		</div>
		<div class="fancybox_box" id="research_vedio_fancybox">
			<div class="fancybox_area" id="research_vedio_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 上傳報告影片 -</h2>
				<form id="research_vedio_form">
					<label>嵌入：</label>
					<textarea name="research_vedio" placeholder="例如：<iframe width='640' height='360' src='https://www.youtube.com/embed/R8lEukwIdyo' frameborder='0' allowfullscreen></iframe>"></textarea>
					<input type="button" class="fancybox_btn" id="research_vedio_submit" value="確定">
				</form>
			</div>
		</div>
		<div class="research_edit_area">
			<?php
				$t_sql = "SELECT `topic` FROM `research_topic` WHERE `p_id`= '".$_SESSION['p_id']."' AND `research` = '1'";
				$t_qry = mysql_query($t_sql, $link) or die(mysql_error());
				while($t_row = mysql_fetch_array($t_qry)){
					echo "<h3>研究題目：".$t_row['topic']."</h3>";
				}
			?>
			<span class="research_return">[返回前一頁]</span> 
			<div class="steps_menu">
				<ul>
					<li class='has-sub active'><a href='#'>新增研究變因</a>
						<ul>
							<li class='sub-sub'><a href='#'>
							<input type="text" class="research_var" size="30" placeholder="輸入與此研究題目有關的變因...">
							<input type="button" class="var_add" value="加入變因">
							<div class="var_block">
							<?php
								$sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<div class='var_choosed' value='".$row['name']."'>".$row['name'].
												"<sapn class='var_deleted'>[刪除]</sapn>".
											 "</div>";
									}
								}
							?>
							</div>
							</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href='#'>編輯研究問題</a>
						<ul>
							<li class='sub-sub'><a href='#'>
							<?php
								$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."'";
								$qry = mysql_query($sql, $link) or die(mysql_error());
								if(mysql_num_rows($qry) > 0){
									while($row = mysql_fetch_array($qry)){
										echo "<div class='steps_box' id='".$row['q_id']."'>
												<h3 class='question_name'>".$row['question']."<span class='question_edit'>[編輯]</span></h3>
												<input type='text' name='question_name' value='".$row['question']."' size='28' />
												<form id='question_form".$row['q_id']."'>
													<p>
														研究假設：<input type='text' name='question_assume".$row['q_id']."' size='28' value='".$row['assume']."' placeholder='請輸入研究假設...'>
													</p>";
												echo "<p><select name='question_independent".$row['q_id']."' class='question_independent'>";
												// 判斷是否已有操縱變因
												if($row['independent_var'] == "0"){	// 未存在操縱變數
													echo "<option value='default'>請選擇操縱變因...</option>";

													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
												}else{	// 未存在操縱變數
													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."' AND `q_v_id` = '".$row['independent_var']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."' AND `q_v_id` != '".$row['independent_var']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
												}
												echo "</select>";
												echo "<select name='question_dependent".$row['q_id']."' class='question_dependent'>";
												// 判斷是否已有應變變因
												if($row['dependent_var'] == "0"){	// 未存在操縱變數
													echo "<option value='default'>請選擇應變變因...</option>";

													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
												}else{	// 未存在操縱變數
													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."' AND `q_v_id` = '".$row['dependent_var']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
													$v_sql = "SELECT * FROM `research_question_var` WHERE `p_id`= '".$_SESSION['p_id']."' AND `q_v_id` != '".$row['dependent_var']."'";
													$v_qry = mysql_query($v_sql, $link) or die(mysql_error());
													if(mysql_num_rows($qry) > 0){
														while($v_row = mysql_fetch_array($v_qry)){
															echo "<option class='var".$v_row['q_v_id']."' value='".$v_row['q_v_id']."'>".$v_row['name']."</option>";
														}
													}
														mysql_query($v_sql, $link) or die(mysql_error());
												}
												echo "</select></p>";
												
											// 判斷是否已儲存
											if($row['independent_var'] != "0" && $row['dependent_var'] != "0"){
												echo "<input type='button' name='research_edit_save".$row['q_id']."' class='research_edit_save' value='儲存' disabled></p></div></form>";
											}else{
												echo "<input type='button' name='research_edit_save".$row['q_id']."' class='research_edit_save' value='儲存'></p></div></form>";
											}
									}
								}
								mysql_query($sql, $link) or die(mysql_error());

								if($_SESSION['chief'] == '1'){ // 組長
									// 繳交作業
									$sql = "SELECT * FROM `research_question` WHERE `p_id`= '".$_SESSION['p_id']."' AND `assume` IS NOT NULL AND `independent_var` != '0' AND `dependent_var` != '0'";
									$qry = mysql_query($sql, $link) or die(mysql_error());
									if(mysql_num_rows($qry) > 0){
										echo "<button class='steps_submit' id='research_edit_submit'>繳交作業</button>";
									}else{
										echo "<button class='steps_submit' id='research_edit_submit' disabled>繳交作業</button>";
									}
								}
							?>
							</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="fancybox_box" id="research_check_fancybox">
			<div class="fancybox_area" id="research_check_area">
				<div class="fancybox_cancel"><img src="../../model/images/project_close.png" width="20px"></div>
				<h2>- 2-1《新增研究問題》檢核表 -</h2>
				<form id="research_check_form" method="post">
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
					<div class="fancybox_mark">小提醒：送出後便不能再修改和討論了。</div>
					<input type="button" class="fancybox_btn" id="research_check_submit" value="送出">
				</form>
			</div>
		</div>
	</div>
	<div class="<?php echo $display2; ?>">
		<h1>完成的學習任務，已經提交給老師！等待老師審核。</h1>
		<p style="text-indent: 2em;">在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button> 撰寫【個人日誌】回想自己在此階段學習到了什麼！ </p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>
