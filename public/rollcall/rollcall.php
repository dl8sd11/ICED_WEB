<?php
include('acount.php');
?>
<?php
date_default_timezone_set('Asia/Taipei');
include('dbacount.php');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');//與DB建立連結
if(isset($_GET['day'])){
	$date=$_GET['day'];
	$sql="select * from checkin order by ". $date . " DESC;";
	$rows=mysql_query($sql);
}
mysql_close($db);
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
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
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
  </div>
  </header>
<main class="main1">
  <div align="center"><br><br>
<form action="rollcall.php" method="get" name="rollcall_form">
  <input name="rollcall" type="text" id="search" placeholder="掃描條碼" size="50" maxlength="9">
    <input type="submit" formaction="rollcall_get.php" formmethod="GET" value="送出">
    </form>
<script>
document.rollcall_form.rollcall.focus()
</script>
<?php
if(empty($_GET['name1'])!=1)
  echo '<h3><strong>'.$_GET['name1'].'</strong>已簽到</h3><br>';
?>
<br>

<div class="rollcall_status">
<table align="center" border="1">
<tr align="center">
<td width="100"><h4>班級</h4></td>
<td width="100"><h4>姓名</h4></td>
<td width="200"><h4>出席狀況</h4></td>
</tr>
<?php
if(!empty($date)){
while($array=@mysql_fetch_array($rows)){
	if(strcmp($array['studentnum'],'0')!=0){
echo '<tr align="center">' ;
echo "<td>".$array['studentclass']."</td>";
echo "<td>".$array['studentname']."</td>";
$daytime=$array[$date];
$time=substr($array[$date],11);
$mark = $time;
$time=str_replace(':','',$time);
if(!strcmp($daytime,'0000-00-00 00:00:00'))
	echo '<td><font color="red">未到</font></td>';
elseif(!strcmp($daytime,'1111-11-11 11:11:11'))
	echo '<td><font color="gray">請假</font></td>';
elseif($time>=120000&&$time<123600)
	echo '<td><font color="green">準時</font></td>';
else
	echo '<td><font color="orange">遲到 '.$mark.'</font></td>';
echo "</tr>";
	}
}
}
?>
</table>
</div>

<div class="rocall_bussy">
<div class="leave_form" align="center">
<h2>請假單</h2>
<form action="rollcall_get.php" method="get">
  <input name="bussy_name" type="text" id="bussy_name" placeholder="姓名" size="5" maxlength="5">
  <input name="bussy_date" type="date" id="bussy_date"><br>
  <input name="bussy_cont" type="text" id="bussy_cont" placeholder="請假事由">
  <input type="submit" formaction="rollcall_get.php" formmethod="GET" value="送出">
  </form><br>
  <?php 
  if(empty($_GET['name2'])!=1)
  echo '<h3>'.$_GET['name2'].'已請假</h3><br>'; 
  ?>
  <h4>今日請假</h4>
  <p>
  <?php 
  include('dbacount.php');
  $dbname = 'rollcall';
  mysql_select_db($dbname,$db);
  mysql_query('SET CHARACTER SET UTF8;');//與DB建立連結
  $sql="select * from rollcall.leave where leave_date=current_date()";
  $rows=mysql_query($sql);
  mysql_close($db);
  $leave_men='';
  while($array=mysql_fetch_array($rows)){
	  $leave_men=$leave_men.$array['name'].':'.$array['leave_cont']."<br>";
  }
  if(empty($leave_men))
  echo '無';
  else
  echo $leave_men;
  ?>
  </p>
  </div>
</div>

    
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