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
$F1=file($datachart);
    
	echo "<center><h1>HAL 10K</h1></center>";
	$time=date("Y-m-d H:i:s");
	echo "<center><h3>Time: $time</h3></center>";
	$last=explode(",",end($F1));
	//var_dump($last[7]);
	//die;
	if ($last[7]<=1) {
		$least=$emaLong-count($F1);
		echo "<center>************<br><b>$least/$emaLong</b> periods left to process graphic chart.</br>************</center>";
	}else{
		echo "<img src='chart.php";echo "'>";
	}
	echo "<center><form action='makesudden.php'  method='post'>";
	echo "Sudden MODE? (ATTENTION! OK?)";
	echo '&nbsp;Password: <input type="text" name="i"><br>';
	echo '&nbsp;Death mode: <input type="text" name="hal"><br><input type="submit" value="Submit">';
	echo "</form></center>";
	echo "<center><b>HAL10K</b> - Bitcoin trading bot</center>";
	echo "<center>Copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br></center>";
?>
