<?php
/*物件導向-->撈取資料*/
include("class.crud.php");
global $_DB;
/*設定database連線資訊*/
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "mysql";
$DB_name = "testDB";

/*例外處理 ???
 */
try
{
	/*
	$dsn = "mysql:host=localhost;dbname=test";
	就是構造我們的DSN（數據源），看看裡面的信息包括：
	數據庫類型是mysql，主機地址是localhost，
	數據庫名稱是test，就這麼幾個信息。不同數據庫的數據源構造方式是不一樣的。
	EX:	mysql:host={$DB_host};dbname={$DB_name} 就是構造數據源頭（下面有用到）

	//$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	/*初始化一個PDO對象，
	構造函數的參數第一個就是我們的數據源，
	第二個是連接數據庫服務器的用戶，
	第三個參數是密碼。
	*/
 	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
 	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

	catch(PDOException $e)
{
 echo $e->getMessage();
}




$crud = new crud($DB_con);

?>