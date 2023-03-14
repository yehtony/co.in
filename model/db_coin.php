<?php
	// 連線資料庫
	$link = mysql_connect("localhost", "root", "ytwu57874") or exit(mysql_error());
	mysql_select_db("coin", $link) or die("db連線失敗");
	mysql_query("set names utf8");
?>