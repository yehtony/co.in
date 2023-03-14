<?php
	$page_url = '<a href="index.php">小組首頁</a> > 認識科學專題活動';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------我的網站-------------------------------------*/
#intro_field{
	width: 80%;
	padding: 20px 40px 20px 40px;
	margin: 15px auto;
	border-radius: 15px;
}
#intro_field legend{
	font-size: 45px;
	font-weight: bolder;
	text-align: center;
	border-bottom: 3px double #000000;
}
</style>
<div id="centercolumn">
	<h1 id="title">- 認識科學專題活動 -</h1>
	<fieldset id="intro_field">
		<p style="text-indent: 2em;">透過網路科展探究系統，你可以隨著老師和系統提供的階段性引導，一步步地完成科展所需的學習任務，從中學習如何發現問題，並利用科學方法進行研究，最後學會如何解決問題。</p>
		<p style="text-indent: 2em;">首先你必須對網路科展探究系統所提供的功能有基本的瞭解，才能開始利用本系統進行科展製作。在這裡，我們將科展製作分為五個製作階段，每個階段都會有子階段以及步驟式的引導，以下是科展階段的簡要說明：</p>
		<p> 一、<b>形成問題</b>：提出你想瞭解的主題，並與小組成員討論出「研究題目」。<br><br>
			二、<b>規劃</b>：利用研究題目，討論相關的變因，以及這些變因對應的研究問題。在解決研究問題之前，你需要與小組伙伴一起設計出完成實驗所需要的素材、工具、實驗步驟和問題紀錄表格。為了確保這些設計可以使用，你必須先與小組進行嘗試性研究</b>。<br /><br />
			三、<b>執行</b>：在這個階段當中，你將會利用上一個階段設計的內容，進行實驗以及紀錄，並撰寫實驗日誌。<br><br>
			四、<b>形成結論</b>：做完所有實驗之後，你必須與小組伙伴討論你們的實驗結果與研究問題的關聯性，並為你們的研究結果做出結論。<br /><br />
			五、<b>報告與展示</b>：為了要呈現你的科展作品，需要製作出作品說明書與作品海報，同時透過報告影片的錄製，來模擬參加科展競賽的情境。在最後，你必須回顧整個學習過程，進行反思，並與小組一起討論是否還有可以改進的地方。
		</p>
		<p style="text-indent: 2em;">你和小組的「科展製作進度」會顯示於畫面下側的選單中，你必須逐一提交各個階段學習任務給教師進行審核；通過審核後，你才能進行下一步的學習任務，直到完成科展作品為止。
		</p>
	</fieldset>
</div>
<?php
	include("api/php/footer.php");
?>