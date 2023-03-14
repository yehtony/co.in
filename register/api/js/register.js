window.onload = init;
// --------------------------------------初始化--------------------------------------
function init(){
	document.getElementById("register_preview").onclick = Review;
	document.getElementById("register_cancel").onclick = Cancel;
	document.getElementById("register_submit").onclick = Register;
}
// -------------------------------------註冊檢查-------------------------------------
function Review(){
	var account 	= $("input[name=account]").val();   	// 帳號
	var password1 	= $("input[name=password1]").val();		// 密碼
	var password2	= $("input[name=password2]").val();		// 再次輸入密碼
	var last_name 	= $("input[name=last_name]").val();		// 姓
	var first_name 	= $("input[name=first_name]").val();	// 名
	var nickname	= $("input[name=nickname]").val();		// 暱稱
	var year 		= $("input[name=year]").val();			// 年
	var month 		= $("input[name=month]").val();			// 月
	var day 		= $("input[name=day]").val();			// 日
	var gender 		= $("select[name=gender]").val();		// 性別
	var identity 	= $("input[name=identity]").val();		// 身份
	var county 		= $("select[name=county]").val();		// 區域
	var school_name = $("input[name=school_name]").val();	// 學校名稱
	var school_type = $("select[name=school_type]").val();	// 學校類別
	var grade 		= $("select[name=grade]").val();		// 年級S
	var teacher 	= $("input[name=teacher]").val();		// 指導老師S
	var email 		= $("input[name=email]").val();			// 信箱

	if(account == "" || password1 == "" || password2 == "" || last_name == "" || first_name == "" || year == "" || month == "" || day == "" || school_name == "" || email == ""){
		alert('【系統】請確認資料是否確實填寫！');
	}else if(password1 != password2){
		alert('【系統】密碼與再次輸入密碼不相符！');
	}else{
		// 確認畫面
		$("input[type=text],input[type=number],input[type=email]").hide().each(function(){
			$(this).after("<span id='show'>" + this.value + "</span>");
		});
		$("select").hide().each(function(){
			$(this).after("<span id='show'>" + this[this.selectedIndex].text + "</span>");
		});
		// 隱藏preview按鈕
		$("#register_preview").hide();

		$("#register_submit").show();
		$("#register_cancel").show();
	}
}
// -------------------------------------檢查取消-------------------------------------
function Cancel(){
	$("span#show").prev().show().end().remove();
	$("#register_submit").hide();
	$("#register_cancel").hide();

	$("#register_preview").show();
}
// -------------------------------------註冊帳號-------------------------------------
function Register(){
	var account 	= $("input[name=account]").val();   	// 帳號
	var password1 	= $("input[name=password1]").val();		// 密碼
	var password2	= $("input[name=password2]").val();		// 再次輸入密碼
	var last_name 	= $("input[name=last_name]").val();		// 姓
	var first_name 	= $("input[name=first_name]").val();	// 名
	var nickname	= $("input[name=nickname]").val();		// 暱稱
	var year 		= $("input[name=year]").val();			// 年
	var month 		= $("input[name=month]").val();			// 月
	var day 		= $("input[name=day]").val();			// 日
	var gender 		= $("select[name=gender]").val();		// 性別
	var identity 	= $("input[name=identity]").val();		// 身份
	var county 		= $("select[name=county]").val();		// 區域
	var school_name = $("input[name=school_name]").val();	// 學校名稱
	var school_type = $("select[name=school_type]").val();	// 學校類別
	var grade 		= $("select[name=grade]").val();		// 年級S
	var teacher 	= $("input[name=teacher]").val();		// 指導老師S
	var email 		= $("input[name=email]").val();			// 信箱

	var check = confirm('【系統】請確認資料確實填寫完成，若確認無誤，請點選「確定」並送出註冊資料！');
	if(check == true){
		if(account.length < 4 || password1.length < 6){
			alert("【系統】帳號或密碼長度不符！");
		}else{
			$.ajax({
				url  : "/co.in/api/php/coin.php",
				type : "POST",
				data : {
					account     : account,
					password1   : password1,
					last_name   : last_name,
					first_name  : first_name,
					nickname	: nickname,
					year    	: year,
					month     	: month,
					day       	: day,
					gender      : gender,
					identity    : identity,
					county      : county,
					school_name : school_name,
					school_type : school_type,
					grade    	: grade,
					teacher    	: teacher,
					email       : email,
					action  	: 'register'
				},
				error : function(e, status){
					alert("【系統】網路發生異常！請檢查網路連線狀況！");
					//location.href = '../../';
					return;
				},
				success : function(data){
					if(data == "ERROR"){
						alert("【系統】帳號密碼長短有誤！"); // 或信箱格式不正確
					}else if(data == "IR"){
						alert('【系統】已有此帳號，請重新輸入新帳號！');
					}else if(data == "SUCCESS"){
						alert('【系統】恭喜您，帳號註冊成功！！');
						location.href = '../../';
					}
				}
			});
		}
	}
}