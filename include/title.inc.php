<?php
if(!defined('IN_TG'))
{
	exit('access defined!');
}
if(!defined('SCRIPT'))
{
	exit ('script error');
}
global $system;
?>
<title><?php echo $system['webname'];?></title>
<link rel="shortcut icon"href="favicon.ico"/>
<link rel="stylesheet"type="text/css"href="style<?php echo $system['skin']?>/basic.css"/>
<link rel="stylesheet"type="text/css"href="style<?php echo $system['skin']?>/<?php echo SCRIPT?>.css"/>