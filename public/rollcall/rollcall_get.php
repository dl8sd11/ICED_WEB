<?php
date_default_timezone_set('Asia/Taipei');
include('dbacount.php');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');
	if($_GET['rollcall']){
	$today='d_'.date('Ymd');
	$sql="select " . $today . " from checkin";
	$rows=mysql_query($sql);
	$row=mysql_fetch_row($rows);
	if(empty($row[0])){
		$sql="ALTER TABLE checkin ADD " . $today . " datetime DEFAULT '0000-00-00 00:00:00'";
		mysql_query($sql);
		$sql="UPDATE checkin SET " . $today . "=NOW() WHERE studentnum='0'";
		mysql_query($sql);
		$sql="UPDATE checkin SET " . $today . "=NOW() WHERE studentnum='" . $_GET['rollcall'] . "'";
		mysql_query($sql);
		}
	else{
		$sql="UPDATE checkin SET " . $today . "=NOW() WHERE studentnum='" . $_GET['rollcall'] . "'";
		mysql_query($sql);
	}
	$sql="select studentname from checkin where studentnum='" . $_GET['rollcall'] . "'";
	$rows=mysql_query($sql);
	$row=mysql_fetch_row($rows);
	mysql_close($db);
	$checkedname=$row[0];
	$location='Location:rollcall.php?name1='.$checkedname.'&day='.$today;
	header($location);
}
else{
	$bussy_date=$_GET['bussy_date'];
	settype($bussy_date,string);
	$bussy_datetime=$bussy_date.' 00:00:00';
	$bussy_date_index=str_replace('-','',$bussy_date);
	$bussy_date_index='d_'.$bussy_date_index;
	$sql="select " . $bussy_date_index . " from checkin";
	$rows=mysql_query($sql);
	$row=mysql_fetch_row($rows);
	if(empty($row[0])){
		$sql="ALTER TABLE rollcall.checkin ADD " . $bussy_date_index . " datetime DEFAULT '0000-00-00 00:00:00'";
		mysql_query($sql);
		$sql="UPDATE rollcall.checkin SET " . $bussy_date_index . "='" . $bussy_datetime . "' WHERE studentnum='0'";
		mysql_query($sql);
		$sql="UPDATE rollcall.checkin SET " . $bussy_date_index . "='1111-11-11 11:11:11' WHERE studentname='" . $_GET['bussy_name'] . "'";
		mysql_query($sql);
		}
	else{
		$sql="UPDATE checkin SET " . $bussy_date_index . "='1111-11-11 11:11:11' WHERE studentname='" . $_GET['bussy_name'] . "'";
		mysql_query($sql);
	}
	$sql="insert into rollcall.leave (add_dt,name,leave_date,leave_cont) values (NOW(),'" . $_GET['bussy_name'] . "','" . $_GET['bussy_date'] . "','" . $_GET['bussy_cont'] . "')";
	mysql_query($sql);
	mysql_close($db);
	$location='Location:rollcall.php?name2='.$_GET['bussy_name'];
	header($location);
}
?>
