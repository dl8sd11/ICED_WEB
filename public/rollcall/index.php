<?php
date_default_timezone_set('Asia/Taipei');
include('acount.php');
include('dbacount.php');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');
$sql="select studentname from checkin where studentnum='0'";
$rows=mysql_query($sql);
$row=mysql_fetch_row($rows);
if(empty($row[0])){
	$sql="insert into rollcall.checkin (studentnum,studentclass,studentname,satb,date_start) values ('0','h000','model','satb',curdate())";
	mysql_query($sql);
}
$sql="SELECT current_date - INTERVAL WEEKDAY( current_date ) 
DAY as weekmon";
$rows=mysql_query($sql);
$array=mysql_fetch_array($rows);
$sql="select * from checkin order by studentclass";
$rows=mysql_query($sql);
$weekmon=$array['weekmon'];
?>
<!doctype html>
<html>
<head>
<!--//////////-->
<!--//////////-->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>合唱團點名系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="styleeet" type="text/css">
<link href="jQueryAssets/jquery.ui.tabs.min.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="jQueryAssets/jquery.ui-1.10.4.tabs.min.js" type="text/javascript"></script>
<style>
tr:nth-child(even) {
	background: #ccc;
}
tr:nth-child(odd) {
	background-color: #FAFAFA;
}
table {
	border: 1px solid #666;
	border-collapse: collapse;
}
tr,td {
	border: 1px solid #666;
}
td:hover {
	background-color: #E6FBFF;
}
</style>
</head>
<body bgcolor="#E5E5E5" onLoad="MM_preloadImages('images/start2.png','images/rollcall_start.png')">

<header class="header">
  <div class="title">
  <a href="index.php"><strong style="color: white;"><br>
  <h1>合唱團點名系統</h1></strong></a>
  <span class="signout" style="color: red;"><a href="signin.php"><h4>---登出---</h4></a></span>
</div>
  </header>
<div class=" rollcall_start"><a href="rollcall.php?day=" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/rollcall_start.png',1)"><img src="images/rollcall_static.png" alt="" width="150" height="150" id="Image1"></a></div>
<main class="main1">
<div align="center"><br><br>
<form action="index.php" method="get">
  <input name="search" type="text" id="search" placeholder="請輸入姓名(敬請期待)" size="50" maxlength="30"><br>
    <input type="submit" formaction="index.php" formmethod="GET" value="搜尋">
    </form>
</div>
<br>
<div align="center">
    <table align="center" border="1">
	<tr align="center">
	<td width="100"><h4>班級</h4></td>
	<td width="100"><h4>姓名</h4></td>
	<td width="80"><h4>星期一</h4></td>
	<td width="80"><h4>星期二</h4></td>
	<td width="80"><h4>星期三</h4></td>
	<td width="80"><h4>星期四</h4></td>
	<td width="80"><h4>星期五</h4></td>
	</tr>
	<?php
	while($array=mysql_fetch_array($rows)){
		if(strcmp($array['studentnum'],'0')!=0){
			echo '<tr align="center">' ;
			echo "<td>".$array['studentclass']."</td>";
			echo "<td>".$array['studentname']."</td>";
			for($i=0;$i<=4;$i++){
				$sql="select '".$weekmon."' + interval ".$i." day as weekday";
				$week=mysql_query($sql);
				$weekday=mysql_fetch_array($week);
				$weekday=$weekday['weekday'];
				$weekday=str_replace('-','',$weekday);
				$week='d_'.$weekday;
				if(isset($array[$week])){
				$daytime=$array[$week];
				$time=substr($array[$week],11);
				$time=str_replace(':','',$time);
				if(!strcmp($daytime,'0000-00-00 00:00:00'))
					echo '<td><font color="red">缺曠</font></td>';
				elseif(!strcmp($daytime,'1111-11-11 11:11:11'))
					echo '<td><font color="gray">請假</font></td>';
				elseif($time>=120000&&$time<123600)
					echo '<td><font color="green">準時</font></td>';
				
				else
					echo '<td><font color="orange">遲到</font></td>';
				}
				else
					echo '<td><font color="black">--</font></td>';
			}
			echo '</tr>' ;
		}
	}
	mysql_close($db);
	?>
	</table>
</div>
    
</main>
<footer class="footer"><h4>Powered By &copy;ICED</h4></footer>
<script type="text/javascript">
$(function() {
	$( "#Tabs1" ).tabs(); 
});
</script>
</body>
</html>