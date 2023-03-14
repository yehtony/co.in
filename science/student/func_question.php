<?php
	$page_url = '<a href="index.php">小組首頁</a> > 常見問題集';
	include("api/php/header.php");
?>
<style>
/*------------------------------------常見問題集------------------------------------*/
.question_menu{
	width: 90%;
	margin: 0 auto;
	clear: both;
}
.question_menu, .question_menu ul, .question_menu ul li, .question_menu ul li a{
	padding: 0;
	list-style: none;
	display: block;
}
.question_menu ul ul{									/*--------------全部展開-------------*/
	border: 1px solid #000000;
	display: none;
}
.question_menu > ul > li.active > ul{					/*-----------.active為展開-----------*/
	display: block;
}
.question_menu > ul > li > a{
	padding: 8px 22px;
	margin-top: -1px;
	margin-bottom: -1px;
	font-size: 18px;
	color: #000000;
	text-decoration: none;
	background-color: #59B791;
	border: 1px solid #000000;
	cursor: pointer;
}
.question_menu > ul > li > a:hover{
	color: #FFFFFF;
	background-color: #77DF88;
}
.question_menu ul ul li a{
	padding: 10px 22px;
	font-size: 16px;
	color: #000000;
	text-decoration: none;
	cursor: pointer;
}
</style>
<script>
$(function(){
/*------------------------------------常見問題集------------------------------------*/
	$('.question_menu li.active').addClass('open').children('ul').show();
	$('.question_menu > ul > li.active > a').css("background-color", "#77DF88;");
	$('.question_menu li.sub-sub>a').removeAttr('href');
	$('.question_menu li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');
		// list變色
		$('.question_menu > ul > li > a').css("background-color", "#59B791;");
		$(this).css("background-color", "#77DF88;");

		var element = $(this).parent('li');
		if(element.hasClass('open')){
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp(200);
		}else{
			element.addClass('open');
			element.children('ul').slideDown(200);
			element.siblings('li').children('ul').slideUp(200);
			element.siblings('li').removeClass('open');
			element.siblings('li').find('li').removeClass('open');
			element.siblings('li').find('ul').slideUp(200);
		}
	});
});
</script>
<div id="centercolumn">
	<h1 id="title">- 常見問題集 -</h1>
	<div class="question_menu">
		<ul>
			<li class='has-sub active'><a href='#'>操作型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 請問我的資料沒跑出來是發生了什麼問題？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：先整理一次網頁。</a></li>
					<li class='sub-sub'><a href='#'>2. 如果操作上遇到問題，我可以請教誰？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請先問指導老師如何解決。</a></li>
					<li class='sub-sub'><a href='#'>3. 請問我想更換專題小組怎麼辦？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請點按LOGO回到專題選擇頁面。</a></li>
					<li class='sub-sub'><a href='#'>4. 為什麼我已填寫完反思日誌，可是系統還是顯示"請完成反思日誌之撰寫"呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請點按LOGO回到專題選擇頁面，即可開始任務。</a></li>
					<li class='sub-sub'><a href='#'>5. 我的活動指引沒跳出來耶，怎麼辦？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請點選待完成任務的<img src='../model/images/project_info.png' width='25px' title='活動指引！' style='vertical-align: top;'>活動指引提示。</a>
					<li class='sub-sub'><a href='#'>6. 為什麼我的階段往前了？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：可能是老師覺得你們可以更好，所以先退回來了，請與老師討論並重新撰寫。</a></li>
					<li class='sub-sub'><a href='#'>7. 為什麼我的討論串不見了呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請先確認自己所在的階段，並按此階段顯示。</a></li>
				</ul>
			</li>
			<li class='has-sub'><a href='#'>概念型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 科學專題是什麼？該怎麼開始進行？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：全國中小學科學展覽會肩負培養學生對科學事務之基本態度、方法、觀念，提昇學生對科學研究之興趣的任務，為配合多元入學政策之推動，教育教材之改變，乃於89年底經由設計指導委員會，針對全國中小學科學展覽會進行變革，並自91年起開始實施，期能鼓勵老師學生就教材協同研究，深化生活中科學經驗，累積鄉土教材，使科學研究為一好奇心之驅使，以培養學生普遍對科學觀察研究之風氣。。</a></li>
					<li class='sub-sub'><a href='#'>2. 請問什麼人適合做科學專題呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：對科學專題有興趣之人。</a></li>
				</ul>
			</li>
			<li class='has-sub'><a href='#'>策略型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 如何進行科展專題比較快呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：沒有快速之捷徑，只有按部就班。</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>