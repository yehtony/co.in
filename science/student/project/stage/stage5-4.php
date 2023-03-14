<style>
/*---------------------------------小組工作室：5-4----------------------------------*/
.qna_area{
	margin-left: 50px; 
}
.qna_area h1{
	text-align: center;
	font-size: 180%;
	color: red;
}
.qna_area textarea{
	width: 90%;
	height: 120px;
	resize: none; 
}
.steps_submit{
	margin-right: 50px;
}
</style>
<script>
$(function(){
/*---------------------------------小組工作室：5-4----------------------------------*/
	$("#research_submit").click(function(){
		var orderlist = [];				// 序號列表
		var answerlist = [];			// 回答列表

		$("#research_qna_form input[type=text]").each(function(){	// 將檢核表的答案存入checkList
			orderlist.push($(this).val());
		});
		
		$("#research_qna_form textarea").each(function(){	// 將檢核表的答案存入checkList
			answerlist.push($(this).val());
		});
		
		console.log(orderlist, answerlist);

		var x = confirm("【系統】確認有信心繳交答案給老師了嗎？");
		
		if(x){
			$("#research_qna_form").ajaxSubmit({
				url  : "/co.in/science/student/api/php/science.php",
				type : "POST",
				async: "true",
				dataType : "json",
				data : {
					type : "add_qna",
					orderlist : orderlist,
					answerlist : answerlist,
					action : "stage5-4_update"
				},
				error : function(){
					alert("【系統】送出失敗！！請再重試一次！！");
					return;
				},
				success : function (data) {
					alert("【系統】最後問題作業繳交成功！");
					window.location.reload();
				}
			});
		}
	});
});
</script>
<section>
	<?php
		// 是否已繳交審核表
		$display1 = '';						// 出現(初始)
		$display2 = 'steps_display';		// 消失(初始)

		$f_sql = "SELECT `stage` FROM `checklist` WHERE `p_id`= '".$_SESSION['p_id']."' AND `stage` = '5-4'";
		$f_qry = mysql_query($f_sql, $link) or die(mysql_error());
		if(mysql_num_rows($f_qry) > 0){
			$display1 = 'steps_display';	// 出現(審核表)
			$display2 = '';					// 消失(審核表)
		}
		mysql_query($f_sql, $link) or die(mysql_error());
	?>
	<div class="<?php echo $display1; ?>">
		<h1 id="title">撰寫教師提問單</h1>
		<p>請根據老師所提出的問題進行小組討論，將討論完的結果填入表單中。</p>
		<hr />
		<div class='qna_area'>
			<form id='research_qna_form'>
				<?php
					$sql = "SELECT * FROM `research_qna` WHERE `p_id`= '".$_SESSION['p_id']."'";
					$qry = mysql_query($sql, $link) or die(mysql_error());
					if(mysql_num_rows($qry) > 0){
						while($row = mysql_fetch_array($qry)){
							echo "<h3 value='".$row['question']."'>問題".$row['order']."：".$row['question']."</h3>
								  <input type='text' name='research_order' value='".$row['order']."' style='display: none;' />
								  <textarea name='research_answer'>".$row['answer']."</textarea>";
						}
						echo "<input type='button' class='steps_submit' id='research_submit' value='繳交作業' />";
					}else{
						echo "<h1>《請等待老師出完題目！！》</h1>";
					}
				?>	
			</form>
		</div>
	</div>
	<div class="<?php echo $display2; ?>">
		<h1>已完成所有的學習任務，請等待老師回應，辛苦了！！</h1>
		<p>在老師回覆審核結果前，你們可以先想想如何進行下一個階段，並到 <button><a href="/co.in/science/student/nav_diary.php">日誌專區</a></button>  撰寫【個人日誌】和【反思日誌】來回想自己在此階段學習到了什麼。^_^</p>
		<p class="steps_tips">[提醒：在每一次的小組討論過後，由組長撰寫小組工作日誌。]</p>
	</div>
</section>