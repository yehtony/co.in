<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>學生註冊畫面</title>
	<!-- Add icon、style CSS、jQuery library、register js-->
	<link rel="icon" href="/co.in/model/images/coin.ico" type="image/x-icon">
	<link rel="stylesheet" href="/co.in/register/api/css/style.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="/co.in/register/api/js/register.js"></script>
</head>
<body>
<div id="wrapper">
	<fieldset id="register_field">
		<legend>學生 - 註冊畫面</legend>
		<form id="register_form" method="post">
			<p><em>*</em>帳號 (4-10碼)：<input type="text" name="account" size="15" maxlength="10" onkeyup="value=value.replace(/[\W]/g,'')"/><span id="notice">( ※ 注意！不可使用/ * '' % $ # @ 空白符號。)</span></p>
			<p><em>*</em>密碼 (6-10碼)：<input type="text" name="password1" size="15" maxlength="10" onkeyup="value=value.replace(/[\W]/g,'')"/><span id="notice">( ※ 注意！不可使用/ * '' % $ # @ 空白符號。)</span></p>
			<p><em>*</em>再次輸入密碼：<input type="text" name="password2" size="15"/></p>
		<hr>
			<p><em>*</em>姓名：<input type="text" name="last_name" size="10" placeholder="姓"/>
		  			  <input type="text" name="first_name" size="10" placeholder="名"/></p>
		  	<p style="text-indent: 17px;">暱稱：<input type="text" name="nickname" size="15" placeholder="同學都叫我..."/></p>
		  	<p><em>*</em>生日：
		  		<input type="number" name="year" min="1911" max="2115" placeholder="西元年"/>年
		  		<input type="number" name="month" min="1" max="12" placeholder="月"/>月
		  		<input type="number" name="day" min="1" max="31" placeholder="日"/>日</p>
		  	<p><em>*</em>性別：
		  			<select name="gender">
						<option value="男">男</option>
						<option value="女">女</option>
					</select></p>
		  	<p style="display: none">身份：<input type="input" name="identity" value="S"/></p>
		  	<p><em>*</em>地點：<select name="county">
			            <option value="基隆市">基隆市</option>
			            <option value="台北市">台北市</option>
			            <option value="新北市">新北市</option>
			            <option value="桃園市">桃園市</option>
			            <option value="新竹市">新竹市</option>
			            <option value="新竹縣">新竹縣</option>
			            <option value="苗栗縣">苗栗縣</option>
			            <option value="台中市">台中市</option>
			            <option value="彰化縣">彰化縣</option>
			            <option value="南投縣">南投縣</option>
			            <option value="雲林縣">雲林縣</option>
			            <option value="嘉義市">嘉義市</option>
			            <option value="嘉義縣">嘉義縣</option>
			            <option value="台南市">台南市</option>
			            <option value="高雄市">高雄市</option>
			            <option value="屏東縣">屏東縣</option>
			            <option value="台東縣">台東縣</option>
			            <option value="花蓮縣">花蓮縣</option>
			            <option value="宜蘭縣">宜蘭縣</option>
			            <option value="澎湖縣">澎湖縣</option>
			            <option value="金門縣">金門縣</option>
			            <option value="連江縣">連江縣</option>
			            <option value="其他">其他</option>
           			 </select></p><!-- 可能會用在以後區域性的專題 -->

		  	 <p><em>*</em>學校：<input type="text" name="school_name" size="10" placeholder="學校名稱"/>
		  			 <select name="school_type">
						<option value="國小">國小</option>
						<option value="國中">國中</option>
						<option value="高中">高中</option>
						<option value="大學">大學</option>
					 </select></p>
			<p><em>*</em>年級：<select name="grade">
		            	<option value="1">一年級</option>
			            <option value="2">二年級</option>
			            <option value="3">三年級</option>
			            <option value="4">四年級</option>
			            <option value="5">五年級</option>
			            <option value="6">六年級</option>
		            </select></p>
		    <p><em>*</em>指導老師：<input type="text" name="teacher" size="15" placeholder="我的老師叫做..."/></p>
			<p><em>*</em>信箱：<input type="email" name="email" size="30" placeholder="我的電子信箱..."/></p>
			<input type="button" id="register_preview" value="確認">
			<input type="button" id="register_cancel" value="取消">
			<input type="button" id="register_submit" value="提交">
		</form>
  	</fieldset>
</div>
</body>
</html>