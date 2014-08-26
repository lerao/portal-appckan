<?php
 
$hostname_connect = "localhost";
$database_connect = "apps";
$username_connect = "root";
$password_connect = "root";
$connect = mysql_connect($hostname_connect, $username_connect, $password_connect) or die( mysql_error() );
mysql_select_db($database_connect, $connect);

?>