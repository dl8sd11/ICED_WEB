<?php
if(empty($_COOKIE['signin'])){
if($_POST['rootID']!='ichoirzzz'||$_POST['rootPW']!='koorfamilie15')
	header('Location:signin_error.html');
else
setcookie('signin',"1",date('U')+900);
}
?>