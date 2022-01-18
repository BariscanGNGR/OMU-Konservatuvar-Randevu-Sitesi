<?php
	$sql = mysqli_connect("localhost","kullanıcı adi","sifre") or die("!!! Veritabani hatası !!!");//kullanici adi sifre
	mysqli_select_db($sql,"sql database");
	mysqli_set_charset($sql,"utf8");
?>
