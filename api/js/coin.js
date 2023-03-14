$(function(){
/*------------------------------------登入系統------------------------------------*/
	$("#login_btn").click(function(){
		if($("input[name=account]").val() == "" || $("input[name=password]").val() == ""){
			alert("【系統】請輸入帳號密碼！");
			$("input:text, input:password").val("");
		}else{
			$.ajax({
				url  : "/co.in/api/php/coin.php",
				type : "POST",
				dataType : "json",
				data : {
					identity : $("input[name=identity]:checked").val(),
					account  : $("input[name=account]").val(),
					password : $("input[name=password]").val(),
					project	 : $("select[name=project]").val(),
					action   : 'login'
				},
				error : function(e, status){
					alert("【警告】登入失敗！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					if(data.check == "illegal"){
						alert(data.announcement);
						$("input:text, input:password").val("");
						return;
					}else if(data.check == "legal"){
						// 暫不開放社會、工程專題
						if($("select[name=project]").val() == "engineer" || $("select[name=project]").val() == "society"){
							alert("【系統】系統建置中。。。近請期待，謝謝ｍ（＿　＿）ｍ");
							location.href = "./";
						}else{
							alert(data.announcement);
							location.href = data.where;
						}
						return;
					}
				}
			});
		}
	});
	// 按鍵(Enter)
	$(window).keydown(function(){
		if(event.keyCode == 13){
			if($("input[name=account]").val() == "" || $("input[name=password]").val() == ""){
				alert("【系統】請輸入帳號密碼！");
				$("input:text, input:password").val("");
			}else{
				$.ajax({
					url  : "/co.in/api/php/coin.php",
					type : "POST",
					dataType : "json",
					data : {
						identity : $("input[name=identity]:checked").val(),
						account  : $("input[name=account]").val(),
						password : $("input[name=password]").val(),
						project	 : $("select[name=project]").val(),
						action   : 'login'
					},
					error : function(e, status){
						alert("【警告】登入失敗！請檢查網路連線狀況！");
						return;
					},
					success : function(data){
						if(data.check == "illegal"){
							alert(data.announcement);
							$("input:text, input:password").val("");
							return;
						}else if(data.check == "legal"){
							alert(data.announcement);
							location.href = data.where;
							return;
						}
					}
				});
			}
		}
	});
/*------------------------------------註冊系統------------------------------------*/
	$("#register_btn").click(function(){
		window.location.href = "register/";
	});
/*------------------------------------忘記密碼------------------------------------*/
	$("#forget_btn").click(function(){
		var email = prompt("請輸入信箱帳號，以便將密碼寄信給您！", "@gmail.com");
		if(email != null){
			$.ajax({
				url  : "/co.in/api/php/coin.php",
				type : "POST",
				dataType : "json",
				data : {
					email : email,
					action: 'forget'
				},
				error : function(e, status){
					alert("【警告】連線失敗！請檢查網路連線狀況！");
					return;
				},
				success : function(data){
					alert(data);
					return;
				}
			});
		}
	});
/*------------------------------------切換專題------------------------------------*/
	$("select[name=project]").change(function(){
		var project = $("select[name=project]").val();

		if(project == 'science'){
			$("#leftcolumn").html("<img src='/co.in/model/images/science.png' width='70%'>");
		}else if(project == 'society'){
			$("#leftcolumn").html("<img src='/co.in/model/images/society.png' width='80%'>");
		}else if(project == 'engineer'){
			$("#leftcolumn").html("<img src='/co.in/model/images/engineer.png' width='75%'>");
		}
	});
});