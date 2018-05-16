<?php
if($_COOKIE[signin]!="1"){
if($_POST[rootID]!='testID'||$_POST[rootPW]!='testPW')
	header('Location:signin_error.html');
else
setcookie('signin',"1",date('U')+900);
}
?>
<?php
date_default_timezone_set('Asia/Taipei');
$db = @mysql_connect('localhost','root','1q1q1q1');
$dbname = 'rollcall';
mysql_select_db($dbname,$db);
mysql_query('SET CHARACTER SET UTF8;');//與DB建立連結
$sql="select count(*) from information_schema.columns where table_schema='rollcall' and table_name='checkin'";
$rows=mysql_query($sql);
$row=mysql_fetch_row($rows);
$daynum=$row[0]-3;//取得天數
$sql="SELECT * FROM checkin WHERE studentnum='h000000'";
$rows=mysql_query($sql);
$array=mysql_fetch_array($rows);//只得標準資料
settype($daynum,string);
$lastday=$array['day' . $daynum ];
$newday=substr($lastday,0,10);
$curday=date("Ymd");//現在日期
settype($newday,string);
$newday=str_replace('-','',$newday);//系統最後資料日期
settype($newday,integer);
settype($curday,integer);
if($newday==$curday){
	$sql="SELECT studentnum FROM checkin WHERE day" . $daynum . " = '0000-00-00 00:00:00'";
	$rows=mysql_query($sql);
	$uncheck=mysql_num_rows($rows);
	$sql="SELECT COUNT(*) AS counting FROM checkin";
	$checked=mysql_query($sql);
	$checked=mysql_fetch_row($checked);
	$checked=$checked[0]-$uncheck-1;
	}
else{
	$checked=0;
	$sql="SELECT COUNT(*) AS counting FROM checkin";
	$uncheck=mysql_query($sql);
	$uncheck=mysql_fetch_row($uncheck);
	$uncheck=$uncheck[0]-1;
}
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
          ['簽到', '比例%'],
          ['已簽到',<?php echo $checked; ?>],
          ['未簽到',<?php echo $uncheck; ?>]
        ]);

        var options = {
          
          pieHole: 0,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
        chart.draw(data, options);
      }
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
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
  </div>
  </header>
<main class="main">
  <div align="center"><br><br>
<form action="resault.php" method="get">
  <input name="rollcall" type="text" id="search" placeholder="請使用條碼機掃描條碼" size="50" maxlength="9">
    <input type="submit" formaction="rollcall_get" formmethod="GET" value="送出">
    </form>
<div class="rollcall_status">Content for  class "rollcall_status" Goes Here</div>
<div class="rocall_bussy">
<div class="check_form" align="center">
<h2>請假單</h2>
<form action="rollcall_get.php" method="post">
  <input name="bussy_name" type="text" id="bussy_name" placeholder="姓名" size="5" maxlength="5">
  <input name="bussy_date" type="date" id="bussy_date"><br>
  <input name="bussy_cont" type="text" id="bussy_cont" placeholder="請假事由">
    <input type="submit" formaction="rollcall_get.php" formmethod="POST" value="送出">
  </form><br>
  
  </div>
</div>

    
    </div>
    
</main>
<footer class="footer"><h4>Power By &copy;ICED</h4></footer>
<script type="text/javascript">
$(function() {
	$( "#Tabs1" ).tabs(); 
});
</script>
</body>
</html>