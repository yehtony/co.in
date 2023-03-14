<?php
	$page_url = '<a href="index.php">專題指導</a> > 問題集';
	include("api/php/header.php");
?>
<style>
/*--------------------------------------Q&A集---------------------------------------*/
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
	background-color: #5EAAF7;
	border: 1px solid #000000;
	cursor: pointer;
}
.question_menu > ul > li > a:hover{
	color: #FFFFFF;
	background-color: #79BBFF;
}
.question_menu ul ul li a{
	padding: 8px 22px;
	font-size: 16px;
	color: #000000;
	text-decoration: none;
	cursor: pointer;
}
</style>
<script>
$(function(){
/*--------------------------------------Q&A集---------------------------------------*/
	$('.question_menu li.active').addClass('open').children('ul').show();
	$('.question_menu li.sub-sub>a').removeAttr('href');
	$('.question_menu li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');
		var element = $(this).parent('li');
		if (element.hasClass('open')) {
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp(200);
		}
		else {
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
			<li class='has-sub'><a href='#'>操作型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 請問我的資料沒跑出來是發生了什麼問題？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：先整理一次網頁。</a></li>
					<li class='sub-sub'><a href='#'>2. 如果操作上遇到問題，我可以請教誰？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請打客服專線 0800-268-268。</a></li>
				</ul>
			</li>
			<li class='has-sub'><a href='#'>概念型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 我沒帶過科展專題怎麼辦？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：請試著閱讀教師指導手冊之內容。</a></li>
					<li class='sub-sub'><a href='#'>2. 請問什麼人適合使用此套系統呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：對科展專題有興趣之人。</a></li>
				</ul>
			</li>
			<li class='has-sub'><a href='#'>策略型問題 + </a>
				<ul>
					<li class='sub-sub'><a href='#'>1. 如何帶小朋友做科學專題比較快呢？</a></li>
					<li style="text-indent: 25px;"><a href='#'>Q：沒有快速之捷徑，只有按部就班。</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>