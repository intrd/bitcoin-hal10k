<?php
/** 
* HAL10K Bitcoin trading bot
* @copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br>
*
* HAL10K is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License 2
* as published by the Free Software Foundation.
* 
* HAL10K is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
**/
    
	include("configs.php");
	$time=date("i");
	if ($_POST['hal']=="meuovo123654"){
		setset("kill",'1');
	}else{
		echo "#ERROR1&nbsp;";
	}
	if ($_POST['i']==$time){
		echo "SUDDEN DONE.";
		setset("sudden",'1');
	}else{
		echo "#ERROR2";
	}
	
?>
