<?php
$sql="select count(*) from information_schema.columns where table_schema='rollcall' and table_name='checkin'";
$rows=mysql_query($sql);
$row=mysql_fetch_row($rows);
$daynum=$row[0]-5;//取得天數
$sql="SELECT * FROM checkin WHERE studentnum='0'";
$rows=mysql_query($sql);
$array=mysql_fetch_array($rows);//只得標準資料
settype($daynum,string);
$lastday=$array['day' . $daynum ];
$newday=substr($lastday,0,10);
$curday=date("Ymd");//現在日期
settype($newday,string);
$newday=str_replace('-','',$newday);//系統最後資料日期
settype($newday,integer);//系統最後日期整數
settype($curday,integer);//現在日期整數
?>