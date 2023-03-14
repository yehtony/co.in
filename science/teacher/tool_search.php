<?php
	$page_url = '<a href="index.php">專題指導</a> > 觀摩專題作品';
	include("api/php/header.php");
?>
<style>
/*-----------------------------------觀摩專題作品-----------------------------------*/
.search_box{
	margin-left: 5px;
}
</style>
<script>
$(function(){
/*-----------------------------------觀摩專題作品-----------------------------------*/
	$("#rearch_submit").click(function(){
		alert("A");
		// var pname = $("input[name=project_pname]").val();
		// var theme = $("select[name=project_theme]").val();
		// var mascot = $("select[name=project_mascot]").val();
		// var group_id = [];				// 專題組員(如多於三個人則可能無法參加科展)
		// var chief_id = $("select[name=project_chief]").val();

		// $("#project_form .group_block .group_choosed").each(function(){	// 最多三個人(暫)
		// 	group_id.push($(this).attr("value"));
		// });

		// if(pname == ""){
		// 	alert("【系統】請填寫小組名稱。");
		// }else if(theme == "default"){
		// 	alert("【系統】請選擇學科。");
		// }else if(chief_id == "default"){
		// 	alert("【系統】請選擇小組組長。");
		// }else if(mascot == "default"){
		// 	alert("【系統】請選擇吉祥物。");
		// }else{
		// 	// console.log(group_id);
		// 	$("#project_form").ajaxSubmit({
		// 		url  : "/co.in/science/teacher/api/php/science.php",
		// 		type : "POST",
		// 		async: "true",
		// 		dataType : "json",
		// 		data : {
		// 			type : "group_project",
		// 			group_id : group_id,
		// 			action : "project_update"
		// 		},
		// 		error : function(){
		// 			alert("【系統】創立失敗！！請再重試一次！！");
		// 			return;
		// 		},
		// 		success : function (data) {
		// 			alert("【系統】新專題小組創立成功！");
		// 			window.location.href = "./";
		// 			// window.location.reload();
		// 		}
		// 	});
		// }
	});
});
</script>
<div id="centercolumn">
	<h1>- 觀摩專題作品 -</h1>
	<div class="search_box">
		<form id="topic_check_form" method="post">
			<!-- 第一格搜尋 -->
			<input name="kw1" class="inputbox" size="30" id="searchinput1">
			<select class="selectstyle" name="c1">
				<option value="">不限欄位</option>
				<option value="title">作品名稱</option>
				<option value="kw">關鍵字</option>
			</select>
			<select class="selectstyle" name="s1">
				<option value="and">AND</option>
				<option value="or">OR</option>
				<option value="not">NOT</option>
			</select><br />
			<!-- 第二格搜尋 -->
			<input name="kw2" class="inputbox" size="30" id="searchinput2">
			<select class="selectstyle" name="c2">
				<option value="">不限欄位</option>
				<option value="title">作品名稱</option>
				<option value="kw">關鍵字</option>
			</select>
			<select class="selectstyle" name="s2">
				<option value="and">AND</option>
				<option value="or">OR</option>
				<option value="not">NOT</option>
			</select><br />
			<!-- 第三格搜尋 -->
			<input name="kw3" class="inputbox" size="30" id="searchinput3">
			<select class="selectstyle" name="c3">
				<option value="">不限欄位</option>
				<option value="title">作品名稱</option>
				<option value="kw">關鍵字</option>
			</select><br />
			<!-- 其他搜尋選項 -->
			年份 <select name="yy">
					<option value="">不限</option>
					<option>2014</option>
					<option>2013</option>
					<option>2012</option>
					<option>2011</option>
					<option>2010</option>
				</select><br />
			組別 <select name="gr">
					<option value="">不限</option>
					<option>國小組</option>
					<option>國中組</option>
					<option>高中組</option>
					<option>高職組</option>
				</select><br />
			領域 <select name="domain">
					<option value="">不限</option>
					<option>自然科</option>
					<option>數學科</option>
					<option>生活與應用科學科</option>
					<option>理化科</option>
					<option>生物及地球科學科</option>
					<option>物理科</option>
					<option>化學科</option>
					<option>生物科</option>
					<option>地球科學科</option>
					<option>機械科</option>
					<option>電子電機及資訊科</option>
					<option>化工衛工及環工科</option>
					<option>土木科</option>
					<option>農業及生物科技科</option>
					<option>應用科學科</option>
					<option>生物（生命科學）科</option>
					<option>動物學</option>
					<option>植物學</option>
					<option>微生物學</option>
					<option>生物化學</option>
					<option>醫學與健康科學</option>
					<option>工程學</option>
					<option>電腦科學</option>
					<option>環境科學</option>
					<option>物理與太空科學</option>
					<option>地球與太空科學</option> 
				</select><br />
			名次 <select name="rank">
					<option value="">不限</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
				</select><br />
			題目來源 <select name="sour">
						<option value="">不限</option>
						<option>生活</option>
						<option>新聞</option>
						<option>課堂</option>
						<option>他人研究</option>
						<option>其他</option>
					</select><br />
			研究方法 <select name="rd">
						<option value="">不限</option>
						<option>實驗法</option>
						<option>觀察法</option>
						<option>調查法</option>
					</select><br />
			<input type="button" id="rearch_submit" value="查詢">
			<input type="reset" value="清除">
		</form>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>