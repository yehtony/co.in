<?php
	$page_url = '<a href="index.php">專題指導</a> > 我的專案';
	include("api/php/header.php");
?>
<style>
/*-------------------------------------我的專案-------------------------------------*/
.share_box{
	float: left;
	margin: 10px 30px 10px 30px;
}
.share_info{
	padding: 5px 10px;
	width: 250px;
	height: 220px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
}
.share_function{
	position: relative;
	top: -1px;
	left: 8px;
	width: 230px;
	height: 160px;
	padding: 12px 10px 0px 10px;
	line-height: 35px;
	text-align: center;
	background-color: #F1FAFA;
	border: 1px solid #000000;
	display: none;
}
</style>
<div id="centercolumn">
	<div class='share_box'>
		<div class='share_info'>
			<h2>桃園市中小學科展</h2>
			<div>預計完工時間：2016.07.17</div>
			<br/>
			<div>專案發起人：吳沺沺</div>
			<div>專案成員：德懷公</div>
		</div>
		<div class='share_function'>
			<div><a href='#'>議題討論</a></div>
			<div><a href='#'>成果暫存</a></div>
			<div><a href='#'>共享資源</a></div>
		</div>
	</div>
</div>
<?php
	include("api/php/footer.php");
?>

