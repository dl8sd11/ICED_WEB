<?php
date_default_timezone_set('Asia/Taipei');
include('dbacount.php');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');
$sql="insert into leave (add_dt,name,leave_date,leave_cont) values ('NOW()','" . $_POST['bussy_name'] . "','" . $_POST['bussy_date'] . "','" . $_POST['bussy_cont'] . "')";
mysql_query($sql);
mysql_close($db);
header("Location:rollcall.php?day=".''."&name=" . $_POST['bussy_name'] . "");
?>