<?php
$host=substr($_SERVER['SERVER_NAME'] , 0 , strpos($_SERVER['SERVER_NAME'] , '.'));
$groupDir=dirname(dirname(__FILE__)).'/'; 
$homeDir=$groupDir;
$tilde=dirname(dirname(__FILE__));
$compRoute=$homeDir.".comp/";
$pwdFile=$homeDir.".shellFile/pwd.txt";
$showFile=$homeDir.".shellFile/result.txt";
$cntCmd=' && ';
$setPath="PATH=\$PATH:/bin:/sbin";
$setHome="HOME=".$homeDir;
$setCol="COLUMNS=170";
$shrc=$homeDir.".shellFile/shrc";
$sourceFile='. '.$shrc;

$shellPW=''; // password for login
$shellACT=''; // account for login
$defaultACT='nobody';
?>
