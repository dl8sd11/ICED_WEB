<?php
include('acount.php');
date_default_timezone_set('Asia/Taipei');
$db = @mysql_connect('127.0.0.1','root','1q1q1q1');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');
$sql="select * from rollcall where studentname='".$_GET[search]."'";
$rows=mysql_query($sql);
$array=mysql_fetch_array($rows);
$logavg=0;
$ontime=0;
$gone=0;
$bussy=0;
$late=0;
$all='';
$monthday=1;
$ii=0;
while($monthday){
	$sql="select current_date() - interval ".$ii." day as monthday";
	$month=mysql_query($sql);
	$monthday=mysql_fetch_array($month);
	$monthday=$monthday[monthday];
	$monthday=str_replace('-','',$monthday);
	$month='d_'.$monthday;
	$daytime=$array[$month];
	$time=substr($array[$month],11);
	$time=str_replace(':','',$time);
	if(!strcmp($daytime,'0000-00-00 00:00:00')){
		$all=$all.$logindex.'<font color="red"> [缺況]</font><br>';
		$gone+=1;;
	}
	elseif(!strcmp($daytime,'1111-11-11 11:11:11')){
		$all=$all.$logindex.'<font color="gray"> [請假]</font><br>';
		$bussy+=1;
	}
	elseif($time>=120000&&$time<124000){
		$all=$all.$daytime.'<font color="green"> [準時]</font><br>';
		$logavg+=1;
		$ontime+=1;
	}
	else{
		$all=$all.$daytime.'<font color="orange"> [遲到]</font><br>';
		$late+=1;
$ii+=1;
}
$logavg=$logavg/$ii; 
mysql_close($db);
?>
<!doctype html>
<html>
<head>
<!--//////////-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['出席狀況', '百分比(%)'],
          ['準時',<?php echo $ontime; ?>],
          ['遲到',<?php echo $late; ?>],
          ['缺曠',<?php echo $gone; ?>],
          ['請假',<?php echo $bussy; ?>]
        ]);

        var options = {
          title: ''
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
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
</head>
<body bgcolor="#E5E5E5" onLoad="MM_preloadImages('images/start2.png','images/rollcall_start.png')">

<header class="header">
  <div class="title">
  <a href="index.php"><strong style="color: white;"><br>
  <h1>合唱團點名系統</h1></strong></a>
  <span class="signout" style="color: red;"><a href="signin.php"><h4>---登出---</h4></a></span>
</div>
  </header>
<div class=" rollcall_start"><a href="rollcall.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/rollcall_start.png',1)"><img src="images/rollcall_static.png" alt="" width="150" height="150" id="Image1"></a></div>
<main class="main1">
  <div align="center"><br><br>
<form action="resault.php" method="get">
  <input name="search" type="text" id="search" placeholder="請輸入姓名" size="50" maxlength="30"><br>
    <input type="submit" formaction="resault.php" formmethod="GET" value="搜尋">
    <input type="submit" value="進階" formaction="setting.php">
    </form>
<br>
<div class="content">
<h3><?php echo $array[studentname]; ?></h3>
<!--<h4>學號: <?php //echo $array[studentnum]; ?></h4>-->
<p>
<?php echo $all; ?>
<h4>準時出席率: <?php echo $logavg; ?>%</h4>
</p>

</div>
<div class="chart" align="center">
  <div id="piechart" style="width: 700px; height: 400px;"></div>
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