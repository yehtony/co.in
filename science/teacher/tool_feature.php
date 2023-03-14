<?php
	$page_url = '<a href="index.php">專題指導</a> > 系統特色介紹';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------我的網站-------------------------------------*/
#feature_field{
	width: 70%;
	padding: 10px 80px 30px 80px;
	margin: 0px auto;
	border-radius: 15px;
}
#feature_field legend{
	font-size: 45px;
	font-weight: bolder;
	text-align: center;
	border-bottom: 3px double #000000;
}
#feature_list{
	font-size: 22px;
	line-height: 50px;
}
</style>
<div id="centercolumn">
	<fieldset id="feature_field">
		<legend>歡迎來到專題探究學習系統！</legend>
		<ol id="feature_list">
			<li><b>快速的通知：</b>最新通知快速通知使用者，並通知其下一步動作。</li>
			<li><b>友善的介面：</b>快速有效率的介面，可以幫助使用者快速上手。</li>
			<li><b>有效的管理：</b>可幫助使用者快速且有效率的管理專題小組活動。</li>
			<li><b>互動式參與：</b>參與小組討論，給予最即時的回覆與答案。</li>
			<li><b>開放性探究：</b>彈性的開放式探究程度，給予學習者最適性化的教學。</li>
			<li><b>階段性任務：</b>將科展專題分為十四個階段，幫助學習者一步一步完成專題任務。</li>
			<li><b>紀錄與反思：</b>記錄使用者日誌，幫助日後反思與建議。</li>
			<li><b>合作式學習：</b>以小組的方式進行合作，建立團隊合作之精神。</li>
			<li><b>學習型鷹架：</b>給予學習者鷹架，幫助其跨越專業橫溝。</li>
			<li><b>方便的工具：</b>提供方便有效的工具，給予使用者更高的效率。</li>
		</ol>
	</fieldset>
</div>
<?php
	include("api/php/footer.php");
?>