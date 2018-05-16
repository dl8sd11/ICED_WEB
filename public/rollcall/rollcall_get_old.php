<?php
date_default_timezone_set('Asia/Taipei');
$db = @mysql_connect('localhost','root','1q1q1q1');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');
include('lastday_compare.php');
	if($_GET[rollcall]){
	if($newday==$curday){
		$sql="UPDATE checkin SET day" . $daynum . "=NOW() WHERE studentnum='" . $_GET[rollcall] . "'";
		mysql_query($sql);
		}
	else{
		settype($daynum,integer);
		$daynum=$daynum+1;
		settype($daynum,string);
		$sql="ALTER TABLE checkin ADD day" . $daynum . " datetime DEFAULT '0000-00-00 00:00:00'";
		mysql_query($sql);
		$sql="UPDATE checkin SET day" . $daynum . "=NOW() WHERE studentnum='h000000'";
		mysql_query($sql);
		$sql="UPDATE checkin SET day" . $daynum . "=NOW() WHERE studentnum='" . $_GET[rollcall] . "'";
		mysql_query($sql);
	}
	mysql_close($db);
	header('Location:rollcall.php');
}
else{
	$bussy_time=$_GET[bussy_date];
	settype($bussy_time,string);
	$bussy_time=$bussy_time.' 23:59:59';
	//$lastday=substr($lastday,0,10);
	$day=(strtotime($bussy_time)-$lastday)/(60*60*24);
	echo '1/'.strtotime($bussy_time).'  ';
	echo '2/'.$lastday.'  ';
	echo '3/'.$day;
	/*settype($daynum,integer);
	$daynum=$daynum+$day;
	settype($daynum,string);
	$sql="ALTER TABLE checkin ADD day" . $daynum . " datetime DEFAULT '0000-00-00 00:00:00'";
	mysql_query($sql);
	$sql="UPDATE checkin SET day" . $daynum . "='1111-11-11 11:11:11' WHERE studentnum='h000000'";
	mysql_query($sql);
	$sql="UPDATE checkin SET day" . $daynum . "='1111-11-11 11:11:11' WHERE studentname='" . $_GET[bussy_name] . "'";
	mysql_query($sql);
	$sql="insert into rollcall.leave (add_dt,name,leave_date,leave_cont) values (NOW(),'" . $_GET[bussy_name] . "','" . $_GET[bussy_date] . "','" . $_GET[	bussy_cont] . "')";
	mysql_query($sql);*/
	mysql_close($db);
	$location='Location:rollcall.php?name='.$_GET[bussy_name];
	//header("$location");
}
?>
